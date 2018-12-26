<?php
require_once('../private_html/codebase.php');
use \codebase\Templates\TemplateManager;
use \codebase\Helper;
use \codebase\App\FileManager;
use \codebase\App\Users\Account;

if(!isset($_GET['file_uid'])) Helper::redirect('/');

$file = FileManager::getFileByUid($_GET['file_uid']);
if(!$file) Helper::redirect('/');
if($file->hasExpired()){
    if(file_exists($filepath)) $file->delete(); // Delete file if it has expired.
    Helper::msgRedirect(Helper::MSG_REDIRECT_FILE_EXPIRED);
}


if($file->getShareType() == FileManager::SHARE_TYPE_EMAIL){
    // TODO: Check if user is in the email list...
}
if(isset($_POST['download'])){
    $filepath = $file->getLocalPath();
    if(file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file->getFileName()).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $file->getFileSize());
        flush(); // Flush system output buffer
        readfile($filepath);
    }else{
        Helper::msgRedirect(Helper::MSG_REDIRECT_FILE_DOESNT_EXIST);
    }
}

$template = TemplateManager::getTemplate(TemplateManager::TMPL_METRANSFER);
$template->setPageTitle('Download file');
$template->setLoginRequired(true);
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
            <p class="transfer_icon"> <b> Download </b></p>
            <h2><?php echo $file->getFileName(); ?></h2>
            <p>Size: <?php echo Helper::formatBytes($file->getFileSize()); ?></p><br>
            <form action="" method="POST">
                <div class="button_pos">
                    <input type="submit" class="btn" name="download" value="Download">
                </div>
            </form>
        </div>
    </body>
</html>

