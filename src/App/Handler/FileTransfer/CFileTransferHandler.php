<?php
namespace App\Handler\FileTransfer;

use App\Helper\CCommon;
use App\Helper\CValidator;
use App\Helper\Messages\CUploadMessages;
use Respect\Validation\Validator as v;
use Slim\Http\UploadedFile;

class CFileTransferHandler extends UploadedFile
{
    /**
     * index mapping for file upload basic requirement with the corresponding error if
     * one of the index does not exist.
     *
     * @var array
     */
    private $indexMapping = [
        'baseName',
        'directory',
        'uploadedFile',
        'type',
    ];
    /**
     * Respect Validator
     *
     * @var \App\Helper\CValidator
     */
    private $validator;

    /**
     * Create a new CFileTransferHandler instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->validator = new CValidator();
    }
    /**
     * Validate index requirement for file upload
     *
     * @param array $upload
     * @return void
     */
    public function isValidaeFile($upload)
    {
        $filesize = $upload['uploadedFile']->size;
        $allowImageSize = ((5 * 1024) * 1024);
        foreach ($this->indexMapping as $key => $value) {
            if (!array_key_exists($value, $upload)) {
                return [
                    'error' => $this->stringInsert(CUploadMessages::NOTFOUND_IMAGE_INDEX, $value, 1),
                ];
            }
            if (empty($upload[$value])) {
                return [
                    'error' => $this->stringInsert(CUploadMessages::EMPTY_IMAGE_INDEX, $value, 1),
                ];
            }
        }
        if($filesize > $allowImageSize){
            return [
                'error' => CUploadMessages::MAX_SIZE_LIMIT,
            ];
        }
        return $this->validateFileExtensions($upload['uploadedFile'], $upload['type']);
    }
    /**
     * validate file extensions
     *
     * @param array $uploadedFile
     * @return array
     */
    public function validateFileExtensions($uploadedFile,$type)
    {
        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            return [
                'error' => CUploadMessages::FAILED, //$uploadedFile->getError(),
            ];
        }

        if (!in_array(strtolower(pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION)), CCommon::EXTENSION_DIRECTORY[$type])) {
            return [
                'error' => CUploadMessages::UNKNOWN_IMAGE_FORMAT,
            ];
        }
    }

    /**
     * insert strings
     *
     * @param string $str
     * @param string $insertstr
     * @param int $pos
     * @return string
     */
    public function stringInsert($str, $insertstr, $pos)
    {
        $count_str = strlen($str);
        $new_str = null;
        for ($i = 0; $i < $pos; $i++) {
            $new_str .= $str[$i];
        }

        $new_str .= "$insertstr";

        for ($i = $pos; $i < $count_str; $i++) {
            $new_str .= $str[$i];
        }

        return $new_str;

    }

}
