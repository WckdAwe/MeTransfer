<?php
require_once('../private_html/codebase.php');
use \codebase\Templates\TemplateManager;


//$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
//$template->setPageTitle('My Account');
//$template->setLoginRequired(true);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title> MeTransfer </title>
        <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    </head>
    <body class="grad">
        <div class="logo">
            <p class="logo_text"> MeTransfer </p>
        </div>
        <div class="login_subscribe_button">
            <a href="/account/login"> LOGIN </a> <br>
            <a href="/account/register"> REGISTER </a>
        </div><br>
        <div class="transfer_window">
            <p class="transfer_icon"> <b> Send Files </b></p> <br>
            <form class="" action="" method="POST">
                <input type="email" name="receiver" value="Email to">
                <input type="email" name="sender" value="Your email">
                <textarea name="message" rows="auto" cols="auto"> Message </textarea> <br>
                <label> Send as:
                    <input type="radio" checked="checked" name="radio">
                </label> Email
                <label>
                    <input type="radio" name="radio">
                </label> Link <br><br>
                <label> Delete after:
                    <select>
                        <option value="1w"> 1 week </option>
                        <option value="2w"> 2 weeks </option>
                        <option value="3w"> 3 weeks </option>
                        <option value="1m"> 1 month </option>
                    </select>
                </label> <br><br>
                <input type="submit" name="submit" value="Transfer">


                <!-- placeholder "set password" <br>
                TO DO LATER: dropdown menu "advanced options": "del after", "shorten url", "protect with pass". -->
            </form>
        </div>
    </body>
</html>