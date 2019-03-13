<?php

namespace App\Service\FileTransfer;

use App\Handler\FileTransfer\CFiletransferHandler_franchiseApplication;
use App\Helper\CUploadFiles;
use App\Helper\CUserFactory;

use App\Helper\Messages\CUploadMessages;
use App\Service\BaseService;
use App\Service\Domain\CUserDomain;
use App\Handler\FileTransfer\CFileTransferHandler;
use App\Service\Domain\CApplicationDomain;
use App\Helper\CCiper;
use App\Helper\CCommon;
use App\Handler\FileTransfer\CFiletransferHandler_CIBI;
use App\Handler\FileTransfer\CFiletransferHandler_Activity;
use App\Controller\FileTransfer\CFileTransferController;
use App\Helper\CFiletransfer_diretory;
use App\Helper\CFiletransfer_dataType;
use App\Helper\cs3Manger;

class CFileTransferService extends BaseService
{
    /**
     * Upload Files
     *
     * @var \App\Helper\CUploadFiles
     */
    private $upload;

    /**
     * franchise application upload
     *
     * @var \App\Handler\FileTransfer\CFiletransferHandler_franchiseApplication
     */
    private $franchiseApplicationUplod;

    /**
     * String insert
     *
     * @var \App\Handler\FileTransfer\CFileTransferHandler
     */
    private $replace;

    /**
     * global user id
     *
     * @var \App\Helper\CUserFactory
     */
    private $uid;

    /**
     * upload id encryption
     *
     * @var unique code
     */
    private $id;

    /**
     * specific directory
     *
     * @var Array
     */
    private $directory;

    /**
     * get the specific data type
     *
     * @var Array
     */
    private $getDataType;

    /**
     * Create a new CFileTransferService instance.
     *
     * @return void
     */


    /**
     * S3 manager
     *
     * @var \App\Helper\cs3Manger
     */
    private $s3;

    public function __construct()
    {
        $ciper = new CCiper();
        $this->s3 = new cs3Manger();
        $this->upload = new CUploadFiles();
        $this->franchiseApplicationUplod = new CFiletransferHandler_franchiseApplication();
        $this->replace = new CFileTransferHandler();
        $this->uid = CUserFactory::getId();
        $this->id = $ciper->cipher('encrypt', substr(md5(uniqid(rand(), true)), 0, 10));
        $this->directory= CFiletransfer_diretory::uploadDirectory();
        $this->getDataType = new CFiletransfer_dataType;
    }
    public function viewSharedFiles($files){
        return $this->returnSuccess(["image" => "http://franchising-uat.jfcgrp.com/storage/pdf-test_rgnqxk.pdf"]);

        // return $this->returnSuccess(["image" => $this->s3->getFile($file)]); 
        // return $this->returnSuccess(["image" => 'https://franchising-system.s3.ap-southeast-1.amazonaws.com/2-FILENAME_0-ZlM0aTZNUEtTUjVMeVZscCtMZU1wZz09.pdf?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Security-Token=FQoGZXIvYXdzEBoaDHnjO2sdNN92i8o0myLBA3QLKMmF2SMKzTk7WC2d%2F09fHUzSK%2BStNogy%2BFEpDEHfZxwjPIvYXB0DNB6yfJ2B7j%2BPcbOxw4nsSwNKFpogdGmma4oCOKP2iJo9OWZO9TyxDQZBb59pzdlrqpy4OOhyuksl3iVBCfHtTrKWByGVSMqJXEm%2Bx8%2BVo8r%2BO98m%2FSwOZWeymuGSELgZ%2FeCjCxPLM0Ib%2FTHgPnq8bAYeK8Vj2f%2BXiKTmPbDi6kRkQzgdGtVPX%2F0OtDGJc7ZlqnIn%2F4EOnQ%2BuNhAryRJ7iHKOSpjqrTamYlmiMdkwcTZO5wiEesZ4t0Alt127wUo65pGWmst%2F5Um2XFKwSCmyAsz8lWbehWyhEhB7Qq%2FEQJmFkLAJVzAmrMEdqXrVdzKdiAgCanLA6hB58fDnTb2qAIxzu84SIBZBIsUqld5CfIFebpZErQKTqEcjZSVQSPc89jaXutmrUROgfq6%2FzY4at62tc5lq%2BxoMGDJtmZZ%2Fp2cvvLEPbYRuAp7duyycG9Jd%2FT2yZiiatHzlSflz2Qq9P7c%2Bu2Sxs%2Buea3E55IcONi8KU%2B5hEfUXjxKeOPz%2Fx832mUm6Bd%2FrK4uw7czIMonleqiTcw68QilVKNnXqOAF&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIA47N75PYLPFTH3FNC%2F20181207%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20181207T084529Z&X-Amz-SignedHeaders=host&X-Amz-Expires=604800&X-Amz-Signature=0701006475090fc69dfffe65379f718d23449ddb9ea4ae662e98cb41ed9350b5']); 
    }
    public function getSharedFiles($file, $response){
        $s3Path = $this->s3->getFile($file);
        if (file_exists($s3Path) == false) {
            echo 'File does not exist.';
        }

        try {
            $fh = fopen($s3Path, 'r');
        } catch (Aws\S3\Exception\S3Exception $e) {
            echo "There was an error downloading the file. " . $e->getMessage() . "\n";
        }

        $fh = fopen($s3Path, 'r');
        // var_dump($s3Path);
        $basename = $file['alternative'] === 'file' ? 'file' : $file['name'];
        // $this->sitelog->logAction('downloaded an attached file', 'downloaded "' . $basename . "'");
        $stream = new \Slim\Http\Stream($fh);

        return $response
            ->withHeader('Content-Type', 'application/force-download')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="' . basename($file['name']) . '"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Pragma', 'public')
            ->withBody($stream);
    }
    /**
     * Global Service for upload
     *
     * @param [type] $request
     * @param [type] $type
     * @return void
     */
    public function upload($uploadedFiles, $type)
    {
        $uploadErrorResponse = [];
        $uploadResponse = [];
        
        $directory = $this->directory[$type];
        $dataType = $this->getDataType->getType($type);
        $validateUploadIndex = $this->franchiseApplicationUplod->validateIndex($uploadedFiles);
        
        if (empty($uploadedFiles)) {
            return $this->returnWithErrors($dataType['uploadError']);
        }
        
        if (!empty($validateUploadIndex)) {
            return $this->returnWithErrors($validateUploadIndex);
        }
        
        foreach ($uploadedFiles as $key => $values) {
            $imageFileName = $this->uid . '-' . $key . '-' . $this->id;
            if($type == "franchiseApplication"){
                $keyArray = explode("_", $key);
                $attachment_type = $keyArray[0];
                $attachment_uid = $keyArray[1];
            } else {
                $attachment_type = $dataType['dataType'];
            }
            
            $upload = $this->upload->uploadFiles([
                'baseName' => $imageFileName,
                'directory' => $directory,
                'uploadedFile' => $uploadedFiles[$key],
                "type" => $dataType['dataType'],
            ]);

            if (is_array($upload)) {
                $errorOccurs = $this->replace->stringInsert("[] ", $key, 1);
                array_push($uploadErrorResponse, $errorOccurs . $upload['error']);
            }

            /**
             * S3 preview
             */
            array_push($uploadResponse, [
                "file_name" => $upload,
                "directory" => $directory,
                "attachment_type" => $attachment_type,
                "attachment_uid" => empty($attachment_uid) ? null : $attachment_uid,
                // "S3upload"=> $this->s3->uploadFile($directory . '/', $upload)
            ]);
            
            
        }

        if (!empty($uploadErrorResponse)) {
            return $this->returnWithErrors($uploadErrorResponse);
        }

        if (empty($uploadResponse)) {
            return $this->returnWithErrors(CUploadMessages::NO_IMAGE_UPLOADED);
        }
        // CAWSService::uploadToAWS($directory);
        return $this->returnSuccess(["image" => $uploadResponse]);
    }
}
