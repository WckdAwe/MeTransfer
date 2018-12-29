<?php
require_once('../../private_html/codebase.php');
use \codebase\Templates\TemplateManager;
use \codebase\Helper;
use \codebase\App\Users\PasswordResetSuccess;
use \codebase\App\ErrorManager;

$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
$template->setPageTitle('Reset password');
$template->setGuestRequired(true);

if(isset($_POST['submit'])){
    $newPasswordSuccess = new PasswordResetSuccess($_POST['token'], $_POST['new_password'], $_POST['check_new_password']);

    if($newPasswordSuccess->run())
        Helper::redirect('login');
}
?>

<!DOCTYPE html>
<html lang="en">
    <?php echo $template->getHead(); ?>
    <body>
        <div class="flex_box">
            <div class="center_box">
                <h2>Set new password</h2>
                <?php echo ErrorManager::printErrors(); ?>
                <form action="<?php if(isset($_GET['token'])) echo '?&token='.$_GET['token'];?>" method="POST">
                    <?php if(isset($_GET['token'])): ?>
                        <input type="hidden" name="token" value="<?php if(isset($_GET['token'])) echo $_GET['token']; ?>" required>
                    <?php else: ?>
                        <div>
                            <div>Token:</div>
                            <input type="textarea" name="token" required>
                        </div>
                    <?php endif; ?>

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
    </body>
</html>
