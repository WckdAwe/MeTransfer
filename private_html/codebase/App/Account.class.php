<?php

namespace codebase\App;

class Account
{
    private static $instance;
    private $errorManager;

    private function __construct()
    {
        $this->errorManager = new ErrorManager();
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function tryLogin($username, $password){
        if(self::isLoggedIn()) return false; // User is already logged in

        $errors = array();

        if(strlen($username) < 5 || strlen($username) > 32) // TODO: Login with email too!
            $this->errorManager->addError(Language::ERR_USERNAME);

        if(!empty($errors)){
            // TODO: Redirect to login form with errors!
            print_r($errors);
            return false;
        }else {
            $PDO = \codebase\Databases\PHPDataObjects::getInstance();

            $STMT = $PDO->prepare('SELECT `username`, `password` FROM users WHERE (`username` = :username)');
            $STMT->bindParam(':username', $username, \PDO::PARAM_STR);
            $STMT->execute();

            $result = $STMT->fetch(\PDO::FETCH_ASSOC);
            if(!empty($result)){
                if(password_verify($password, $result['password'])){
                    echo 'GOOD PASSWORD\n';

                    $_SESSION['username'] = $result['username'];
                    echo 'LOGIN SUCCESS\n';
                    return true;
                }
                echo 'BAD PASSWORD\n';
                return false;
            }
            printf('Login Failure\n');
            print_r($result);
            return false;
        }
    }

    public function trySignup($username, $password, $email, $terms, $birthday = null, $gender = null){
        if(self::isLoggedIn()) return false;
        //if(!isset($_POST['submit'])) return false; // TODO: Maybe Redirect?

//        $username = Helper::get_string($_POST, 'username', null);
//        $password = Helper::get_string($_POST, 'password', null);
//        $email = Helper::get_string($_POST, 'email', null);
//        $birthday = Helper::get_string($_POST, 'birthday', null); // TODO: Implement birthday check
//        $gender = Helper::get_string($_POST, 'gender', null); // TODO: Implement gender check
//        $terms = Helper::get_string($_POST, 'tos', null);

        if(!isset($terms))
            $this->errorManager->addError(Language::TOS_ACCEPT);

        if(strlen($username) < 5 || strlen($username) > 32)
            $this->errorManager->addError(Language::ERR_USERNAME);

        if(strlen($password) < 5 || strlen($password) > 32)
            $this->errorManager->addError(Language::ERR_PASSWORD);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $this->errorManager->addError(Language::ERR_EMAIL);

        if($this->errorManager->hasErrors()){
            // TODO: Redirect to registration form with errors!
            return false;
        }else {
            $PDO = \codebase\Databases\PHPDataObjects::getInstance();
            $STMT = $PDO->prepare('SELECT `username`, `email` FROM users WHERE (`username` = :username or `email` = :email)');
            $STMT->bindParam(':username', $username, \PDO::PARAM_STR);
            $STMT->bindParam(':email', $email, \PDO::PARAM_STR);
            $STMT->execute();

            if($STMT->rowCount()){
                $result = $STMT->fetch(\PDO::FETCH_ASSOC);

                if($result['username'] == $username)
                    $this->errorManager->addError(Language::ERR_USERNAME_EXISTS);
                if($result['email'] == $email)
                    $this->errorManager->addError(Language::ERR_EMAIL_EXISTS);

                // TODO: Redirect to registration form with errors!
                return false;
            }else{
                $password = password_hash($password, PASSWORD_BCRYPT);
                $STMT = $PDO->prepare('INSERT INTO users values(DEFAULT, :username, :email, :password, DEFAULT)');
                $STMT->bindParam(':username', $username, \PDO::PARAM_STR);
                $STMT->bindParam(':email', $emaiwl, \PDO::PARAM_STR);
                $STMT->bindParam(':password', $password, \PDO::PARAM_STR);
                $STMT->execute();

                // TODO: Redirect to login page or to account page?
                return true;
            }
        }
    }

    public static function isLoggedIn(){
        return isset($_SESSION['username']);
    }


    public function tryLogout(){
        if(self::isLoggedIn()){
            session_unset();
            session_destroy();
            header("Refresh:3;url=/index.php");
        }else{
            header("Refresh:0;url=/index.php");
        }
    }
}