<?php

namespace codebase\App\CronJobs;

use \codebase\App\Users\UserFile;

$PDO = \codebase\Databases\PHPDataObjects::getInstance();
$PDO->beginTransaction();
$STMT = $PDO->prepare('SELECT * FROM `user_files` WHERE `delete_at`-CURRENT_TIMESTAMP()<0');
$STMT->execute();
$STMT->setFetchMode(\PDO::FETCH_CLASS, UserFile::class);
$files = $STMT->fetchAll(\PDO::FETCH_CLASS, UserFile::class);

foreach ($files as $file) {
    if($file instanceof UserFile){
        $path = $file->getLocalPath();
        try{
            if(file_exists($path)){
                unlink($path);
            }
        }catch (\Exception $e){}
    }
}

$STMT = $PDO->prepare('DELETE FROM `user_files` WHERE `delete_at`-CURRENT_TIMESTAMP()<0');
$STMT->execute();
$PDO->commit();