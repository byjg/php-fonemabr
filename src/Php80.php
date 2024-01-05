<?php

namespace ByJG\WordProcess;

class Php80
{
    // @todo: remove this class when PHP 8 is the minimum version
    public static function str_contains(string $haystack, string $needle): bool
    {
        if (function_exists('str_contains')) {
            return str_contains($haystack, $needle);
        }
        return '' === $needle || false !== strpos($haystack, $needle);
    }

    public static function str_ends_with(string $haystack, string $needle): bool
    {
        if (function_exists('str_ends_with')) {
            return str_ends_with($haystack, $needle);
        }
        return '' === $needle || substr($haystack, -strlen($needle)) === $needle;
    }
}