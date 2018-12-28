<?php
namespace codebase\App;
use codebase\App\Language;
use codebase\App\ErrorManager;
use codebase\Helper;

class PasswordResetSuccess{

    private $email;
    private $token;
    private $used;
    private $new_password;
    private $check_new_password;
    private $id;


    public function __construct($email, $token, $new_password, $check_new_password, $id){
        $this->email = $email;
        $this->token = $token;
        $this->new_password = $new_password;
        $this->check_new_password = $check_new_password;
        $this->id = $id;

    }

    public function setUsed($used){
        $this->used = $used;
    }



    public function checkPassword(){
        if(strlen($this->new_password) < 5 || strlen($this->new_password) > 32){
            ErrorManager::addError(Language::ERR_LENGTH_BETWEEN, 'password', 5, 32);
        }

        if($this->new_password != $this->check_new_password){
            ErrorManager::addError(Language::ERR_ITEM_NOT_SAME, 'password', 'password check');
        }
        if(!ErrorManager::hasErrors()){
            return true;
        }
        return false;   
    }

    public function updatePassword(){
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $PDO->beginTransaction();
        $this->new_password = password_hash($this->new_password, PASSWORD_BCRYPT);
        $STMT = $PDO->prepare('UPDATE users SET `password`= :new_password WHERE (`email`= :email)');
        $STMT->bindParam(':email', $this->email, \PDO::PARAM_STR);
        $STMT->bindParam(':new_password', $this->new_password, \PDO::PARAM_STR);
        $STMT->execute();
        $PDO->commit();
    }

    public function updateTokenUsed(){
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $PDO->beginTransaction();
        $used = 1;
        $STMT = $PDO->prepare('UPDATE password_reset SET `used` = :used WHERE (`uid` = :token)');
        $STMT->bindParam(':used', $used, \PDO::PARAM_INT);
        $STMT->bindParam(':token', $this->token, \PDO::PARAM_STR);
        $STMT->execute();
        $PDO->commit();
    }

    public function isTokenValid(){
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT `uid`, `used` FROM password_reset WHERE ( `uid` = :token AND `belongsTo` = :id )');
        $STMT->bindParam(':token', $this->token, \PDO::PARAM_STR);
        $STMT->bindParam(':id', $this->id, \PDO::PARAM_INT);
        $STMT->execute();
        $result = $STMT->fetch(\PDO::FETCH_ASSOC);
        if(empty($result)){
            ErrorManager::addError(Language::NOT_VALID_TOKEN);
            return false;
        }
        $this->setUsed($result['used']);
        return true;
    }

    public function run(){
        if($this->isTokenValid() ){
            if($this->used == 0){
                if($this->checkPassword()){
                    $this->updatePassword();
                    $this->updateTokenUsed();
                    return true;
                }
                return false;
            }
            else{
                ErrorManager::addError(Language::TOKEN_USED);

                session_unset();
                session_destroy();
                
                #MAYBE ADD SOME DELAY HERE
                Helper::redirect('reset_password_email');
    
            }
        }
        return false;
    }
}
?>