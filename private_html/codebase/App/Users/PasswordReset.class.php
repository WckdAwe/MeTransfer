<?php
namespace codebase\App\Users;
use codebase\App\Language;
use codebase\App\ErrorManager;
use codebase\Emails\PasswordResetEmail;
use codebase\Helper;


class PasswordReset{
    private $user_id;
    private $email;
    private $token;
    private $used;

    public function __construct($email){
        $this->email = $email;
    }

    private function checkEmail(){
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT `id` FROM users WHERE (`email` = :email)');
        $STMT->bindParam(':email', $this->email, \PDO::PARAM_STR);
        $STMT->execute(); 
        $result = $STMT->fetch(\PDO::FETCH_ASSOC);

        if(empty($result)){
            return false;
        }
        $this->user_id = $result['id'];
        return true;
    }

    private function updateDatabase(){
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $PDO->beginTransaction();
        $STMT = $PDO->prepare('INSERT INTO password_reset values(DEFAULT, :token, 0, :belongsTo, DEFAULT)');
        $STMT->bindParam(':token', $this->token, \PDO::PARAM_STR);
        $STMT->bindParam(':belongsTo', $this->user_id, \PDO::PARAM_INT);
        $STMT->execute();
        $PDO->commit();
    }


    private function checkAwaitingReset() : bool
    {
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $PDO->beginTransaction();

        $STMT = $PDO->prepare('SELECT `used` FROM password_reset WHERE (`belongsTo` = :user_id) ORDER BY `created_at` DESC');
        $STMT->bindParam(':user_id', $this->user_id, \PDO::PARAM_INT);
        $STMT->execute();
        $result = $STMT->fetch(\PDO::FETCH_ASSOC);
        $PDO->commit();

        return $result['used'] == '0';
    }

    private function generateUniqueDbUid(){
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $PDO->beginTransaction();

        do {
            $this->token = Helper::generateUID();
            $STMT = $PDO->prepare('SELECT `used` FROM password_reset WHERE (`uid` = :token) ORDER BY `created_at` DESC');
            $STMT->bindParam(':token', $this->token, \PDO::PARAM_STR);
            $STMT->execute();

            if(!($STMT->rowCount() && $STMT->fetch(\PDO::FETCH_ASSOC)['used'] == '0')){
                break;
            }
        } while(true);

        $PDO->commit();
    }

    public function run(){
        if(!$this->checkEmail()){
            ErrorManager::addError(Language::ERR_DB_ITEM_NO_EXIST, 'email');
            return false;
        }

        if($this->checkAwaitingReset()){
            ErrorManager::addError(Language::ERR_RESET_TOKEN_ALREADY_GIVEN);
            return false;
        }

        $this->generateUniqueDbUid();
        $this->updateDatabase();

        $email = new PasswordResetEmail($this->token);
        $email->setReceivers($this->email);
        $email->sendEmail();

        return true;
    }
}
?>