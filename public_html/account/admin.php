<?php
require_once('../../private_html/codebase.php');
use \codebase\Templates\TemplateManager;
use \codebase\App\Users\Account;
use codebase\Helper;

$template = TemplateManager::getTemplate(TemplateManager::TMPL_ACCOUNT);
$template->setPageTitle('Admin panel');
#$template->setLoginRequired(true);
#if( !Account::user()->isAdmin() ){
#    echo 'Unauthorized access';
#    Helper::redirect('index');
#}

$PDO =  \codebase\Databases\PHPDataObjects::getInstance();
$STMT = $PDO->prepare('SELECT `file_name` FROM user_files');
$STMT->execute();
$result = $STMT->fetch(\PDO::FETCH_ASSOC);

if(isset($_POST['submit'])){
    try{
        $delete_name = $_POST['name'];
        $PDO->beginTransaction();
        $STMT = $PDO->prepare('DELETE FROM user_file WHERE `file_name`= :given_name');
        $STMT->bindParam(':given_name', $delete_name, \PDO::PARAM_STR);
        $STMT->execute();
        $PDO->commit();
    }catch(\Exception $e){
        echo "You can't delete now";
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<?php echo $template->getHead(); ?>
<body>
    <div>
        <div class="flex_box">
            <div class="center_box">
                <div>
                    <?php 
                        echo "<pre>";
                        print_r($result);
                        echo "</pre>";
                    ?>
                </div>
                <div>
                    <form action="" method="POST">
                        <div>
                            <div>Name of file you want to delete:</div>
                            <input type="text" name="name">
                        </div>
                        <div>
                            <input type="submit" name="submit" value="Delete">
                        <div>   
                    </form>
                </div>
            </div>
        <div>
    </div>
</body>
</html>