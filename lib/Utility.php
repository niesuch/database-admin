<?php

namespace Lib;

/**
 * Utility class
 * 
 * @autor Niesuch
 */
class Utility {

    /**
     * Return open char and its position in string 
     * @param type $string
     * @param type $position
     * @return type
     */
    public static function get_open_char($string, $position) {
        $char = null;
        $position_temp = null;
        $pattern = "/(\/\*|^--|(?<=\s)--|#|'|\"|;)/";
        $matches = null;

        if (preg_match($pattern, $string, $matches, PREG_OFFSET_CAPTURE, $position)) {
            $char = $matches[1][0];
            $position_temp = $matches[1][1];
        }

        return array($char, $position_temp);
    }

    /**
     * Return close char and its position in string 
     * @param type $string
     * @param type $position
     * @param type $char
     * @return type
     */
    public static function get_close_char($string, $position, $char) {
        $char_temp = null;
        $position_temp = null;
        $matches = null;

        $close_char = array(
            '\'' => '(?<!\\\\)\'|(\\\\+)\'',
            '"' => '(?<!\\\\)"',
            '/*' => '\*\/',
            '#' => '[\r\n]+',
            '--' => '[\r\n]+',
        );

        $pattern = "/(" . $close_char[$char] . ")/";

        if ($close_char[$char] && preg_match($pattern, $string, $matches, PREG_OFFSET_CAPTURE, $position)) {
            $char_temp = $matches[1][0];
            $position_temp = $matches[1][1];
        }

        return array($char_temp, $position_temp);
    }

}
