<?php
require_once('../../private_html/codebase.php');
use \codebase\Templates\TemplateManager;


$template = TemplateManager::getTemplate();
$template->setPageTitle('Edit profile info');
$template->setLoginRequired(true);
$template->addCSS('\assets\css\profile_style.css', true);
?>

<!DOCTYPE html>
<html lang="en">
<?php echo $template->getHead(); ?>
<body>
    <div class='edit_username'>
        name : 
        <input type='button' name='edit_name_button' value='edit'>
        <br>
    </div>
    <div class='edit_password'>
        password :
        <input type='button' name='edit_password_button' value='edit'>
        <br>
    </div>
    <div class='edit_email'>
        email : 
        <input type='button' name='edit_email_button' value='edit'>
        <br>
    </div>
    <input type='button' name='back_button' value='back'>

</body>
</html>