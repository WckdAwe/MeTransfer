<?php
require_once('../../private_html/codebase.php');
use \codebase\Templates\TemplateManager;
use \codebase\App\Users\Account;
use \codebase\App\ErrorManager;
use \codebase\Helper;

$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
$template->setPageTitle('Edit profile info');
$template->setLoginRequired(true);

$user = Account::user();
$user_info = $user->info();
if(isset($_POST['pass_edit'])){
    if($user->changePassword($_POST['password_verification'], $_POST['password'], $_POST['password_check'])){
        Account::logout('/account');
    }
}else if(isset($_POST['prof_edit'])){
    $user->updateInfo($_POST['password_verification'], $_POST['email'], $_POST['first_name'],
                      $_POST['last_name'], $_POST['gender'], $_POST['birthday']);

    // Fetch Updated info. || TODO: Find a better way for this.
    $user = Account::user();
    $user_info = $user->info();
}
?>

<!DOCTYPE html>
<html lang="en">
    <?php echo $template->getHead(); ?>
    <body>
        <div class="flex_box">
            <div class="center_box">
                <h2>Edit your Profile</h2>
                <?php if(isset($_POST['prof_edit'])) echo ErrorManager::printErrors(); ?>
                <div>
                    Something missing? Maybe something needs to be changed.
                    <br>
                    Don't worry! We got you covered!
                </div>
                <form action="" method="POST">
                    <div>
                        Username:
                        <input type="text" value="<?php echo $user->getUsername(); ?>" readonly>
                    </div>
                    <div>
                        Email:
                        <input type="email" name="email" value="<?php echo $user->getEmail(); ?>">
                    </div>
                    <div>
                        First Name:
                        <input type="text" name="first_name" value="<?php echo $user_info->getFirstName(); ?>" placeholder="John">
                    </div>
                    <div>
                        Last Name:
                        <input type="text" name="last_name" value="<?php echo $user_info->getLastName(); ?>" placeholder="Doe">
                    </div>
                    <div>
                        Gender:
                        <select name="gender">
                            <option value="0" <?php echo Helper::HTMLSelected($user_info->getGender(), 0); ?>>Prefer not to say</option>
                            <option value="1" <?php echo Helper::HTMLSelected($user_info->getGender(), 1); ?>>Male</option>
                            <option value="2" <?php echo Helper::HTMLSelected($user_info->getGender(), 2); ?>>Female</option>
                        </select>
                    </div>
                    <div>
                        Birthday:
                        <input type="date" name="birthday" value="<?php echo date('Y-m-d', strtotime($user_info->getBirthday())); ?>">
                    </div>
                    <div>
                        Confirm with password:
                        <input type="password" name="password_verification" value="">
                    </div>

                    <div>
                        <input type="submit" name="prof_edit" value="Update my profile">
                    </div>
                </form>

                <hr>

                <h3>Change password</h3>
                <?php if(isset($_POST['pass_edit'])) echo ErrorManager::printErrors(); ?>
                <div>
                    Or... maybe you want to change that old
                    <br>
                    and stinky password with something new.
                </div>
                <form action="" method="POST">
                    <div>
                        Old password:
                        <input type="password" name="password_verification" value="">
                    </div>

                    <div>
                        New password:
                        <input type="password" name="password" value="">
                    </div>

                    <div>
                        New password check:
                        <input type="password" name="password_check" value="">
                    </div>

                    <div>
                        <input type="submit" name="pass_edit" value="Change password">
                    </div>
                </form>

                <hr>

                <div>
                    Thanks for being a <strong>loyal member</strong> since
                    <br>
                    <?php echo date( "d/m/Y", strtotime($user->getCreatedAt())); ?>
                </div>
                <div>
                    Go back to my <a href="/account">Account page</a>.
                </div>
            </div>
        </div>
    </body>
</html>