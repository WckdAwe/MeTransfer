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
        <button type="button">edit</button>
        <br>
    </div>
    <div class='edit_password'>
        password :
        <button type="button">edit</button>
        <br>
    </div>
    <div class='edit_email'>
        email : 
        <button type="button">edit</button>
        <br>
    </div>
    <button type="button">back</button>

</body>
</html>