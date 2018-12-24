<?php
require_once('../../private_html/codebase.php');
use \codebase\App\Users\Account;
use \codebase\Templates\TemplateManager;


$template = TemplateManager::getTemplate();
$template->setPageTitle('My Account');
$template->setLoginRequired(true);
$template->addCSS('\assets\css\profile_style.css', true);
?>


<!DOCTYPE html>
<html lang="en">
    <?php echo $template->getHead(); ?>
    <body>
        <div>
            <h2>Hello <?php echo Account::user()->username; ?></h2>
            <input type="button" value="Upload file" class="button_style">
            <input type="button" value="Profile settings" class="button_style">
            <input type="button" value="logout" class="button_style" onclick="document.location.href = '/account/logout'">
        </div>
    </body>
</html>