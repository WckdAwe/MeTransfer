<?php
require_once('../private_html/codebase.php');
//use \codebase\Templates\TemplateManager;
use \codebase\App\FileManager;

if(isset($_POST['upload'])){
    FileManager::uploadFile($_FILES['file']);
}

//$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
//$template->setPageTitle('My Account');
//$template->setLoginRequired(true);
?>

<!DOCTYPE html>
<html>

<head>
</head>

<body>
    <?php
        echo \codebase\App\ErrorManager::printErrors();
    ?>
    <form method="POST" action="" enctype="multipart/form-data">
        <div>
            <span>Upload a File:</span>
            <input type="file" name="file" />
        </div>

        <input type="submit" name="upload" value="Upload" />
    </form>
</body>

</html>