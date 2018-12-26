<?php
/**
 * Created by PhpStorm.
 * User: wicked
 * Date: 25/12/2018
 * Time: 9:08 μμ
 */

namespace codebase\App;


use codebase\App\Users\Account;
use codebase\App\Users\User;
use codebase\App\Users\UserFile;
use codebase\Helper;

class FileManager
{
    const ALLOWED_EXTENSIONS = ['jpg', 'gif', 'png', 'zip', 'txt'];
    const UPLOAD_DIR = ROOT.'/uploads/';

    const SHARE_TYPE_EMAIL = 0;
    const SHARE_TYPE_LINK = 1;

    public static function uploadFile($uploadedFile, $delete_at = '1', $email_addresses = [], $password = null)
    {
        if($uploadedFile['error'] == UPLOAD_ERR_OK){
            $fileTmpPath = $uploadedFile['tmp_name'];
            $fileName = $uploadedFile['name'];
            $fileSize = $uploadedFile['size'];
            // $fileType = $uploadedFile['type']; TODO: Implement file type in DB?
            $fileNameCmps = explode('.', $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = Helper::generateUID();

            if($delete_at < 1 || $delete_at > 4 || is_int($delete_at)) $delete_at = 1;

            $share_type = empty($email_addresses) ? self::SHARE_TYPE_LINK : self::SHARE_TYPE_LINK;

            if(in_array($fileExtension, self::ALLOWED_EXTENSIONS)){
                $dest_path = self::UPLOAD_DIR . $newFileName.'.'.$fileExtension;

                while(file_exists($dest_path)){
                    $newFileName = Helper::generateUID();
                    $dest_path = self::UPLOAD_DIR . $newFileName.'.'.$fileExtension;
                }

                if(move_uploaded_file($fileTmpPath, $dest_path)){
                    $fileOwner = Account::isLoggedIn() ? Account::user()->getId() : null;

                    $delete_at = date('Y-m-d H:i:s', strtotime("+$delete_at week"));
                    try {
                        if($password) $password = password_hash($password, PASSWORD_BCRYPT);
                        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
                        $PDO->beginTransaction();
                        $STMT = $PDO->prepare('INSERT INTO user_files values(DEFAULT, :uid, :file_name, :file_ext, :file_size, DEFAULT, :delete_at, :belongs_to, :share_type, :password)');
                        $STMT->bindParam(':uid', $newFileName, \PDO::PARAM_STR);
                        $STMT->bindParam(':file_name', $fileName, \PDO::PARAM_STR);
                        $STMT->bindParam(':file_ext', $fileExtension, \PDO::PARAM_STR);
                        $STMT->bindParam(':file_size', $fileSize, \PDO::PARAM_INT);
                        $STMT->bindParam(':delete_at', $delete_at, \PDO::PARAM_STR);
                        $STMT->bindParam(':belongs_to', $fileOwner, \PDO::PARAM_INT);
                        $STMT->bindParam(':share_type', $share_type, \PDO::PARAM_INT);
                        $STMT->bindParam(':password', $password, \PDO::PARAM_STR);

                        $STMT->execute();
                        $PDO->commit();

                        Helper::redirect('/dl/'.$newFileName);
                        return true;
                    }catch(\Exception $e){
                        ErrorManager::addError(Language::ERR_FATAL, $e->getMessage());
                        unlink($dest_path); // TODO: Delete file if PDO Exception happens. (Currently overriden by PDO->getInstance -> Redirects if PDO Down);
                        return false;
                    }
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

    public static function getFileById($id) : ?UserFile
    {
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT * FROM user_files WHERE (`id` = :file_id)');
        $STMT->bindParam(':file_id',$id, \PDO::PARAM_INT);
        $STMT->execute();
        $STMT->setFetchMode(\PDO::FETCH_CLASS, __NAMESPACE__ . '\\Users\\UserFile');
        $result = $STMT->fetch(\PDO::FETCH_CLASS);
        return $result ? $result : null;
    }

    public static function getFileByUid($uid) : ?UserFile
    {
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT * FROM user_files WHERE (`uid` = :file_uid)');
        $STMT->bindParam(':file_uid',$uid, \PDO::PARAM_STR);
        $STMT->execute();
        $STMT->setFetchMode(\PDO::FETCH_CLASS, __NAMESPACE__ . '\\Users\\UserFile');
        $result = $STMT->fetch(\PDO::FETCH_CLASS);
        return $result ? $result : null;
    }
}