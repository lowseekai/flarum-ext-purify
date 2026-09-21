<?php

namespace Justoverclock\Purify\Support;

final class TextObscurer
{
    /**
     * Apply a text transformation to post content without touching empty
     * content or changing the legacy array-with-raw representation.
     */
    public static function transformContent(mixed $content, callable $transform): mixed
    {
        if (is_array($content)) {
            if (isset($content['raw']) && is_string($content['raw']) && $content['raw'] !== '') {
                $content['raw'] = $transform($content['raw']);
            }

            return $content;
        }

        if (!is_string($content) || $content === '') {
            return $content;
        }

        return $transform($content);
    }

    public static function obscure(string $value): string
    {
        $length = function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);

        return str_repeat('*', $length);
    }

    public static function replaceMatches(string $content, string $pattern): string
    {
        $result = preg_replace_callback(
            $pattern,
            static fn (array $match): string => self::obscure($match[0]),
            $content
        );

        return $result ?? $content;
    }
}
