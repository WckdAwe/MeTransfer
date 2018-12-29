<?php

namespace codebase\App;


use codebase\App\Users\Account;
use codebase\App\Users\UserFile;
use codebase\Emails\NewTransferEmail;
use codebase\Emails\NewTransferOwnerEmail;
use codebase\Helper;

class FileManager
{
    const ALLOWED_EXTENSIONS = ['jpg', 'gif', 'png', 'zip', 'txt'];
    const UPLOAD_DIR = ROOT.'/uploads/';

    const SHARE_TYPE_EMAIL = 0;
    const SHARE_TYPE_LINK = 1;

    public static function uploadFile($uploadedFile, $delete_at = '1', $sender_email, $email_addresses = [], $included_message = null, $password = null)
    {
        $share_type = empty($email_addresses) ? self::SHARE_TYPE_LINK : self::SHARE_TYPE_EMAIL;
        if($share_type == self::SHARE_TYPE_EMAIL){
            $email_addresses = str_replace(' ', '', $email_addresses);
            if(strpos($email_addresses, ',') !== false){
                $email_addresses = explode(',', $email_addresses);
            }else{
                $email_addresses = [$email_addresses];
            }
            foreach ($email_addresses as $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    ErrorManager::addError(Language::ERR_INVALID, 'A receiver\'s email');
                    return false;
                }
            }

        }

        if((self::SHARE_TYPE_LINK && !empty($sender_email)) || self::SHARE_TYPE_EMAIL) {
            if (!filter_var($sender_email, FILTER_VALIDATE_EMAIL)) {
                ErrorManager::addError(Language::ERR_INVALID, 'The sender\'s email');
                return false;
            }
        }

        if(Account::isLoggedIn())
            $sender_email = Account::user()->getEmail(); // Override sender email if user is logged in.

        if($uploadedFile['error'] == UPLOAD_ERR_NO_FILE){
            ErrorManager::addError(Language::ERR_MISSING, 'uploaded file');
            return false;
        }
        if($uploadedFile['error'] != UPLOAD_ERR_OK){
            ErrorManager::addError(Language::ERR_FATAL, $uploadedFile['error']);
            return false;
        }

        $fileTmpPath = $uploadedFile['tmp_name'];
        $fileName = $uploadedFile['name'];
        $fileSize = $uploadedFile['size'];
        $fileNameCmps = explode('.', $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        if(!in_array($fileExtension, self::ALLOWED_EXTENSIONS)) {
            ErrorManager::addError(Language::ERR_FILE_TYPE, implode(', ',self::ALLOWED_EXTENSIONS));
            return false;
        }

        $newFileName = Helper::generateUID();
        if($delete_at < 1 || $delete_at > 4 || is_int($delete_at)) $delete_at = 1; // Verify that delete_at is between 1-4 weeks.
        $dest_path = self::UPLOAD_DIR . $newFileName.'.'.$fileExtension;

        while(file_exists($dest_path)){
            $newFileName = Helper::generateUID();
            $dest_path = self::UPLOAD_DIR . $newFileName.'.'.$fileExtension;
        }

        if(move_uploaded_file($fileTmpPath, $dest_path)){
            $fileOwner = Account::isLoggedIn() ? Account::user()->getId() : null;

            $delete_at = date('Y-m-d H:i:s', strtotime("+$delete_at week"));
            $PDO = null;
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


                if($share_type == self::SHARE_TYPE_EMAIL) {
                    $file_id = $PDO->lastInsertId();

                    $email_addresses = array_unique(array_merge($email_addresses, [$sender_email])); // Merge and remove duplicate email addresses

                    // TODO: Find a better way to do this? maybe? Well we already know one but yeah... testing things out. This is not safe btw... just saying
                    $query = '';
                    foreach ($email_addresses as $key => $email)
                        $query .= '(:file_id, :email'.$key.'),';

                    $query = substr($query, 0, -1); // Remove last ','

                    $STMT = $PDO->prepare('INSERT INTO file_auth (`file_id`, `email`) values '.$query);
                    $STMT->bindParam(':file_id', $file_id, \PDO::PARAM_INT);

                    foreach ($email_addresses as $key => $email)
                        $STMT->bindValue(':email' . $key, $email, \PDO::PARAM_STR);

                    $STMT->execute();

                    $email = new NewTransferOwnerEmail($fileName, '/dl/' . $newFileName . '/'.$sender_email, $included_message);
                    $email->setReceivers($sender_email);
                    $email->sendEmail();

                    foreach ($email_addresses as $email_addr){ // TODO: Yeap... We could also improve that... if it was going to be an actual app.
                        $email = new NewTransferEmail($fileName, '/dl/' . $newFileName . '/'.$email_addr, $included_message);
                        $email->setReceivers($email_addr);
                        $email->sendEmail();
                    }
                }

                $PDO->commit();
                Helper::redirect('/dl/'.$newFileName.'/'.$sender_email);
                return true;
            }catch(\MailException $e){
                ErrorManager::addError('Email Exception Test');
                unlink($dest_path);
                $PDO->rollBack();
            }
            catch(\Exception $e){
                ErrorManager::addError(Language::ERR_FATAL, $e->getMessage());
                unlink($dest_path); // TODO: Delete file if PDO Exception happens. (Currently overriden by PDO->getInstance -> Redirects if PDO Down)
            }
        }else{
            ErrorManager::addError(Language::ERR_FILE_MOVE);
        }
        return false;
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

    public static function getAllFiles()
    {
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT * FROM user_files ORDER BY `id` DESC');
        $STMT->execute();
        $STMT->setFetchMode(\PDO::FETCH_CLASS, __NAMESPACE__ . '\\Users\\UserFile');
        return $STMT->fetchAll(\PDO::FETCH_CLASS, __NAMESPACE__ . '\\Users\\UserFile');
    }

}