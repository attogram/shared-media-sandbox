<?php

namespace Attogram\SharedMedia\Sandbox;

/**
 * SharedMedia Tools
 */
class Tools
{
    const VERSION = '0.0.1';

    /**
     * @param string $str1
     * @param string $str2
     */
    public static function isSelected($str1, $str2)
    {
        if ($str1 == $str2) {
            return ' selected ';
        }
    }

    /**
     * make a string safe for web output
     *
     * @param string|mixed $string
     */
    public static function safeString($string)
    {
        if (is_string($string)) {
            return htmlentities($string);
        }
        return htmlentities(print_r($string, true));
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function getGet(string $name)
    {
        return isset($_GET[$name]) ? trim(urldecode($_GET[$name])) : null;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function hasGet($name)
    {
        return isset($_GET[$name]) ? true : false;
    }

    /**
     * @param array $array
     * @return int
     */
    public static function getLongestStringLengthInArray(array $array)
    {
        $length = 0;
        foreach ($array as $string) {
            if (!is_string($string)) {
                continue;
            }
            if (strlen($string) > $length) {
                $length = strlen($string);
            }
        }
        return $length;
    }
}
