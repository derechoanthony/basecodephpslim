<?php

namespace App\Controller\FileTransfer;

use App\Controller\BaseController;
use App\Handler\FileTransfer\CFileTransferHandler;
use App\Model\BackgroundAttachment;
use App\Model\User;
use App\Service\Avatar\CAvatar;
use App\Service\FileTransfer\CFileTransferService;
use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class CFileTransferController extends BaseController
{
    /**
     * Users model
     *
     * @var \App\model\User
     */
    private $userModel;
    /**
     * profile image
     *
     * @var \App\Service\Avatar\CAvatar
     */
    private $avatar;

    /**
     * Service for this module
     *
     * @var \App\Service\CFileTransferService
     */
    private $fileTransferService;

    /**
     * Validation for this module
     *
     * @var \App\Handler\FileTransfer
     */
    private $fileTransferHandler;

    /**
     * background attachment
     *
     * @var \App\Model\BackgroundAttachment
     */
    private $bgAttachment;

    /**
     * Create a new CFileTransferController instance.
     *
     * @param \Interop\Container\ContainerInterface
     *
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->fileTransferService = new CFileTransferService();
        $this->userModel = new User();
        $this->bgAttachment = new BackgroundAttachment();
        $this->fileTransferHandler = new CFileTransferHandler();
        $this->avatar = new CAvatar();
    }
    public function sharedFiles(Request $request, Response $response)
    {
        $file = $this->fileTransferService->getSharedFiles($request->getParam('file_name'), $response);
        return $file;
    }
    public function viewSharedFiles(Request $request, Response $response)
    {
        $file = $this->fileTransferService->viewSharedFiles($request->getParam('file_name'));
        return $response->withJson($file);
    }
    public function franchiseApplicationUpload(Request $request, Response $response)
    {
        $data = $this->fileTransferService->upload($request->getUploadedFiles(), "franchiseApplication");
        return $response->withJson($data);
    }

    public function siteApplicationUpload(Request $request, Response $response)
    {
        $data = $this->fileTransferService->upload($request->getUploadedFiles(), "siteApplication");
        return $response->withJson($data);
    }

    public function siteAssessmentDocumentUpload(Request $request, Response $response)
    {
        $data = $this->fileTransferService->upload($request->getUploadedFiles(), "siteAssessmentDocument");
        return $response->withJson($data);
    }

    public function profileImage(Request $request, Response $response)
    {
        $model = $this->convertToModel($request, $this->userModel);
        $data = $this->avatar->profileImage($model, $request);
        return $response->withJson($data);
    }

    public function CIBIUpload(Request $request, Response $response)
    {
        $data = $this->fileTransferService->upload($request->getUploadedFiles(), "CIBI");
        return $response->withJson($data);
    }

    public function HAUpload(Request $request, Response $response)
    {
        $data = $this->fileTransferService->upload($request->getUploadedFiles(), "harrisonAssessment");
        return $response->withJson($data);
    }
    public function GTMUpload(Request $request, Response $response)
    {
        $data = $this->fileTransferService->upload($request->getUploadedFiles(), "GTM");
        return $response->withJson($data);
    }
    public function interviewSummaryUpload(Request $request, Response $response)
    {
        $data = $this->fileTransferService->upload($request->getUploadedFiles(), "interviewSummary");
        return $response->withJson($data);
    }

}
