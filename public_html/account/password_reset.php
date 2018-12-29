<?php
require_once('../../private_html/codebase.php');
use \codebase\Templates\TemplateManager;
use \codebase\Helper;
use \codebase\App\Users\PasswordReset;
use \codebase\App\ErrorManager;

$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
$template->setPageTitle('Forgot password');
$template->setGuestRequired(true);
if(isset($_POST['submit'])){
    #Check if email is valid here:
    $email = $_POST['email'];
    $newPasswordReset = new PasswordReset($email);

    if($newPasswordReset->run()){
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
                <?phP echo ErrorManager::printErrors(); ?>
                <h2>Reset your password</h2>
                <form action="" method="POST">
                    <div>
                        <div>Email:</div>
                        <input type="email" name="email">
                    </div>
                    <div>
                        <input type="submit" name="submit" value="Reset">
                    </div>
                    <div>
                        <p>Had a brief moment of enlightenment and changed your mind?</p>
                        <p><a href="login">Login</a> to my account</p>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>



