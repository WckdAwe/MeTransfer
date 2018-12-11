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
    public $fname, $lname;
    public $gender;
    public $birthday;
}