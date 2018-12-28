<?php
require_once('../../private_html/codebase.php');
use \codebase\Templates\TemplateManager;
use codebase\App\Language;
use codebase\App\ErrorManager;
use codebase\Helper;

$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
$template->setPageTitle('Reset password');
$template->setGuestRequired(true);
if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $token = $_POST['token'];
    $PDO = \codebase\Databases\PHPDataObjects::getInstance();
    $STMT = $PDO->prepare('SELECT `uid`, `used` FROM password_reset WHERE (`uid` = :token)');
    $STMT->bindParam(':token', $token, \PDO::PARAM_STR);
    $STMT->execute();

    $result = $STMT->fetch(\PDO::FETCH_ASSOC);
    if(empty($result)){
        ErrorManager::addError(Language::NOT_VALID_TOKEN);
    }
    else{
        #check if used == false (0) 
        if($result['used'] == 0){
            $new_password = $_POST['new_password'];
            $check_new_password = $_POST['check_new_password'];
            if(strlen($new_password) < 5 || strlen($new_password) > 32){
                ErrorManager::addError(Language::ERR_LENGTH_BETWEEN, 'password', 5, 32);
            }

            if($new_password != $check_new_password){
                ErrorManager::addError(Language::ERR_ITEM_NOT_SAME, 'password', 'password check');
            }
            if(!ErrorManager::hasErrors()){

                #update db:
                $PDO->beginTransaction();
                $new_password = password_hash($new_password, PASSWORD_BCRYPT);
                $STMT = $PDO->prepare('UPDATE users SET `password`= :new_password WHERE (`email`= :email)');
                $STMT->bindParam(':email', $email, \PDO::PARAM_STR);
                $STMT->bindParam(':new_password', $new_password, \PDO::PARAM_STR);
                $STMT->execute();
                $PDO->commit();
                

                
                $PDO->beginTransaction();
                $used = 1;
                $STMT = $PDO->prepare('UPDATE password_reset SET `used` = :used WHERE (`uid` = :token)');
                $STMT->bindParam(':used', $used, \PDO::PARAM_INT);
                $STMT->bindParam(':token', $token, \PDO::PARAM_STR);
                $STMT->execute();
                $PDO->commit();

             
                
                
                session_unset();
                session_destroy();

                Helper::redirect('login');
            }

    
        }
        else{

            ErrorManager::addError(Language::TOKEN_USED);

            session_unset();
            session_destroy();
            
          
            Helper::redirect('reset_password_email');
        }
        
        

    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<?php echo $template->getHead(); ?>
<body>
    <div>
        <form action="" method="POST">
            <div class="flex_box">
                <div class="center_box">
                <h2>Set new password</h2>
                <!--Error MSG here-->
                <?php echo \codebase\App\ErrorManager::printErrors(); ?>
                    <form action="" method="POST">
                    <div>
                        <div>Email:</div>
                        <input type="email" name="email" value="<?php if(isset($_SESSION['email'])) echo $_SESSION['email']; ?>" required>
                    </div>
                    <div>
                        <div>Token:</div>
                        <input type="textarea" name="token" required> 
                    </div>
                    <div>
                        <div>New password:</div>
                        <input type="password" name="new_password" required> 
                    </div>
                    <div>
                        <div>Confirm new password:</div>
                        <input type="password" name="check_new_password" required> 
                    </div>
                    <div>
                        <input type="submit" name="submit" value="Submit">
                    </div>
                    </form>
                </div>
            </div>
    </div>
</body>
</html>
