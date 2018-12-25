<?php
/**
 * Created by PhpStorm.
 * User: wicked
 * Date: 25/12/2018
 * Time: 9:08 μμ
 */

namespace codebase\App;


use codebase\Helper;

class FileManager
{
    const ALLOWED_EXTENSIONS = ['jpg', 'gif', 'png', 'zip', 'txt'];

    public static function uploadFile($uploadedFile){

        if($uploadedFile['error'] == UPLOAD_ERR_OK){
            $fileTmpPath = $uploadedFile['tmp_name'];
            $fileName = $uploadedFile['name'];
            $fileSize = $uploadedFile['size'];
            $fileType = $uploadedFile['type'];
            $fileNameCmps = explode('.', $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = Helper::generateUID();

            if(in_array($fileExtension, self::ALLOWED_EXTENSIONS)){
                $uploadFileDir = ROOT.'/uploads/';
                $dest_path = $uploadFileDir . $newFileName.'.'.$fileExtension;

                if(move_uploaded_file($fileTmpPath, $dest_path)){
                    return true;
                }

                ErrorManager::addError(Language::ERR_FILE_MOVE);
            }
            ErrorManager::addError(Language::ERR_FILE_TYPE, implode(', ',self::ALLOWED_EXTENSIONS));
            return false;
        }else{
            ErrorManager::addError(Language::ERR_FATAL, $uploadedFile['error']);
            return false;
        }
    }
}