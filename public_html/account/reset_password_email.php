<?php
require_once('../../private_html/codebase.php');
use \codebase\Templates\TemplateManager;
use codebase\App\Language;
use codebase\App\ErrorManager;
use codebase\Helper;
use codebase\Emails\ContactManager;

$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
$template->setPageTitle('Forgot password');
$template->setGuestRequired(true);
if(isset($_POST['submit'])){
    #Check if email is valid here:
    $email = $_POST['email'];
    $username = $_POST['username'];
    $PDO = \codebase\Databases\PHPDataObjects::getInstance();
    $STMT = $PDO->prepare('SELECT `id` FROM users WHERE (`email` = :email)');
    $STMT->bindParam(':email', $email, \PDO::PARAM_STR);
    $STMT->execute();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               

    echo("statement executed");
    $result = $STMT->fetch(\PDO::FETCH_ASSOC);
    if(empty($result)){
        #Error inform the user
        ErrorManager::addError(Language::ERR_DB_ITEM_NO_EXIST, 'email');
    }
    else{
            #generating token
            $token = Helper::generateUID();
            $used = 0;
            $id = $result['id'];
            $MSG = 'To reset your password you need to use this : '.$token;
            #send email 
            ContactManager::sendMail($email, $MSG);
            #add information to database
            $PDO->beginTransaction();
            $STMT = $PDO->prepare('INSERT INTO password_reset values(DEFAULT, :token, :used, :belongsTo, DEFAULT)');
            $STMT->bindParam(':token', $token, \PDO::PARAM_STR);
            $STMT->bindParam(':used', $used, \PDO::PARAM_INT);
            $STMT->bindParam(':belongsTo', $id, \PDO::PARAM_INT);
            $STMT->execute();
    
            $PDO->commit();
    
            $_SESSION['email'] = $email;
            
            Helper::redirect('password_reset_success');  
        }

    }




?>

<!DOCTYPE html>
<html lang="en">
<?php echo $template->getHead(); ?>
<body>
    <div class="flex_box">
        <div class="center_box">
            <?phP echo \codebase\App\ErrorManager::printErrors(); ?>
            <h2>Reset your password</h2>
            <form action="" method="POST">
                <div>
                    <div>Email:</div>
                    <input type="email" name="email">
                </div>
                <div>
                    <input type="submit" name="submit" value="Reset">
                </div>
            </form>
        </div>
    </div>
</body>
</html>



