<?php
require_once('../private_html/codebase.php');
use \codebase\Templates\TemplateManager;

$template = TemplateManager::getTemplate(TemplateManager::TMPL_METRANSFER);
$template->setPageTitle('Oh oh');
?>

<!DOCTYPE html>
<html lang="en">
    <?php echo $template->getHead(); ?>
    <body class="grad">
        <div class="logo">
            <p class="logo_text"> MeTransfer </p>
        </div>
        <div class="login_subscribe_button">
            <a href="/account/login"> LOGIN </a> <br>
            <a href="/account/register"> REGISTER </a>
        </div><br>
        <div class="transfer_window">
            <p class="transfer_icon"> <b> ERROR </b></p> <br>
            <p>Something went so terribly bad. We are so sorry...</p>
        </div>
    </body>
</html>