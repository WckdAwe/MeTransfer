<?php
require_once('../../private_html/codebase.php');
use \codebase\App\Users\Account;
use \codebase\Templates\TemplateManager;


$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
$template->setPageTitle('My Account');
$template->setLoginRequired(true);
?>


<!DOCTYPE html>
<html lang="en">
    <?php echo $template->getHead(); ?>
    <body>
        <div class="flex_box">
            <div class="center_box">
                <h2>Hello <?php echo Account::user()->getUsername(); ?></h2>
                <input type="button" value="Upload file" onclick="document.location.href = '/'">
                <input type="button" value="Profile settings" onclick="document.location.href = '/account/edit'">
                <input type="button" value="Logout" onclick="document.location.href = '/account/logout'">
                <br><br><br>
                <input type="button" value="My Files" onclick="document.location.href = '/account/my_files'">
            </div>
        </div>
    </body>
</html>