<?php

namespace codebase\App\Users;

class UserInfo {
    private $first_name, $last_name;
    private $gender;
    private $birthday;

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getBirthday()
    {
        return $this->birthday;
    }
}