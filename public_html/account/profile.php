<DOCTYPE html>
<html>
<head>
    <title>Profile page</title>
    <link rel="stylesheet" href="\assets\css\profile_style.css">
</head>
<body>
    <div>
        <?php
            $username = $_SESSION['username'];
            echo '<h2> Hello'.$username.',</h2>';  
        ?>
        <br>
        <br>
        <input type="Button" value="Upload file" name="upload_button" class="button_style">
        <input type="Button" value="Profile settings" name="settings_button" class="button_style">
        <input type="Button" value="logout" name="logout_button" class="button_style">
    </div>
</body>
</html>