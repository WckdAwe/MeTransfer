<?php

namespace codebase\App;

class ErrorManager
{
    protected $errors = [];

    public function getErrors()
    {
        return $this->errors;
    }

    public function printErrors()
    {
        $result = '';
        foreach ($this->getErrors() as $error) {
            $result = '<li>' . htmlentities($error) . '</li>';
        }
        return $result;
    }

    public function addError(string $error, $param = null)
    {
        // TODO: Setup parameters
        array_push($this->getErrors(), $error);
    }

    public function hasErrors(){
        return !empty($this->getErrors());
    }

    public function cleanErrors(){
        empty($this->getErrors());
        $this->errors = [];
    }
}