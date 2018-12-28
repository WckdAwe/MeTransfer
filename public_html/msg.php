<?php
require_once('../private_html/codebase.php');
use \codebase\Templates\TemplateManager;
use \codebase\App\Users\Account;
use \codebase\Helper;

$template = TemplateManager::getTemplate(TemplateManager::TMPL_METRANSFER);
$title = 'ERROR';
$msg = '<p>Something went so terribly bad. We are so sorry...</p>';
if(isset($_GET['type'])){
    switch($_GET['type']){
        case Helper::MSG_REDIRECT_DOWNLOAD_SUCCESS:
            $title = 'SUCCESS';
            $msg = '<p>Your file must be downloading right now.</p><p>Cheeriosss.</p>';
            break;
        case Helper::MSG_REDIRECT_FILE_EXPIRED:
            $title = 'FILE EXPIRED';
            $msg = '<p>Unfortunately this file is dead.</p><p>It is no longer with us. Capiche??</p><p>Richardo! Cleanup on aisle 4!</p>';
            break;
        case Helper::MSG_REDIRECT_FILE_DOESNT_EXIST:
            $title = 'FILE DOESN\'T EXIST';
            $msg = '<p>Can\'t find this file... Something went bad. One of our devs screwed up.</p><p><small>It was probably Wicke&#272;.</small></p><p><small>He sucks</small></p>';
            break;
    }
    $template->setPageTitle(ucfirst($title));
}
$isLoggedIn = Account::isLoggedIn();
if($isLoggedIn) $user = Account::user();
?>

<!DOCTYPE html>
<html lang="en">
    <?php echo $template->getHead(); ?>
    <body class="grad">
        <div class="logo">
            <p class="logo_text"> MeTransfer </p>
        </div>
        <?php echo $template->getUserMenu(); ?>
        <div class="transfer_window">
            <p class="transfer_icon"> <b> <?php echo $title; ?> </b></p> <br>
            <?php echo $msg; ?>
        </div>
    </body>
</html>