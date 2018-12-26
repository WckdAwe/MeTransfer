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

    public static function errorRedirect($type = null)
    {
        self::redirect('/err');
    }

    public static function HTMLSelected($val1, $val2)
    {
        return $val1 == $val2 ? 'selected="selected"' : '';
    }

    public static function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public static function generateUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = ''.substr($charid, 0, 8).$hyphen
                    .substr($charid, 8, 4).$hyphen
                    .substr($charid,12, 4).$hyphen
                    .substr($charid,16, 4).$hyphen
                    .substr($charid,20,12);
            return $uuid;
        }
    }

    public static function get_string($array, $index, $default = null) {
        if (isset($array[$index]) && strlen($value = trim($array[$index])) > 0) {
            return get_magic_quotes_gpc() ? stripslashes($value) : $value;
        } else {
            return $default;
        }
    }
}