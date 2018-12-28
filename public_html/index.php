<?php
require_once('../private_html/codebase.php');

use \codebase\Templates\TemplateManager;
use \codebase\App\FileManager;
use \codebase\App\Users\Account;

if(isset($_POST['upload'])){
    $receivers = [];
    if($_POST['share_type'] == 'email')
        $receivers = $_POST['receivers'];
    FileManager::uploadFile($_FILES['file'], $_POST['delete_at'], $_POST['sender'], $receivers, $_POST['message'], $_POST['password']);
}

$template = TemplateManager::getTemplate(TemplateManager::TMPL_METRANSFER);
$template->setPageTitle('Home');

$isLoggedIn = Account::isLoggedIn();
if($isLoggedIn) $user = Account::user();
$senderHTML = $isLoggedIn ? 'value="'.$user->getEmail().'" readonly' : 'value="'.(isset($_POST['sender']) ? $_POST['sender'] : '').'"';
?>

<!DOCTYPE html>
<html lang="en">
    <?php echo $template->getHead(); ?>

    <body class="grad">
        <div class="logo">
            <p class="logo_text"><a href="/"> MeTransfer </a></p>
        </div>
        <?php echo $template->getUserMenu(); ?>
        <br>
        <div class="transfer_window">
            <p class="transfer_icon"> <b> Send Files </b></p> <br>
            <?php echo \codebase\App\ErrorManager::printErrors(); ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div id="mail_components">
                    <input type="text" name="receivers" placeholder="email1@test.com, email2@test.com ..." value="<?php echo isset($_POST['receivers']) ? $_POST['receivers'] : ''; ?>">
                    <input type="email" name="sender" placeholder="Your email" <?php echo $senderHTML; ?> > <br><br>
                    <textarea name="message" rows="auto" cols="auto" placeholder="Hey there bud! This is a file for you."><?php echo isset($_POST['message']) ? $_POST['message'] : ''; ?></textarea> <br>
                </div>
                <label> Send as:
                    <input id="share_type_email" onchange="checkShareType();" type="radio" name="share_type" value="email" <?php echo (!isset($_POST['share_type']) || $_POST['share_type']=='email') ? 'checked="checked"' : '';?>>
                </label> Email
                <label>
                    <input id="share_type_link" onchange="checkShareType();" type="radio" name="share_type" value="link" <?php echo (isset($_POST['share_type']) && $_POST['share_type']=='link') ? 'checked="checked"' : '';?>>
                </label> Link <br><br>
                <label> Delete after:
                    <select name="delete_at">
                        <option value="1"> 1 week </option>
                        <option value="2"> 2 weeks </option>
                        <option value="3"> 3 weeks </option>
                        <option value="4"> 1 month </option>
                    </select>
                </label><br><br>
                <label>
                    Password (Empty for none):
                    <input type="password" name="password">
                </label><br><br>
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
    <script>
        checkShareType();
        function checkShareType(){
            document.getElementById('mail_components').style.display = (!document.getElementById('share_type_email').checked ? 'none' : 'inherit');
        }
    </script>
</html>