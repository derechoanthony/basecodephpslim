<?php

namespace App\Helper;

use App\Handler\FileTransfer\CFileTransferHandler;
use Slim\Http\UploadedFile;

class CUploadFiles
{
    /**
     * validate upload
     *
     * @var \App\Handler\FileTransfer\CFileTransferHandler
     */
    private $validateUpload;
    /**
     * Create a new CUploadFiles instance.
     */
    public function __construct()
    {
        $this->validateUpload = new CFileTransferHandler();
    }

    /**
     * move the image to specific directory and create a unique filename
     *
     * @param String $directory
     * @param UploadedFile $uploadedFile
     * @param String $identifier
     * @return void
     */
    public function moveUploadedFile($directory, $uploadedFile, String $basename)
    {
        $filename = sprintf('%s.%0.8s',
            $basename,
            pathinfo(
                $uploadedFile->getClientFilename(),
                PATHINFO_EXTENSION
            )
        );
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
        return $filename;
    }

    public function uploadFiles(array $upload)
    {
        $validateIndex = $this->validateUpload->isValidaeFile($upload);
        if (!is_null($validateIndex)) {
            return $validateIndex;
        }

        if ($upload['uploadedFile']->getError() === UPLOAD_ERR_OK) {

            return $this->moveUploadedFile(
                $upload['directory'],
                $upload['uploadedFile'],
                $upload['baseName']
            );
        }
    }
}
