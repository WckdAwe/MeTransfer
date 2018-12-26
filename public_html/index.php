<?php
require_once('../private_html/codebase.php');
use \codebase\Templates\TemplateManager;

use \codebase\App\FileManager;
use \codebase\App\Users\Account;

if(isset($_POST['upload'])){
    if($_POST['share_type'] == 'email'){
        $receivers = ['vasil711@hotmail.com','test2@hotmail.com'];
    }else{
        $receivers = [];
    }
    FileManager::uploadFile($_FILES['file'], $_POST['delete_at'], $receivers, null);
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
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="email" name="receiver" placeholder="Email to">
                <input type="email" name="sender" placeholder="Your email" value="<?php echo $isLoggedIn ? $user->getEmail() : ''; ?>">
                <textarea name="message" rows="auto" cols="auto"> Message </textarea> <br>
                <label> Send as:
                    <input type="radio" checked="checked" name="share_type" value="email">
                </label> Email
                <label>
                    <input type="radio" name="share_type" value="link">
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
                <input type="submit" name="upload" value="Transfer">


                <!-- placeholder "set password" <br>
                TO DO LATER: dropdown menu "advanced options": "del after", "shorten url", "protect with pass". -->
            </form>
        </div>
    </body>
</html>