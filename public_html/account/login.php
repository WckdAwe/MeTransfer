<?php
require_once('../../private_html/codebase.php');
use \codebase\App\Users\Account;
use \codebase\Templates\TemplateManager;


$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
$template->setPageTitle('Login');
$template->setGuestRequired(true);
if(isset($_POST['submit']))
    Account::getInstance()->tryLogin($_POST['username'], $_POST['password']);
?>



<!DOCTYPE html>
<html lang="en">

    <?php echo $template->getHead(); ?>

    <body>
        <div class="flex_box">
            <div class="center_box">
                <h2>Login</h2>

                <?php echo \codebase\App\ErrorManager::printErrors(); ?>
                <p>Please fill your credentials to login.</p>

                <form action="" method="POST">
                    <div>
                        <div>Username:</div>
                        <input type="text" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>" required>
                    </div>
                    <div>
                        <div>Password:</div>
                        <input type="password" name="password" required>
                    </div>
                    <div>
                        <input type="submit" name="submit" value="Login">
                    </div>
                    <div>
                        <p>If you don't have an account click here: <a href="register">Register</a></p>
                        <p><a href="reset_password_email">Forgot your password?</a></p>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>