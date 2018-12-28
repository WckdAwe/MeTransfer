<?php
require_once('../../private_html/codebase.php');
use \codebase\Templates\TemplateManager;
use codebase\App\Language;
use codebase\App\ErrorManager;
use codebase\Helper;
use codebase\App\PasswordResetSuccess;

$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
$template->setPageTitle('Reset password');
$template->setGuestRequired(true);
if(isset($_POST['submit'])){
    $id = (int)$_SESSION['id'];
    $email = $_POST['email'];
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $check_new_password = $_POST['check_new_password'];

    $newPasswordSucces = new PasswordResetSuccess($email, $token, $new_password, $check_new_password, $id);
    if($newPasswordSucces->run()){
        session_unset();
        session_destroy();

        

        Helper::redirect('login');
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
