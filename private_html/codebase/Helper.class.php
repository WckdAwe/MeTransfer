<?php
namespace codebase;


class Helper
{
//    public static function isset_or_def($variable, $default){
//        return isset($variable) ? $variable : $default;
//    }

    public static function redirect($url, $statusCode = 303)
    {
        header('Location: ' . $url, true, $statusCode);
        die();
    }

    public static function get_string($array, $index, $default = null) {
        if (isset($array[$index]) && strlen($value = trim($array[$index])) > 0) {
            return get_magic_quotes_gpc() ? stripslashes($value) : $value;
        } else {
            return $default;
        }
    }
}