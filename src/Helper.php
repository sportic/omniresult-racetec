<?php

namespace Sportic\Timing\RaceTecClient;

/**
 * Class Helper
 * @package ByTIC\MFinante
 */
class Helper
{

    /**
     * Convert a string to camelCase. Strings already in camelCase will not be harmed.
     *
     * @param  string $str The input string
     *
     * @return string camelCased output string
     */
    public static function camelCase($str)
    {
        $str = self::convertToLowercase($str);

        return preg_replace_callback(
            '/_([a-z])/',
            function ($match) {
                return strtoupper($match[1]);
            },
            $str
        );
    }

    /**
     * @param $obj
     *
     * @return array
     */
    public static function objectToArray($obj)
    {
        if (is_object($obj)) {
            $obj = (array) $obj;
        }
        if (is_array($obj)) {
            $new = [];
            foreach ($obj as $key => $val) {
                $new[$key] = self::objectToArray($val);
            }
        } else {
            $new = $obj;
        }

        return $new;
    }

    /**
     * Convert strings with underscores to be all lowercase before camelCase is preformed.
     *
     * @param  string $str The input string
     *
     * @return string The output string
     */
    protected static function convertToLowercase($str)
    {
        $explodedStr = explode('_', $str);

        if (count($explodedStr) > 1) {
            $lowerCasedStr = [];
            foreach ($explodedStr as $value) {
                $lowerCasedStr[] = strtolower($value);
            }
            $str = implode('_', $lowerCasedStr);
        }

        return $str;
    }
}
