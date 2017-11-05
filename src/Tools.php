<?php

namespace Attogram\SharedMedia\Sandbox;

/**
 * SharedMedia Tools
 */
class Tools
{
    const VERSION = '0.0.2';

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
     * Get the length of the longest string in an array
     *
     * @param array $array
     * @return int
     */
    public static function getLongestStringLengthInArray(array $array)
    {
        foreach ($array as $key => $val) {
            if (!is_string($val)) {
                unset($array[$key]);
            }
        }
        if (empty($array)) {
            return 0;
        }
        return max(array_map('strlen', $array));
    }
}
