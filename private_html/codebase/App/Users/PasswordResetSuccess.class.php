<?php
namespace codebase\App\Users;
use \codebase\App\ErrorManager;
use \codebase\App\Language;

class PasswordResetSuccess{
    private $token;
    private $user_id;
    private $new_password;
    private $check_new_password;

    public function __construct($token, $new_password, $check_new_password){
        $this->token = $token;
        $this->new_password = $new_password;
        $this->check_new_password = $check_new_password;
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
        $STMT = $PDO->prepare('UPDATE users SET `password`= :new_password WHERE (`id`= :user_id)');
        $STMT->bindParam(':user_id', $this->user_id, \PDO::PARAM_INT);
        $STMT->bindParam(':new_password', $this->new_password, \PDO::PARAM_STR);
        $STMT->execute();

        $STMT = $PDO->prepare('UPDATE password_reset SET `used` = :used WHERE (`uid` = :token and `used` = 0)');
        $STMT->bindValue(':used', 1, \PDO::PARAM_INT);
        $STMT->bindParam(':token', $this->token, \PDO::PARAM_STR);
        $STMT->execute();

        $PDO->commit();
    }

    public function isTokenValid() : bool
    {
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT `belongsTo`, `used` FROM password_reset WHERE (`uid` = :token) ORDER BY `created_at` DESC');
        $STMT->bindParam(':token', $this->token, \PDO::PARAM_STR);
        $STMT->execute();
        $result = $STMT->fetch(\PDO::FETCH_ASSOC);
        if(empty($result)){
            ErrorManager::addError(Language::ERR_INVALID, 'reset token');
            return false;
        }
        $this->user_id = $result['belongsTo'];
        return $result['used'] == 0 ? true : false;
    }

    public function run(){
        if($this->isTokenValid() ){
            if($this->checkPassword()){
                $this->updatePassword();
                return true;
            }
            return false;
        }
        return false;
    }
}
?>