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
        if (!is_string($string)) {
            return $string;
        }
        return htmlentities($string);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function getGet($name)
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
     * get a value from an array
     *
     * @param array $array
     * @param mixed|string $value
     */
    public static function getFromArray(array $array, string $value)
    {
        if (isset($array[$value])) {
            return $array[$value];
        }
        return '';
    }
}
