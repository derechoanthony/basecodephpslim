<?php

namespace App\Controller\Auth;

use App\Controller\BaseController;
use App\Handler\Auth\CAuthHandler;
use App\Model\User;
use App\Service\Auth\CAuthService;
use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Helper\CCiper;
use App\Helper\Mailer\CSendEmail;

class CAuthController extends BaseController
{
    /**
     * Users model
     *
     * @var \App\model\User
     */
    private $userModel;

    /**
     * Service for this module
     *
     * @var \App\Service\Auth
     */
    private $authService;

    /**
     * Validation for this module
     *
     * @var \App\Handler\Auth
     */
    private $authHandler;
    private $email;
    /**
     * Create a new CAuthService instance.
     *
     * @param \Interop\Container\ContainerInterface
     *
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->authService = new CAuthService();
        $this->userModel = new User();
        $this->authHandler = new CAuthHandler();
        $this->email = new CSendEmail();
    }

    /**
     * Authenticate user
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     */
    public function login(Request $request, Response $response)
    {
        $model = $this->convertToModel($request, $this->userModel);
        $validation = $this->authHandler->loginHandler($model);

        if (!$validation->isValid()) {
            return $this->returnWithErrors(
                $response,
                $validation->getErrors()
            );
        }
        $data = $this->authService->login($model);
        return $response->withJson($data);
    }

    /**
     * Register a new user
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     */
    public function register(Request $request, Response $response)
    {
        $model = $this->convertToModel($request, $this->userModel);
        $validation = $this->authHandler->registerHandler($model);

        if (!$validation->isValid()) {
            return $this->returnWithErrors(
                $response,
                $validation->getErrors()
            );
        }

        $data = $this->authService->register($model);

        return $response->withJson($data);
    }

    /**
     * Reset users password
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     */
    public function resetPassword(Request $request, Response $response)
    {
        $model = $this->convertToModel($request, $this->userModel);
        $validation = $this->authHandler->resetPasswordHandler($model);
        if (!$validation->isValid()) {
            return $this->returnWithErrors(
                $response,
                $validation->getErrors()
            );
        }

        $data = $this->authService->resetPassword($model);

        return $response->withJson($data);
    }

    /**
     * validate change password request
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     */
    public function validatePasswordRequest(Request $request, Response $response)
    {
        $model = $this->convertToModel($request, $this->userModel);
        $data = $this->authService->validatePasswordRequest($model);
        return $response->withJson($data);
    }
    /**
     * Change users password
     *
     * @param \Slim\Http\Request  $request
     * @param \Slim\Http\Response $response
     *
     * @return \Slim\Http\Response
     */
    public function changePassword(Request $request, Response $response)
    {
        $model = $this->convertToModel($request, $this->userModel);
        $validation = $this->authHandler->changePasswordHandler($model);
        if (!$validation->isValid()) {
            return $this->returnWithErrors(
                $response,
                $validation->getErrors()
            );
        }

        $data = $this->authService->changePassword($model);

        return $response->withJson($data);
    }
    /**
     * Activate user Account
     *
     * @param Request $request
     * @param Response $response
     * @return \Slim\Http\Response
     */
    public function activateAccount(Request $request, Response $response)
    {
        $userAccount = [
            'userEmail'=> $request->getAttribute('account')
        ];
        $model = $this->convertToModel($userAccount, $this->userModel);
        $data = $this->authService->activateAccount($model);
        return $response->withJson($data);
    }
    /**
     * validate change password url
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function validateChangeRequestURL(Request $request, Response $response){
        $data = $this->authService->validateChangeRequestURL($request);
        return $response->withJson($data);
    }

}
