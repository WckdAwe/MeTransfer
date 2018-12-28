<?php

namespace codebase\App\Users;

use codebase\App\ErrorManager;
use codebase\App\FileManager;
use codebase\App\Language;
use codebase\Helper;

class UserFile {
    private $id, $uid;
    private $file_name, $file_ext, $file_size;
    private $created_at, $delete_at;
    private $belongs_to;
    private $share_type;
    private $password;

    public function getId()
    {
        return $this->id;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getFileName()
    {
        return $this->file_name;
    }

    public function getFileExt()
    {
        return $this->file_ext;
    }

    public function getFileSize()
    {
        return $this->file_size;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getDeleteAt()
    {
        return $this->delete_at;
    }

    public function getBelongsTo()
    {
        return $this->belongs_to;
    }

    public function getShareType()
    {
        return $this->share_type;
    }

    public function getShareTypeAsText()
    {
        switch($this->getShareType()){
            case FileManager::SHARE_TYPE_EMAIL:
                return 'Email';
            case FileManager::SHARE_TYPE_LINK:
                return 'Link';
        }
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUrl(){
        $default_url = '/dl/'.$this->getUid();
        return $default_url;
    }

    public function getLocalPath(){
        return FileManager::UPLOAD_DIR.$this->getUid().'.'.$this->getFileExt();
    }

    public function hasExpired(){
        return strtotime($this->getDeleteAt()) - time() < 0 ? true : false;
    }

    public function hasPassword() : bool
    {
        return $this->getPassword() ? true : false;
    }

    public function delete()
    {
        if (!Account::isLoggedIn()) {
            ErrorManager::addError(Language::ERR_LOGIN_REQUIRED_ACTION);
            return false;
        }

        $user = Account::user();
        if ($user->getId() != $this->getBelongsTo()) { // TODO: OR IS ADMINISTRATOR
            ErrorManager::addError(Language::ERR_NO_PERMISSION, 'remove this file');
            return false;
        }

        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $PDO->beginTransaction();
        $STMT = $PDO->prepare('DELETE FROM user_files WHERE (`id` = :id)');
        $STMT->bindParam(':id', $this->id, \PDO::PARAM_INT);
        $STMT->execute();
        $PDO->commit();

        unlink($this->getLocalPath());
        Helper::redirect('/account/my_files');
    }

    public function getAccessEmails(){
        $PDO = \codebase\Databases\PHPDataObjects::getInstance();
        $STMT = $PDO->prepare('SELECT `email` FROM file_auth WHERE (`file_id` = :file_id)');
        $STMT->bindParam(':file_id',$this->id, \PDO::PARAM_INT);
        $STMT->execute();

        $db_result = $STMT->fetchAll();
        $result = [];
        foreach ($db_result as $db_item) array_push($result, $db_item['email']);
        return $result;
    }
}