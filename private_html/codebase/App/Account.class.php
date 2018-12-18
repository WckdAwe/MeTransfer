<?php

namespace codebase\App;


use http\Exception\BadMethodCallException;
use codebase\Helper;

class Account
{
    public static function tryLogin($username, $password){
        if(self::isLoggedIn()) return false; // User is already logged in.
        throw BadMethodCallException;
    }

    public static function tryLogout(){
        if(self::isLoggedIn()){
            session_unset();
            session_destroy();
            header("Refresh:3;url=/index.php");
        }else{
            header("Refresh:0;url=/index.php");
        }
    }

    public static function trySignup(){
        if(self::isLoggedIn()) return false;
        //if(!isset($_POST['submit'])) return false; // TODO: Maybe Redirect?

        $username = Helper::isset_or_def($_POST['username'], null);
        $password = Helper::isset_or_def($_POST['password'], null);
        $email = Helper::isset_or_def($_POST['password'], null);
        $birthday = Helper::isset_or_def($_POST['birthday'], null); // TODO: Implement birthday check
        $gender = Helper::isset_or_def($_POST['gender'], null); // TODO: Implement gender check
        $terms = Helper::isset_or_def($_POST['tos'], null);

        /* DUMMY VALUES */
        $username = "wckdawe";
        $password = "123456";
        $email = "wckdawe@test.com";
        $terms = 1;
        /* TODO: ^ REMOVE THE ABOVE */

        $errors = array();

        if(!isset($terms))
            array_push($errors, Language::TOS_ACCEPT);

        if(strlen($username) < 5 || strlen($username) > 32)
            array_push($errors, Language::ERR_USERNAME);

        if(strlen($password) < 5 || strlen($password) > 32)
            array_push($errors, Language::ERR_PASSWORD);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            array_push($errors, Language::ERR_EMAIL);

        if(!empty($errors)){
            // TODO: Redirect to registration form with errors!
            echo "Errors:\n";
            print_r($errors);
        }else {
            $PDO = \codebase\Databases\PHPDataObjects::getInstance();
            $STMT = $PDO->prepare('SELECT * FROM users WHERE (`username` = :username or `email` = :email)');
            $STMT->bindParam(':username', $username, \PDO::PARAM_STR);
            $STMT->bindParam(':email', $email, \PDO::PARAM_STR);
            $STMT->execute();

            echo 'Testing to see what\'s inside the DB: ';
            echo $STMT->fetchAll();
//            $STMT->
        }
    }

    public static function isLoggedIn(){
        return isset($_POST['user']);
    }
}