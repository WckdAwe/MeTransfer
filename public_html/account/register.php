<?php
require_once('../../private_html/codebase.php');
use \codebase\App\Users\Account;
use \codebase\Templates\TemplateManager;


$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
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
    <div class="flex_box">
        <div class="center_box">
            <h2>Register</h2>

            <?php echo \codebase\App\ErrorManager::printErrors(); ?>
            <p>Please fill in the required details to register for an account.</p>
            <form action="" method="POST">
                <div>
                    <div>Username:</div>
                    <input type="text" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>" required>
                </div>
                <div>
                    <div>Email:</div>
                    <input type="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" required>
                </div>
                <div>
                    <div>Password:</div>
                    <input type="password" name="password" required>
                </div>
                <div>
                    <div>Retype your password:</div>
                    <input type="password" name="password_check" required>
                </div>
                <div>
                    <p>Do you by any chance agree with the TOS?</p>
                    <input type="checkbox" name="tos">
                    Yes mom. I agree. Can we finish now?
                </div>
                <div>
                    <input type="submit" name="submit" value="Register">
                </div>

                <p>Already have an account? Click here: <a href="login">Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>