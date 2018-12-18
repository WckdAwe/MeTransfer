<?php
namespace codebase;


class Helper
{
    public static function isset_or_def($variable, $default){
        return isset($variable) ? $variable : $default;
    }
}