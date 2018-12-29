<?php
require_once('../../private_html/codebase.php');
use \codebase\App\Users\Account;
use \codebase\Templates\TemplateManager;
use \codebase\App\Users\UserFile;
use \codebase\App\FileManager;

$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
$template->setPageTitle('Admin Page');
$template->setAdminRequired(true);

if(isset($_GET['action']) && isset($_GET['id'])){
    if($_GET['action']=='del'){
        $file = FileManager::getFileById($_GET['id']);
        if($file && $file->delete()) \codebase\Helper::redirect('/admin');
    }
}

$user = Account::user();
$files = FileManager::getAllFiles();
?>


<!DOCTYPE html>
<html lang="en">
    <?php echo $template->getHead(); ?>
    <body>
        <div class="flex_box">
            <div class="center_box">
                <?php echo \codebase\App\ErrorManager::printErrors(); ?>
                <h2>Admin File Manager</h2>
                <?php if(empty($files)) : ?>
                    <p>Apparently this site sucks cause no one has yet uploaded anything.</p>
                <?php else: ?>
                    <p>As an administrator you may <b>delete</b> any file in this database.</p>
                    <p>You <b>can't</b> access any <b>password protected</b> or <b>private email</b> file though. We loyal brah.</p>
                    <p><small>At least from the file manager directly (͠≖͜ʖ͠≖) 👌 .</small></p>
                    <div>
                        Total uploaded files: <?php echo sizeof($files); ?>
                        <p>I am done. <a href="/">Take me home</a>, country roads.</p>
                    </div>
                    <table>
                        <tr>
                            <th>File ID</th>
                            <th>File Name</th>
                            <th>Access</th>
                            <th>Password Prot.</th>
                            <th>Active?</th>
                            <th>Expiration Date</th>
                            <th>Owner</th>
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
                                        '<th>' . $file->getDeleteAt() . '</th>' .
                                        '<th>' . $file->getOwnerUsername() . '</th>';
                                    if($file->hasExpired()){
                                        echo '<th>¯\_(ツ)_/¯</th>';
                                    }else{
                                        $goURL = ($file->getShareType() != FileManager::SHARE_TYPE_LINK && $file->getBelongsTo() != $user->getId() && !in_array($user->getEmail(), $file->getAccessEmails())) ? 'GO' : '<a href="'.$file->getUrl().'">GO</a>';
                                        echo '<th>'.$goURL.' || <a href="?action=del&id='.$file->getId().'">Delete</a></th>';
                                    }
                                    echo '</tr>';
                                }
                            }
                        ?>
                    </table>
                <?php endif; ?>
                <p>Sweet <a href="/">Home</a> Alabama</p>
            </div>
        </div>
    </body>
</html>