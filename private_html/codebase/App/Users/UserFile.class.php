<?php

namespace codebase\App\Users;

use codebase\App\ErrorManager;
use codebase\App\FileManager;
use codebase\App\Language;
use codebase\Databases\PHPDataObjects;
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

    public function getPassword()
    {
        return $this->password;
    }

    public function getUrl(){
        return '/dl/'.$this->getUid();
    }

    public function hasExpired(){
        return strtotime($this->getDeleteAt()) - time() < 0 ? true : false;
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

        unlink(FileManager::UPLOAD_DIR.$this->getUid().'.'.$this->getFileExt());
        Helper::redirect('/account/my_files');
    }
}