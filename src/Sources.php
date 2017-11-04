<?php

namespace Attogram\SharedMedia\Sandbox;

/**
 * SharedMedia Sources
 */
class Sources
{
    const VERSION = '0.0.1';

    public static $sources = [
        'commons'        => 'https://commons.wikimedia.org/w/api.php',
        'en.wikipedia'   => 'https://en.wikipedia.org/w/api.php',
    ];

    public static function getSource()
    {
        // return the first source
        return self::$sources[array_keys(self::$sources)[0]];
    }
}
