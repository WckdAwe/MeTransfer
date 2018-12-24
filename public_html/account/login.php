<?php
require_once('../../private_html/codebase.php');
use \codebase\App\Users\Account;
use \codebase\Templates\TemplateManager;


$template = TemplateManager::getTemplate(TemplateManager::TMPL_LOGIN_REGISTER);
$template->setPageTitle('Login');
$template->setGuestRequired(true);
if(isset($_POST['submit']))
    Account::getInstance()->tryLogin($_POST['username'], $_POST['password']);
?>



<!DOCTYPE html>
<html lang="en">

    <?php echo $template->getHead(); ?>

    <body>
        <div class="login_class">
            <h2>Login</h2>

            <?php echo \codebase\App\ErrorManager::printErrors(); ?>
            <br>
            Please fill your credentials to login.
            <br>
            <br>
            <form action="" method="POST">
                Username:<br>
                <input type="text" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>" required>
                <br>
                Password:<br>
                <input type="password" name="password" required>
                <br>
                <input type="submit" name="submit" value="Login">
                <br>
                If you don't have an account click here: <a href="../register">Register</a>
                <br>
                <a href="../password_reset">Forgot your password?</a>
            </form>
        </div>
    </body>
</html>