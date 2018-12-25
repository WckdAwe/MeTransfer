<?php

namespace codebase\App;

class ErrorManager
{
    protected static $errors = [];

    public static function getErrors()
    {
        return self::$errors;
    }

    public static function printErrors()
    {
        $result = '';
        foreach (self::getErrors() as $error) {
            $result = '<li>' . htmlentities($error) . '</li>';
        }
        return !empty($result) ? '<ul class="error">'.$result.'</ul>' : '';
    }

    public static function addError(string $error, $param = null)
    {
        // TODO: Setup parameters
        array_push(self::$errors, $error);
    }

    public static function hasErrors(){
        return !empty(self::getErrors());
    }

    public static function cleanErrors(){
        empty(self::getErrors());
        self::$errors = [];
    }
}