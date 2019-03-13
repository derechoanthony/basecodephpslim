<?php

namespace App\Controller\Users;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Model\User;

use App\Helper\CUserFactory;
use Interop\Container\ContainerInterface;
use App\Controller\BaseController;
use App\Handler\Users\CUserHandler;
use App\Service\Users\CUserService;
use App\Model\PersonalInfo;

class CUserController extends BaseController
{
    /**
     * Users model
     *
     * @var \App\model\CUser
     */
    private $userModel;

    /**
     * Service for this module
     *
     * @var \App\Service\Users
     */
    private $userService;

    /**
     * Validation for this module
     *
     * @var \App\Handler\Users
     */
    private $userHandler;
    /**
     * user personal info
     *
     * @var \App\Model\PersonalInfo
     */
    private $userInfo;

    /**
     * Create a new CUserController instance.
     *
     * @param \Interop\Container\ContainerInterface
     * 
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->userService = new CUserService();
        $this->userModel = new User();
        $this->userHandler = new CUserHandler();
        $this->userInfo = new PersonalInfo();
    }
    public function fetchExistingApplication(Request $request, Response $response){
        $uid = $request->getAttribute('userId');
        $data = $this->userService->fetchExistingApplication($uid);
        return $response->withJson($data);        
    }
    public function searchUsers(Request $request, Response $response)
    {
        $model = $this->convertToModel($request, $this->userModel);
        $data = $this->userService->searchUsers($model);

        return $response->withJson($data);
    }

    public function getFranchiseeInfo(Request $request, Response $response)
    {
        $userId = $request->getAttribute('userId');

        if (!$userId) {
            return $this->returnWithErrors(
                $response,
                [
                    "An invalid query parameter was sent."
                ]
            );
        }

        $data = $this->userService->getFranchiseeInfo($userId);

        return $response->withJson($data);
    }

    public function getLatestBackgroundInfo(Request $request, Response $response)
    {
        $userId = $request->getAttribute('userId');

        if (!$userId) {
            return $this->returnWithErrors(
                $response,
                [
                    "An invalid query parameter was sent."
                ]
            );
        }

        $data = $this->userService->getLatestBackgroundInfo($userId);

        return $response->withJson($data);
    }
    
    public function getUser(Request $request, Response $response, $args)
    {
        $data = $this->userService->getUser($args['userId']);
        return $response->withJson($data);
    }

    public function getSBUManagers(Request $request, Response $response, $args)
    {
        $sbus = strlen($request->getParam('sbus')) ? explode(",", $request->getParam('sbus')) : array();
        

        $data = $this->userService->getSBUManagers($sbus);
        return $response->withJson($data);
    }
    
    public function addUser(Request $request, Response $response)
    {
        $model = $this->convertToModel($request, $this->userModel);
        $validation = $this->userHandler->addUserHandler($model);

        if (!$validation->isValid()) {
            return $this->returnWithErrors(
                $response,
                $validation->getErrors()
            );
        }
        
        $data = $this->userService->addUser($model);

        return $response->withJson($data);
    }

    public function updateUser(Request $request, Response $response, $args)
    {
        $model = $this->convertToModel($request, $this->userModel, $args);
        $validation = $this->userHandler->updateUserHandler($model);
        
        if (!$validation->isValid()) {
            return $this->returnWithErrors(
                $response,
                $validation->getErrors()
            );
        }
        $data = $this->userService->updateUser($model);

        return $response->withJson($data);
    }
    public function updateAccountInformation(Request $request, Response $response, $args)
    {
        $model = $this->convertToModel($request, $this->userModel, $args);
        $validation = $this->userHandler->updateAccountInformation($model);

        if (!$validation->isValid()) {
            return $this->returnWithErrors(
                $response,
                $validation->getErrors()
            );
        }

        $data = $this->userService->updateAccountInformation($model,$request->getParam('userOldPassword'));

        return $response->withJson($data);
    }
    
    public function archivedUser(Request $request, Response $response, $args)
    {
        $data = $this->userService->archivedUser($args['userId']);
        return $response->withJson($data);
    }

    public function searchArchivedUsers(Request $request, Response $response, $args)
    {
        $model = $this->convertToModel($request, $this->userModel);
        $data = $this->userService->searchArchivedUsers($model);
        return $response->withJson($data);
    }
    
    public function activateArchivedUser(Request $request, Response $response, $args)
    {
        $data = $this->userService->activateArchivedUser($request->getAttribute('userId'));
        return $response->withJson($data);
    }


}