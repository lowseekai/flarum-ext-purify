<?php

/*
 * This file is part of purify extension by Marco Colia.
 *
 * Copyright (c) Marco Colia.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Justoverclock\Purify\Listeners;

use Flarum\Settings\SettingsRepositoryInterface;
use Justoverclock\Purify\Support\TextObscurer;
use Illuminate\Contracts\Events\Dispatcher;
use Flarum\Post\Event\Saving;

class ObscureBadWords
{
    protected SettingsRepositoryInterface $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;

    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(Saving::class, $this->filterPostContent(...));
    }

    public function filterPostContent(Saving $event): void
    {
        $event->post->content = TextObscurer::transformContent(
            $event->post->content,
            fn (string $content): string => $this->filterOutBadWords($content)
        );
    }

    private function filterOutBadWords(string $content): string
    {
        $badWordsSetting = $this->settings->get('justoverclock-purify.badWordsList');

        if (!is_string($badWordsSetting) || trim($badWordsSetting) === '') {
            return $content;
        }

        $badWords = explode(',', $badWordsSetting);

        foreach ($badWords as $badWord) {
            $badWord = trim($badWord);

            if (!empty($badWord)) {
                $pattern = '/' . preg_quote($badWord, '/') . '/iu';
                $content = TextObscurer::replaceMatches($content, $pattern);
            }
        }

        return $content;
    }
}
