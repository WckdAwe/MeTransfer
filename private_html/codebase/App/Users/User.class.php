<?php
namespace codebase\App\Users;

use codebase\App\ErrorManager;
use codebase\App\Language;
use const http\Client\Curl\Features\LARGEFILE;

class User
{
    private $id;
    private $username;
    private $email;
    private $password;
    private $created_at;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }


    public function changePassword($old_password, $new_password, $new_password_check){
        if(!password_verify($old_password, $this->getPassword())){
            ErrorManager::addError(Language::ERR_PASSWORD_INCORRECT);
            return false;
        }

        if(strlen($new_password) < 5 || strlen($new_password) > 32)
            ErrorManager::addError(Language::ERR_PASSWORD);

        if($new_password != $new_password_check)
            ErrorManager::addError(Language::ERR_PASSWORD_CHECK);

        if($new_password == $old_password)
            ErrorManager::addError(Language::ERR_PASSWORD_SAME);

        if(!ErrorManager::hasErrors()){
            $new_password = password_hash($new_password, PASSWORD_BCRYPT);
            $PDO = \codebase\Databases\PHPDataObjects::getInstance();
            $STMT = $PDO->prepare('UPDATE users SET `password` = :password WHERE (`username` = :username)');
            $STMT->bindParam(':username', $this->getUsername(), \PDO::PARAM_STR);
            $STMT->bindParam(':password', $new_password, \PDO::PARAM_STR);
            $STMT->execute();
            return true;
        }
        return false;
    }
}