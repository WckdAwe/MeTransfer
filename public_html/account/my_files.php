<?php
require_once('../../private_html/codebase.php');
use \codebase\App\Users\Account;
use \codebase\Templates\TemplateManager;
use \codebase\App\Users\UserFile;
use \codebase\App\FileManager;

$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
$template->setPageTitle('My Files');
$template->setLoginRequired(true);

if(isset($_GET['action']) && isset($_GET['id'])){
    if($_GET['action']=='del'){
        $file = FileManager::getFileById($_GET['id']);
        if($file && $file->delete()) \codebase\Helper::redirect('/account/my_files');
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
                            <th>Access</th>
                            <th>Password Prot.</th>
                            <th>Active?</th>
                            <th>Expiration Date</th>
                            <th>Actions</th>
                        </tr>
                        <?php
                            foreach ($files as $file){
                                if($file instanceof UserFile) {
                                    $shareTypeHTML = $file->getShareTypeAsText();
                                    if($file->getShareType() == FileManager::SHARE_TYPE_EMAIL){
                                        $shareTypeHTML .= ' Access:<br><ul>';
                                        foreach ($file->getAccessEmails() as $accessEmail) {
                                            $shareTypeHTML .= '<ol>'.$accessEmail.'</ol>';
                                        }
                                        $shareTypeHTML .= '</ul>';
                                    }
                                    echo '<tr><th>' . $file->getId() . '</th>' .
                                        '<th>' . $file->getFileName() . '</th>' .
                                        '<th>' . $shareTypeHTML  . '</th>' .
                                        '<th>' . ($file->getPassword() ? 'Yes' : 'No') . '</th>' .
                                        '<th>' . ($file->hasExpired() ? 'No' : 'Yes') . '</th>' .
                                        '<th>' . $file->getDeleteAt() . '</th>';
                                    if($file->hasExpired()){
                                        echo '<th>¯\_(ツ)_/¯</th>';
                                    }else{
                                        echo '<th><a href="'.$file->getUrl().'">GO</a> || <a href="?action=del&id='.$file->getId().'">Delete</a></th>';
                                    }
                                    echo '</tr>';
                                }
                            }
                        ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>