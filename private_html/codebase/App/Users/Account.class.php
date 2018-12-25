<?php

namespace codebase\App\Users;

use codebase\App\ErrorManager;
use codebase\App\Language;
use codebase\Helper;

class Account
{
    private static $instance;


    public static function getInstance()
    {
        if (is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function tryLogin($username, $password){
        if(self::isLoggedIn()) return false; // User.class is already logged in

        if(strlen($username) < 5 || strlen($username) > 32) // TODO: Login with email too!
            ErrorManager::addError(Language::ERR_USERNAME);

        if(!ErrorManager::hasErrors()){
            $PDO = \codebase\Databases\PHPDataObjects::getInstance();

            $STMT = $PDO->prepare('SELECT `username`, `password` FROM users WHERE (`username` = :username)');
            $STMT->bindParam(':username', $username, \PDO::PARAM_STR);
            $STMT->execute();

            $result = $STMT->fetch(\PDO::FETCH_ASSOC);
            if(!empty($result)){
                if(password_verify($password, $result['password'])){
                    $_SESSION['username'] = $result['username'];
                    Helper::redirect('/account');
                    return true;
                }
                ErrorManager::addError(Language::ERR_INCORRECT, 'password');
                return false;
            }
            ErrorManager::addError(Language::ERR_DB_ITEM_NO_EXIST, 'username');
            return false;
        }
        return false;
    }

    public function trySignup($username, $email, $password, $password_check, $terms){
        if(self::isLoggedIn()) return false;

        if(!isset($terms))
            ErrorManager::addError(Language::TOS_ACCEPT);

        if(strlen($username) < 5 || strlen($username) > 32)
            ErrorManager::addError(Language::ERR_LENGTH_BETWEEN, 'username', 5, 32);

        if(strlen($password) < 5 || strlen($password) > 32)
            ErrorManager::addError(Language::ERR_LENGTH_BETWEEN, 'password', 5, 32);

        if($password != $password_check)
            ErrorManager::addError(Language::ERR_ITEM_NOT_SAME, 'password', 'password check');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            ErrorManager::addError(Language::ERR_INVALID, 'email');

        if(!ErrorManager::hasErrors()){
            $PDO = \codebase\Databases\PHPDataObjects::getInstance();
            $STMT = $PDO->prepare('SELECT `username`, `email` FROM users WHERE (`username` = :username or `email` = :email)');
            $STMT->bindParam(':username', $username, \PDO::PARAM_STR);
            $STMT->bindParam(':email', $email, \PDO::PARAM_STR);
            $STMT->execute();

            if($STMT->rowCount()){
                $result = $STMT->fetch(\PDO::FETCH_ASSOC);

                if($result['username'] == $username)
                    ErrorManager::addError(Language::ERR_DB_ITEM_EXIST, 'username');
                if($result['email'] == $email)
                    ErrorManager::addError(Language::ERR_DB_ITEM_EXIST, 'email');

                return false;
            }else{
                $PDO->beginTransaction();
                $password = password_hash($password, PASSWORD_BCRYPT);
                $STMT = $PDO->prepare('INSERT INTO users values(DEFAULT, :username, :email, :password, DEFAULT)');
                $STMT->bindParam(':username', $username, \PDO::PARAM_STR);
                $STMT->bindParam(':email', $email, \PDO::PARAM_STR);
                $STMT->bindParam(':password', $password, \PDO::PARAM_STR);
                $STMT->execute();
                $user_id = $PDO->lastInsertId();

                $STMT = $PDO->prepare('INSERT INTO user_info (`user_id`) values(:user_id)');
                $STMT->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
                $STMT->execute();

                $PDO->commit();
                Helper::redirect('/account');
                return true;
            }
        }
    }

    public static function user() : User
    {
        if(!self::isLoggedIn()) return null;

        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT * FROM users WHERE (`username` = :username)');
        $STMT->bindParam(':username', $_SESSION['username'], \PDO::PARAM_STR);
        $STMT->execute();
        $STMT->setFetchMode(\PDO::FETCH_CLASS, __NAMESPACE__ . '\\User');
        return $STMT->fetch(\PDO::FETCH_CLASS);
    }

    public static function isLoggedIn() : bool
    {
        return isset($_SESSION['username']);
    }

    public static function logout($redirect_url = '/') : void
    {
        if(self::isLoggedIn()) {
            session_unset();
            session_destroy();
        }
        Helper::redirect($redirect_url);
    }
}