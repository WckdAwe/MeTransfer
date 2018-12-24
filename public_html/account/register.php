<?php
require_once('../../private_html/codebase.php');
use \codebase\App\Users\Account;
use \codebase\Templates\TemplateManager;


$template = TemplateManager::getTemplate(TemplateManager::TMPL_LOGIN_REGISTER);
$template->setPageTitle('Register');
$template->setGuestRequired(true);
if(isset($_POST['submit'])) {
    $tos_accept = isset($_POST['tos']) ? $_POST['tos'] : null;
    Account::getInstance()->trySignup($_POST['username'], $_POST['email'],
                                      $_POST['password'], $_POST['password_check'],
                                      $tos_accept);
    }
?>



<!DOCTYPE html>
<html lang="en">

<?php echo $template->getHead(); ?>

<body>
<div class="login_class">
    <h2>Register</h2>

    <?php echo \codebase\App\ErrorManager::printErrors(); ?>
    <br>
    Please fill in the required details to register for an account.
    <br>
    <br>
    <form action="" method="POST">
        Username:<br>
        <input type="text" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>" required>
        <br>
        Email:<br>
        <input type="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" required>
        <br>
        Password:<br>
        <input type="password" name="password" required>
        <br>
        Re-type your password:<br>
        <input type="password" name="password_check" required>
        <br>

        Do you by any chance agree with the TOS?<br>
        <input type="checkbox" name="tos">
        <br>

        <input type="submit" name="submit" value="Register">
        <br>
        Already have an account? Click here: <a href="../login">Login</a>
    </form>
</div>
</body>
</html>