<?php
require_once('../../private_html/codebase.php');
use \codebase\App\Account;
use \codebase\Templates\TemplateManager;


$template = TemplateManager::getTemplate();
$template->setPageTitle('Login');
if(isset($_POST['submit'])){
    $_SESSION['name'] = $_POST['username'];
    if(!Account::getInstance()->tryLogin($_POST['username'], $_POST['password'])){
        echo 'ERROR ON LOGIN... Will add specific error later...';
    }
}

// TODO: Redirect if logged in already!
if(Account::getInstance()){
    $_SESSION['name'] = $_POST['username'];
    header("Refresh:0;url=profile.php");
}

session_unset();
session_destroy();
?>



<!DOCTYPE html>
<html lang="en">

    <?php echo $template->getHead(); ?>

    <body>
        <div class="login_class">
            <h2>Login</h2>
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
                <input type="submit" name="submit" value="login" id="login_button">
                <br>
                if you don't have an account click here: <a href="register.php">register </a>
                <br>
                <a href="../any_name.php">forgot your password?</a>
            </form>
        </div>
    </body>
</html>