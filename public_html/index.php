<?php
require_once('../private_html/codebase.php');
\codebase\App\Account::trySignup("panaiwths", "123456");
//$template = \codebase\Templates\TemplateManager::getTemplate();
print_r(__DBCONF);
?>
<!---->
<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!---->
<!--    <head>-->
<!--        --><?php //echo $template->getHead(); ?>
<!--    </head>-->
<!---->
<!--    <body>-->
<!--        --><?php //echo $template->getNavigation() ?>
<!---->
<!--        --><?php //echo $template->getFooter(); ?>
<!--    </body>-->
<!--</html>-->