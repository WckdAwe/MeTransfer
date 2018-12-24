<DOCTYPE html>
<html lang="en">
<?php echo $template->getHead(); ?>
<body>
    <?php
        $username = $_SESSION['username'];
        $password = $_SESSION['password'];
    ?>
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