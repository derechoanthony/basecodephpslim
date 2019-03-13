<?php

namespace App\Service\Avatar;

use App\Helper\Messages\CUploadMessages;
use App\Service\BaseService;
use App\Service\Domain\CUserDomain;
use App\Service\FileTransfer\CFileTransferService;
use App\Transformer\Avatar\CAvatarTransformer;
// use App\Helper\cs3Manger;
class CAvatar extends BaseService
{
    /**4-profileImage-MUh6WlREY1NRWVFOaG43c2xYUlBhQT09.jpg
     * CUser business logic
     *
     * @var \App\Service\CUserDomain
     */
    private $userDomain;

    /**
     * Upload files
     *
     * @var \App\Service\FileTransfer\CFileTransferService
     */
    private $upload;
    /**
     * S3 manager
     *
     * @var \App\Helper\cs3Manger
     */
    private $s3;

    public function __construct()
    {
        $this->userDomain = new CUserDomain();
        // $this->s3 = new cs3Manger();
        $this->upload = new CFileTransferService();
        $this->avatarTransformer = new CAvatarTransformer();
    }
    /**
     * Undocumented function
     *
     * @param \App\Models\CUser $model
     * @param \Slim\Http\Request $request
     * @return Array
     */
    public function profileImage($model, $request)
    {
        $uploadedFiles = $request->getUploadedFiles();
        $uid = $model->id;
        $imageUpload = $this->upload->upload($uploadedFiles, "avatar");

        if ($imageUpload['success'] === false) {
            return $this->returnWithErrors($imageUpload['errors']);
        }

        $insertAvatar = $this->userDomain->insertAvatar([
            "id" => $uid,
            "profile_pic" => $imageUpload['data']['image'][0]['file_name'],
        ]);

        if (!$insertAvatar) {
            return $this->returnWithErrors(CUploadMessages::FAILED);
        }
        
        $user = $this->userDomain->getUserById($uid);
        $profileImage = null;

        /**
         * S3 preview
         */
        $image = $user->profile_pic;
        $profileImage = null;
        // $profileImage = $this->s3->getFile($image);
        $user['profile_pic'] = $profileImage;

        return $this->returnSuccess(
            [
                'user' => $this->avatarTransformer->transform($user),
            ]
        );
    }
}
