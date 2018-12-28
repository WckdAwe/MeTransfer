<?php
namespace codebase\App;
use codebase\App\Language;
use codebase\App\ErrorManager;
use codebase\Helper;
use codebase\Emails\Email;


class PasswordResetWithEmail{
    private $id;
    private $email;
    private $token;
    private $used;

    public function __construct($email){
        $this->email = $email;
    }

    public function setUsed(){
        $this->used = 0;
    }
    public function setEmail($email){
        $this->email = $email;
    }

    public function setToken($token){
        $this->token = $token;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }


    
    public function checkEmail(){
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT `id` FROM users WHERE (`email` = :email)');
        $STMT->bindParam(':email', $this->email, \PDO::PARAM_STR);
        $STMT->execute(); 
        $result = $STMT->fetch(\PDO::FETCH_ASSOC);

        if(empty($result)){
            return false;
        }
        $this->id = $result['id'];
        return true;
    }

    public function updateDatabase(){
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $PDO->beginTransaction();
        $STMT = $PDO->prepare('INSERT INTO password_reset values(DEFAULT, :token, :used, :belongsTo, DEFAULT)');
        $STMT->bindParam(':token', $this->token, \PDO::PARAM_STR);
        $STMT->bindParam(':used', $this->used, \PDO::PARAM_INT);
        $STMT->bindParam(':belongsTo', $this->id, \PDO::PARAM_INT);
        $STMT->execute();
        $PDO->commit();
    
    }

    public function run(){
        if(!$this->checkEmail()){
            ErrorManager::addError(Language::ERR_DB_ITEM_NO_EXIST, 'email');
            return false;
        }
        $this->setToken(Helper::generateUID());
        $this->setUsed();

        $MSG = 'You forgot your password? Here take this token:  '.$this->token;
        $MSG.'it will help you reset your password';
        $subject = 'Password reset token';
        
        #EMAIL HERE :
        #Email::setReceivers($this->email);
        #Email::setSubject($subject);
        #Email::setContents($MSG);
        #Email::sendEmail();

        $this->updateDatabase();

        return true;
    }

}

?>