<?php
/**
 * Created by PhpStorm.
 * User: wicked
 * Date: 25/12/2018
 * Time: 9:08 μμ
 */

namespace codebase\App;


use codebase\App\Users\Account;
use codebase\Helper;

class FileManager
{
    const ALLOWED_EXTENSIONS = ['jpg', 'gif', 'png', 'zip', 'txt'];

    public static function uploadFile($uploadedFile, $delete_at = '1')
    {
        if($uploadedFile['error'] == UPLOAD_ERR_OK){
            $fileTmpPath = $uploadedFile['tmp_name'];
            $fileName = $uploadedFile['name'];
            $fileSize = $uploadedFile['size'];
            $fileType = $uploadedFile['type'];
            $fileNameCmps = explode('.', $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = Helper::generateUID();
            if($delete_at < 1 || $delete_at > 4 || is_int($delete_at)) $delete_at = 1;

            if(in_array($fileExtension, self::ALLOWED_EXTENSIONS)){
                $uploadFileDir = ROOT.'/uploads/';
                $dest_path = $uploadFileDir . $newFileName.'.'.$fileExtension;

                while(file_exists($dest_path)){
                    $newFileName = Helper::generateUID();
                    $dest_path = $uploadFileDir . $newFileName.'.'.$fileExtension;
                }

                if(move_uploaded_file($fileTmpPath, $dest_path)){
                    $fileOwner = Account::isLoggedIn() ? Account::user()->getId() : null;

                    $delete_at = date('Y-m-d H:i:s', strtotime("+$delete_at week"));
                    $PDO = \codebase\Databases\PHPDataObjects::getInstance();
                    $PDO->beginTransaction();
                    $STMT = $PDO->prepare('INSERT INTO user_files values(DEFAULT, :uid, :file_name, :file_ext, :file_size, DEFAULT, :delete_at, :belongs_to)');
                    $STMT->bindParam(':uid', $newFileName, \PDO::PARAM_STR);
                    $STMT->bindParam(':file_name', $fileName, \PDO::PARAM_STR);
                    $STMT->bindParam(':file_ext', $fileExtension, \PDO::PARAM_STR);
                    $STMT->bindParam(':file_size', $fileSize, \PDO::PARAM_INT);
                    $STMT->bindParam(':delete_at', $delete_at, \PDO::PARAM_STR);
                    $STMT->bindParam(':belongs_to', $fileOwner, \PDO::PARAM_INT);
                    $STMT->execute();
                    $PDO->commit();
                    return true;
                }

                ErrorManager::addError(Language::ERR_FILE_MOVE);
                return false;
            }
            ErrorManager::addError(Language::ERR_FILE_TYPE, implode(', ',self::ALLOWED_EXTENSIONS));
            return false;
        }else{
            ErrorManager::addError(Language::ERR_FATAL, $uploadedFile['error']);
            return false;
        }
    }
}