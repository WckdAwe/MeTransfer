<?php

namespace codebase\App;

class UserManager {

    private $PROJECTS = array();


    public function getProjects() {
        return $this->PROJECTS;
    }

}

class User {
    public $id;
    public $username;
    public $email;
    public $password;
    public $created_at;
}

class UserDetails {
    public $first_name, $last_name;
    public $gender;
    public $birthday;
}