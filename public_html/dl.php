<?php
require_once('../private_html/codebase.php');
use \codebase\Templates\TemplateManager;
use \codebase\Helper;
use \codebase\App\FileManager;
use \codebase\App\Users\Account;
use \codebase\App\ErrorManager;
use \codebase\App\Language;

if(!isset($_GET['file_uid'])) Helper::redirect('/');

$file = FileManager::getFileByUid($_GET['file_uid']);
if(!$file) Helper::redirect('/');
if($file->hasExpired()){
    if(file_exists($filepath)) $file->delete(); // Delete file if it has expired.
    Helper::msgRedirect(Helper::MSG_REDIRECT_FILE_EXPIRED);
}

$isLoggedIn = Account::isLoggedIn();
if($isLoggedIn) $user = Account::user();

if($isLoggedIn && ($file->getBelongsTo() == $user->getId())){ // TODO: Or isAdmin() (But loyalty?);
    // continue normally;
}else if($file->getShareType() == FileManager::SHARE_TYPE_EMAIL){
    $access_emails = $file->getAccessEmails();
    if(!isset($_GET['email']) || !in_array($_GET['email'], $access_emails) || ($isLoggedIn && !in_array($user->getEmail(), $access_emails))){
        Helper::redirect('/');
    }
}

$template = TemplateManager::getTemplate(TemplateManager::TMPL_METRANSFER);
$template->setPageTitle('Download file');

if(isset($_POST['download'])){
    $filepath = $file->getLocalPath();
    if($file->hasPassword() && !password_verify($_POST['password'], $file->getPassword())){
        ErrorManager::addError(Language::ERR_INCORRECT, 'password');
    }else if(file_exists($filepath)) {
        header('Set-Cookie: fileLoading=true'); // TODO: Alternative way?
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

?>

<!DOCTYPE html>
<html lang="en">
    <?php echo $template->getHead(); ?>
    <body class="grad">
        <?php echo $template->getUserMenu(); ?>
        <br>
        <div class="transfer_window">
            <p class="transfer_icon"> <b> Download </b></p>
            <?php echo ErrorManager::printErrors(); ?>
            <h2><?php echo $file->getFileName(); ?></h2>
            <p>Size: <?php echo Helper::formatBytes($file->getFileSize()); ?></p><br>
            <form action="" method="POST">
                <?php if($file->hasPassword()): ?>
                    <div>
                        <p>This file is password protected: </p>
                        <input id="pwd" type="password" name="password" placeholder="Password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>">
                        <br><br>
                    </div>
                <?php endif; ?>
                <div class="button_pos">
                    <input id="dl_btn" type="submit" class="btn" name="download" value="Download">
                </div>
            </form>
        </div>

        <script>
            var pwd_field = document.getElementById('pwd');
            var dl_btn = document.getElementById('dl_btn');
            if(pwd_field){
                dl_btn.disabled = pwd_field.value.length <= 0;
                pwd_field.onchange = function(){ dl_btn.disabled = (pwd_field.value.length <= 0); }
                pwd_field.onkeyup = function(){ dl_btn.disabled = (pwd_field.value.length <= 0); }
                pwd_field.onkeydown = function(){ dl_btn.disabled = (pwd_field.value.length <= 0); }
                pwd_field.onpaste = function(){ dl_btn.disabled = (pwd_field.value.length <= 0); }
            }

            setInterval(function(){
                if (getCookie('fileLoading')) {
                    deleteCookie('fileLoading');

                    location.href = '/msg.php?type=download_success';
                }
            }, 1000);

            function getCookie(name) {
                var dc = document.cookie;
                var prefix = name + "=";
                var begin = dc.indexOf("; " + prefix);
                if (begin == -1) {
                    begin = dc.indexOf(prefix);
                    if (begin != 0) return null;
                }
                else
                {
                    begin += 2;
                    var end = document.cookie.indexOf(";", begin);
                    if (end == -1) {
                        end = dc.length;
                    }
                }
                // because unescape has been deprecated, replaced with decodeURI
                //return unescape(dc.substring(begin + prefix.length, end));
                return decodeURI(dc.substring(begin + prefix.length, end));
            }

            function deleteCookie(name) {
                document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            };
        </script>
    </body>
</html>

