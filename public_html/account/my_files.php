<?php
require_once('../../private_html/codebase.php');
use \codebase\App\Users\Account;
use \codebase\Templates\TemplateManager;
use codebase\App\Users\UserFile;
use \codebase\App\FileManager;

$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
$template->setPageTitle('My Files');
$template->setLoginRequired(true);

if(isset($_GET['action']) && isset($_GET['id'])){
    if($_GET['action']=='del'){
        $file = FileManager::getFileById($_GET['id']);
        if($file) $file->delete();
    }
}

$user = Account::user();
$files = $user->files();
?>


<!DOCTYPE html>
<html lang="en">
    <?php echo $template->getHead(); ?>
    <body>
        <div class="flex_box">
            <div class="center_box">
                <?php echo \codebase\App\ErrorManager::printErrors(); ?>

                <?php if(empty($files)) : ?>
                    You currently have no files uploaded. </br> Would you like to:<br>
                    <a href="/">Upload</a> a new file or Go back to <a href="/account">My Account</a>
                <?php else: ?>
                    These are the files you currently have uploaded. Would you like to:<br>
                    <a href="/">Upload</a> a new file or Go back to <a href="/account">My Account</a>
                    <table>
                        <tr>
                            <th>File ID</th>
                            <th>File Name</th>
                            <th>Active?</th>
                            <th>Expiration Date</th>
                            <th>Access</th>
                            <th>Actions</th>
                        </tr>
                        <?php
                            foreach ($files as $file){
                                if($file instanceof UserFile) {
                                    echo '<th>' . $file->getId() . '</th>' .
                                        '<th>' . $file->getFileName() . '</th>' .
                                        '<th>' . ($file->hasExpired() ? 'No' : 'Yes') . '</th>' .
                                        '<th>' . $file->getDeleteAt() . '</th>' .
                                        '<th>' . $file->getShareType() . '</th>' .
                                        '<th><a href="'.$file->getUrl().'">GO</a> || <a href="?action=del&id='.$file->getId().'">Delete</a></th>';
                                }
                            }
                        ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>