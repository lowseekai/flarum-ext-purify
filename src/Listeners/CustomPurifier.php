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

class CustomPurifier
{
    protected SettingsRepositoryInterface $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(Saving::class, $this->customPurifier(...));
    }

    public function customPurifier(Saving $event): void
    {
        if (!$this->isEnabled('justoverclock-purify.CustomRegexp')) {
            return;
        }

        $customPurifierPattern = $this->settings->get('justoverclock-purify.regexcustom');
        $pattern = $this->normalizePattern($customPurifierPattern);

        if ($pattern === null) {
            return;
        }

        $event->post->content = TextObscurer::transformContent(
            $event->post->content,
            fn (string $content): string => TextObscurer::replaceMatches($content, $pattern)
        );
    }

    private function normalizePattern(mixed $pattern): ?string
    {
        if (!is_string($pattern) || trim($pattern) === '') {
            return null;
        }

        $pattern = trim($pattern);

        if (@preg_match($pattern, '') !== false) {
            return $pattern;
        }

        $pattern = '~(?:'.str_replace('~', '\~', $pattern).')~iu';

        return @preg_match($pattern, '') === false ? null : $pattern;
    }

    private function isEnabled(string $key): bool
    {
        return filter_var($this->settings->get($key), FILTER_VALIDATE_BOOLEAN);
    }
}
