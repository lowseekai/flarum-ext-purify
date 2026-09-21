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

use Flarum\Post\Event\Saving;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Justoverclock\Purify\Support\TextObscurer;

class ObscureEmail
{
    protected SettingsRepositoryInterface $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(Saving::class, $this->obscureEmail(...));
    }

    public function obscureEmail(Saving $event): void
    {
        if (!$this->isEnabled('justoverclock-purify.AlsoEmail')) {
            return;
        }

        $event->post->content = TextObscurer::transformContent(
            $event->post->content,
            fn (string $content): string => $this->replaceEmails($content)
        );
    }

    private function replaceEmails(string $content): string
    {
        $pattern = '/[a-z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?(?:\.[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)+/iu';

        return TextObscurer::replaceMatches($content, $pattern);
    }

    private function isEnabled(string $key): bool
    {
        return filter_var($this->settings->get($key), FILTER_VALIDATE_BOOLEAN);
    }
}
