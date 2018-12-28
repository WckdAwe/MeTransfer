<?php
namespace codebase\App\Users;

use codebase\App\ErrorManager;
use codebase\App\Language;

class User
{
    private $id;
    private $username;
    private $email;
    private $password;
    private $created_at;
    private $isAdmin;

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
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function isAdmin(){
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT `username` FROM users WHERE `isAdmin` = :isAdmin AND `id` = :id');
        $STMT->bindParam(':isAdmin', $this->getIsAdmin(), \PDO::PARAM_INT);
        $STMT->bindParam(':id', $this->getId(), \PDO::PARAM_INT);
        $result = $STMT->fetch();

        if( !empty($result) )
            return true;
        return false;



    }
    public function changePassword($old_password, $new_password, $new_password_check){
        if(!password_verify($old_password, $this->getPassword())){
            ErrorManager::addError(Language::ERR_INCORRECT, 'password');
            return false;
        }

        if(strlen($new_password) < 5 || strlen($new_password) > 32)
            ErrorManager::addError(Language::ERR_LENGTH_BETWEEN, 'new password', 5, 32);

        if($new_password != $new_password_check)
            ErrorManager::addError(Language::ERR_ITEM_NOT_SAME, 'new password', 'new password check');

        if($new_password == $old_password)
            ErrorManager::addError(Language::ERR_ITEM_CANT_BE_SAME, 'new password', 'old password');

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

    public function info() : ?UserInfo
    {
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT * FROM user_info WHERE (`user_id` = :user_id)');
        $STMT->bindParam(':user_id',$this->id, \PDO::PARAM_INT);
        $STMT->execute();
        $STMT->setFetchMode(\PDO::FETCH_CLASS, __NAMESPACE__ . '\\UserInfo');
        $result = $STMT->fetch(\PDO::FETCH_CLASS);
        return $result ? $result : null;
    }

    public function files()
    {
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT * FROM user_files WHERE (`belongs_to` = :user_id) ORDER BY `id` DESC');
        $STMT->bindParam(':user_id',$this->id, \PDO::PARAM_INT);
        $STMT->execute();
        $STMT->setFetchMode(\PDO::FETCH_CLASS, __NAMESPACE__ . '\\UserFile');
        return $STMT->fetchAll(\PDO::FETCH_CLASS, __NAMESPACE__ . '\\UserFile');
    }

    public function updateInfo($password, $email, $first_name = '', $last_name = '', $gender = '0', $birthday = ''){
        //$info = $this->info();

        if(!password_verify($password, $this->getPassword())){
            ErrorManager::addError(Language::ERR_INCORRECT, 'password');
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            ErrorManager::addError(Language::ERR_INVALID, 'email');

        if(!empty($first_name) && (strlen($first_name) < 3 || strlen($first_name) > 35))
            ErrorManager::addError(Language::ERR_LENGTH_BETWEEN, 'first name', 3, 35);

        if(!empty($last_name) && (strlen($last_name) < 3 || strlen($last_name) > 35))
            ErrorManager::addError(Language::ERR_LENGTH_BETWEEN, 'last name', 3, 35);

        if(is_numeric($gender) && (strlen($gender) < 0 || strlen($gender) > 2))
            ErrorManager::addError(Language::ERR_STRING_SPECIFIC, 'gender', '(Not specified, Male, Female)');

//        if() // TODO: Birthday check!
//            ErrorManager::addError(Language::ERR_LENGTH_BETWEEN);


        if(!ErrorManager::hasErrors()){
            $PDO = \codebase\Databases\PHPDataObjects::getInstance();
            $PDO->beginTransaction();
            if($email != $this->getEmail()){
                $STMT = $PDO->prepare('SELECT `username`, `email` FROM users WHERE (`email` = :email)');
                $STMT->bindParam(':email', $email, \PDO::PARAM_STR);
                $STMT->execute();

                if($STMT->rowCount()){
                    ErrorManager::addError(Language::ERR_DB_ITEM_EXIST, 'email');
                    $PDO->rollBack();
                    return false;
                }

                $STMT = $PDO->prepare('UPDATE users SET `email` = :email WHERE (`id` = :id)');
                $STMT->bindParam(':id', $this->id, \PDO::PARAM_INT);
                $STMT->bindParam(':email', $email, \PDO::PARAM_STR);
                $STMT->execute();
            }

            $STMT = $PDO->prepare('UPDATE user_info SET `first_name` = :first_name,
                                                                  `last_name` = :last_name,
                                                                  `gender` = :gender,
                                                                  `birthday` = :birthday 
                                                                   WHERE (`user_id` = :id)');
            $STMT->bindParam(':id', $this->id, \PDO::PARAM_INT);
            $STMT->bindParam(':first_name', $first_name, \PDO::PARAM_STR);
            $STMT->bindParam(':last_name', $last_name, \PDO::PARAM_STR);
            $STMT->bindParam(':gender', $gender, \PDO::PARAM_INT);
            $STMT->bindParam(':birthday', $birthday, \PDO::PARAM_STR);
            $STMT->execute();
            $PDO->commit();
            return true;
        }
        return false;
    }
}