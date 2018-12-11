<?php

namespace codebase\App;


use http\Exception\BadMethodCallException;

class Account
{
    public static function tryLogin($username, $password){
        if(self::isLoggedIn()) return; // User is already logged in.
        throw BadMethodCallException;
    }

    public static function tryLogout(){
        if(self::isLoggedIn()){
            session_unset();
            session_destroy();
            header("Refresh:2;url=/index.php");

        }else{
            header("Refresh:0;url=/index.php");
        }
        throw BadMethodCallException;
    }

    public static function trySignup($username, $password){
        if(self::isLoggedIn()) return false;
        if(!isset($_POST['submit'])) return false; // TODO: Maybe Redirect?

        $username = $_POST['user'];
        $password = $_POST['pass'];
        $email = $_POST['email'];
        $birthday = $_POST['birthday']; // TODO: Implement birthday check
        $gender = $_POST['gender']; // TODO: Implement gender check
        $terms = $_POST['terms'];

        $errors = array();

        if(!isset($terms))
            array_push($errors, Language::$TOS_ACCEPT);

        if(strlen($username) < 5 || strlen($username) > 32)
            array_push($errors, Language::$ERR_USERNAME);

        if(strlen($password) < 5 || strlen($password) > 32)
            array_push($errors, Language::$ERR_PASSWORD);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            array_push($errors, Language::$ERR_EMAIL);

        if(!empty($errors)){
            // TODO: Redirect to registration form with errors!
        }else {
            $PDO = \codebase\Databases\PHPDataObjects::getInstance();
            $STMT = $PDO->prepare('SELECT * FROM users WHERE (`username` = :username or `email` = :email)');
            $STMT->bindParam(':username', $username, \PDO::PARAM_STR);
            $STMT->bindParam(':email', $email, \PDO::PARAM_STR);
            $STMT->execute();

//            echo 'Testing to see what\'s inside the DB: ';
//            echo $STMT->fetchAll();
//            $STMT->
        }

        throw BadMethodCallException;
    }

    public static function isLoggedIn(){
        return isset($_POST['user']);
    }
}