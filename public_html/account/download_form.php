<?php
    require_once('../../private_html/codebase.php');
    use \codebase\App\Users\Account;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="/assets/css/dl_form.css" rel="stylesheet"><link>
    </head>
    <body>
        <?php
            if(Account::isLoggedIn()){
                //change button... do onclick ... fuck css 
                echo    '<div class="button_pos">
                            <button class="btn"> Download </button>
                        </div>';
            }
            else{
                include 'login.php';
            }
        ?>
    </body>
</html>

