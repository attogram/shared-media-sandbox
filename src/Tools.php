<?php

namespace Attogram\SharedMedia\Sandbox;

/**
 * SharedMedia Sandbox Tools
 */
class Tools
{
    const VERSION = '1.1.4';

    /**
     * Check if an <option> in a <select> is selected
     *
     * @param string $str1
     * @param string $str2
     *
     * @return string
     */
    public static function isSelected($str1, $str2)
    {
        if ($str1 == $str2) {
            return ' selected ';
        }
        return '';
    }

    /**
     * make a string safe for web output
     *
     * @param string|mixed $string
     * @return string
     */
    public static function safeString($string)
    {
        if (is_string($string)) {
            return htmlentities($string);
        }
        return htmlentities(print_r($string, true));
    }

    /**
     * get the value of a global _GET variable
     *
     * @param string $name
     * @return mixed
     */
    public static function getGet($name)
    {
        if (isset($_GET[$name])) {
            return trim(urldecode($_GET[$name]));
        }
        return null;
    }

    /**
     * Check if a global _GET variable is set
     *
     * @param string $name
     * @return mixed
     */
    public static function hasGet($name)
    {
        if (isset($_GET[$name])) {
            return true;
        }
        return false;
    }
}
