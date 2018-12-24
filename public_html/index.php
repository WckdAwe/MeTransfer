<?php
require_once('../private_html/codebase.php');
print_r(__DBCONF);
use \codebase\App\Account;
use \codebase\Templates;
//$template = \codebase\Templates\TemplateManager::getTemplate();?>
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

<?php
Account::getInstance()->trySignup('panaiwtis1', '123456', 'panaiwtis@melisoulas.gr', 1);
Account::getInstance()->tryLogin('panaiwtis1', '123456');
if(Account::isLoggedIn()){
    echo "User is logged in\n";
    echo $_SESSION['username'];
}

?>