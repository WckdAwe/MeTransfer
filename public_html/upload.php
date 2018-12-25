<?php
    session_start();

    $msg = '';
    if(isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload'){
        if(isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK){
            //uploaded file details 
            $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
            $fileName = $_FILES['uploadedFile']['name'];
            $fileSize = $_FILES['uploadedFile']['size'];
            $fileType = $_FILES['uploadedFile']['type'];
            $fileNameCmps = explode('.', $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            //allowed extensions
            $allowedFileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt');

            if(in_array($fileExtension, $allowedFileExtensions)){
                // path for upload files ... TO DO --> one dir behind 
                $uploadFileDir = 'uploads/';
                $dest_path = $uploadFileDir . $newFileName;

                if(move_uploaded_file(fileTmpPath, $dest_path)){
                    $msg = 'File upload success !';
                }
                else{
                    $msg = 'Error moving the file to upload dir !';
                }
            }
            else{
                $msg = 'Upload failed! Allowed file types: ' . implode(',', $allowedFileExtensions);
            }
        }
        else{
            $msg = 'There is some error in the file upload. Please check the following error.<br>';
            $msg .= 'Error:' . $_FILES['uploadedFile']['error'];
        }
    }
    $_SESSION['message'] = $msg;
    header('Location: index.php');
?>