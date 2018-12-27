<?php
require_once('../private_html/codebase.php');
use \codebase\Templates\TemplateManager;

use \codebase\App\FileManager;
use \codebase\App\Users\Account;

if(isset($_POST['upload'])){
    $incld_message = isset($_POST['message']) ? $_POST['message'] : null;
    if($_POST['share_type'] == 'email'){
        $receivers = $_POST['receivers'];
    }else{
        $receivers = [];
    }
    FileManager::uploadFile($_FILES['file'], $_POST['delete_at'], $_POST['sender'], $receivers, $incld_message, null);
}

$template = TemplateManager::getTemplate(TemplateManager::TMPL_METRANSFER);
$template->setPageTitle('Home');
$isLoggedIn = Account::isLoggedIn();
if($isLoggedIn) $user = Account::user();
?>

<!DOCTYPE html>
<html lang="en">
    <?php echo $template->getHead(); ?>
    <body class="grad">
        <div class="logo">
            <p class="logo_text"><a href="/"> MeTransfer </a></p>
        </div>
        <div class="login_subscribe_button">
            <?php if(!$isLoggedIn){
                echo '<div>
                          <a href="/account/login"> LOGIN </a>
                      </div>
                      <div>
                          <a href="/account/register"> REGISTER </a>
                      </div>';
            }else{
                echo 'Hello <b>'.$user->getUsername().'</b> <br>';
                echo '<div>
                          <a href="/account"> MY PROFILE </a>
                      </div>
                      <div>
                          <a href="/account/logout"> LOGOUT </a>
                      </div>';
            }?>

        </div><br>
        <div class="transfer_window">
            <p class="transfer_icon"> <b> Send Files </b></p> <br>
            <?php echo \codebase\App\ErrorManager::printErrors(); ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="text" name="receivers" placeholder="email1@test.com, email2@test.com ..." value="<?php echo isset($_POST['receivers']) ? $_POST['receivers'] : ''; ?>">
                <input type="email" name="sender" placeholder="Your email" value="<?php echo $isLoggedIn ? $user->getEmail() : ''; ?>"> <br><br>
                <textarea name="message" rows="auto" cols="auto" placeholder="Hey there bud! This is a file for you."><?php echo isset($_POST['message']) ? $_POST['message'] : ''; ?></textarea> <br>
                <label> Send as:
                    <input type="radio" name="share_type" value="email" <?php echo (!isset($_POST['share_type']) || $_POST['share_type']=='email') ? 'checked="checked"' : '';?>>
                </label> Email
                <label>
                    <input type="radio" name="share_type" value="link" <?php echo (isset($_POST['share_type']) && $_POST['share_type']=='link') ? 'checked="checked"' : '';?>>
                </label> Link <br><br>
                <label> Delete after:
                    <select name="delete_at">
                        <option value="1"> 1 week </option>
                        <option value="2"> 2 weeks </option>
                        <option value="3"> 3 weeks </option>
                        <option value="4"> 1 month </option>
                    </select>
                </label>
                <input type="file" name="file" />
                <br><br>
                <input type="submit" name="upload" value="Transfer"> <br>
                <p><strong>Tip:</strong>
                    For multiple receiver emails just separate them with comma.
                </p>

                <!-- placeholder "set password" <br>
                TO DO LATER: dropdown menu "advanced options": "del after", "shorten url", "protect with pass". -->
            </form>
        </div>
    </body>
</html>