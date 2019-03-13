<?php

namespace App\Service\Users;

use App\Service\BaseService;
use App\Service\Domain\CUserDomain;
use App\Helper\Messages\CAuthMessages;
use App\Helper\Messages\CUserMessages;
use App\Helper\Messages\CActivityMessages;
use App\Transformer\Users\CUserTransformer;
use App\Transformer\Users\CFranchiseeInfoTransformer;
use App\Transformer\Users\CPersonalInfoTransformer;
use App\Transformer\Users\CBackgroundInfoTransformer;
use App\Transformer\Users\CAccountInformationTransformer;
use App\Transformer\Users\CSBUManagerTransformer;
use App\Service\Auth\CAuthService;
use App\Helper\CCommon;
use App\Transformer\Users\CExistingApplication;
use App\Helper\Mailer\CMailer;

class CUserService extends BaseService
{
    /**
     * CUser business logic
     *
     * @var \App\Service\CUserDomain
     */
    private $userDomain;

    /**
     * Transformer for search user response
     *
     * @var \App\Transformer\Users\CUserTransformer
     */
    private $userTransformer;

    /**
     * Transformer for background info response
     *
     * @var \App\Transformer\Users\CBackgroundInfoTransformer
     */
    private $backgroundInfoTransformer;
    /**
     * existing application transformer
     *
     * @var \App\Transformer\Users\CExistingApplication;
     */
    private $existingApplicationTransformer;
    /**
     * sbu manager transformer
     *
     * @var \App\Transformer\Users\SBUManagerTransformer;
     */
    private $sbuManagerTransformer;

    /**
     * Create a new CUserService instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userDomain = new CUserDomain();
        $this->userTransformer = new CUserTransformer();
        $this->franchiseeTransformer = new CFranchiseeInfoTransformer();
        $this->personalInfoTransformer = new CPersonalInfoTransformer();
        $this->backgroundInfoTransformer = new CBackgroundInfoTransformer();
        $this->accountInformationTransformer = new CAccountInformationTransformer();
        $this->existingApplicationTransformer = new CExistingApplication;
        $this->authService = new CAuthService();
        $this->sbuManagerTransformer = new CSBUManagerTransformer();
    }
    public function fetchExistingApplication($uid){
        $user = $this->userDomain->fetchExistingApplication($uid);
        if(!$user){
            return $this->returnWithErrors(CUserMessages::ACCOUNT_NOT_EXIST);
        }
        return $this->returnSuccess(
            [
                'users' => $this->existingApplicationTransformer->transform($user)
            ]
        );
    }
    /**
     * Search users
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * 
     * @return array
     */
    public function searchUsers($model)
    {

        $users = $this->transformWithPagination(
            $this->userTransformer,
            $this->userDomain->searchUsers($model)
        );

        return $this->returnSuccess(
            [
                'users' => $users
            ]
        );
    }

    /**
     * Get specific user
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * 
     * @return array
     */
    public function getUser($id)
    {
       $user = $this->userDomain->getUserById($id,'positions');

       return $this->returnSuccess(
            [
                'user' => $this->userTransformer->transform($user)
            ]
       );
    }

    /**
     * Get all managers
     *
     * @param 
     *
     * @return user list
     */
    public function getSBUManagers($sbus)
    {
        $managers = $this->userDomain->getSBUManagers($sbus);
        $franchisingHead = $this->userDomain->getFranchisingHead($sbus);
        if(!$managers){
            return $this->returnWithErrors(CActivityMessages::MANAGERS_ERROR);
        }
        
        return $this->returnSuccess(
            [
                'franchisingHead' => $this->sbuManagerTransformer->transformCollection($franchisingHead->toArray()),
                'managers' => $this->sbuManagerTransformer->transformCollection($managers->toArray())
            ]
        );     
    }
    /**
     * Get specific user with personal info
     *
     * @param int $id
     * 
     * @return array
     */
    public function getFranchiseeInfo($id)
    {        
        $user = $this->userDomain->getFranchiseeInfo($id);

        if(!$user){
            return $this->returnWithErrors(CAuthMessages::ACCOUNT_ID);
        }

        if(!$this->userDomain->isUserFranchisee($user)){
            return $this->returnWithErrors(CUserMessages::USER_NOT_FRANCHISEE);
        }

        return $this->returnSuccess(
            [
                'franchisee' => $this->franchiseeTransformer->transform($user)
            ]
        );
    }

    /**
     * Get background info of specific user
     *
     * @param int $id
     * 
     * @return array
     */
    public function getLatestBackgroundInfo($id)
    {        
        $user = $this->userDomain->getLatestBackgroundInfo($id);

        if(!$user){
            return $this->returnWithErrors(CAuthMessages::ACCOUNT_ID);
        }

        if(!$this->userDomain->isUserFranchisee($user)){
            return $this->returnWithErrors(CUserMessages::USER_NOT_FRANCHISEE);
        }

        return $this->returnSuccess(
            [
                'franchiseeBackgroundInfo' => $this->backgroundInfoTransformer->transform($user->latestBackgroundInfo)
            ]
        );
    }

    /**
     * Add new user
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * 
     * @return array
     */
    public function addUser($model)
    {
        $model['password'] = $this->convertPassword(
            $this->getDefaultPassword()
        );

        if ($this->userDomain->checkEmailExist($model['email'])) {
            return $this->returnWithErrors(CUserMessages::ACCOUNT_EXIST);
        }

        $user = $this->userDomain->addUser($model);
        if ($user == null) {
            return $this->returnWithErrors(CUserMessages::USER_NOT_ADDED);
        }
        $user = $this->userTransformer->transform($user);

        if (!$this->isLocal()) {
            $mailer = new CMailer($user['userEmail'],null,"addUser",["employeeName"=>["firstName"=>$user['userFirstName'],"middleName"=>$user['userMiddleName'],"lastName"=>$user['userLastName']],
                "accountType"=>$user['userRoles']['title'],
                "sbu"=>$user['userSbus'][0]['title'],
                "position"=>$user['userPosition']['title']]);

            $isEmailSend = $mailer->composeMessage();

            if (!$isEmailSend) return $this->returnWithErrors(CAuthMessages::EMAIL_NOT_SEND);
        }

        return $this->returnSuccess(
                [
                    'user' => $user
                ]
        );
    }

    /**
     * Update user
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * 
     * @return array
     */
    public function updateUser($model)
    {
        
       $user = $this->userDomain->updateUser($model);
    
       if ($user == null) {
        return $this->returnWithErrors(CUserMessages::USER_NOT_UPDATED);
       }
       $user = $this->userTransformer->transform($user);
       
        if (!$this->isLocal()) {
            $mailer = new CMailer($user['userEmail'],null,"updateUser",["employeeName"=>["firstName"=>$user['userFirstName'],"middleName"=>$user['userMiddleName'],"lastName"=>$user['userLastName']],
                "accountType"=>$user['userRoles']['title'],
                "sbu"=>$user['userSbus'][0]['title'],
                "position"=>$user['userPosition']['title']]);

            $isEmailSend = $mailer->composeMessage();

            if (!$isEmailSend) return $this->returnWithErrors(CAuthMessages::EMAIL_NOT_SEND);
        }

       return $this->returnSuccess(
            [
                'user' => $user
            ]
       );
    }

    /**
     * Archive user
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * 
     * @return array
     */
    public function archivedUser($id)
    {
        $id = $this->userDomain->archiveUser($id);
        
        if ($id == null) {
            return $this->returnWithErrors(CUserMessages::USER_NOT_ARCHIVED);
        }

        $user = $this->userDomain->getArchiveUser($id);

        if (!$this->isLocal()) {
            $mailer = new CMailer($user->email,null,"deactivateUser",["employeeName"=>["firstName"=>$user->first_name,"middleName"=>$user->middle_name,"lastName"=>$user->last_name]]);
            $isEmailSend = $mailer->composeMessage();

            if (!$isEmailSend) return $this->returnWithErrors(CAuthMessages::EMAIL_NOT_SEND);
            
        }

        return $this->returnSuccess(
             [
                 'id' => $id
             ]
        );
    }
    

     /**
     * Update account information
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * 
     * @return array
     */
    public function updateAccountInformation($model,$oldPassword)
    {
       $user = $this->userDomain->updateAccountInformation($model);
    
       if($user AND $oldPassword){
            if(password_verify(base64_decode($oldPassword), $user->password)){
                $message = $this->authService->changePassword($model);
                
            }else{
                return $this->returnWithErrorsData(CUserMessages::PASSWORD_COMBINATION_INCORRECT,[ 'user' => $this->accountInformationTransformer->transform($user)]);
            }
            if($message['success'] == false){
               return $this->returnWithErrorsData($message['errors'],[ 'user' => $this->accountInformationTransformer->transform($user)]);
            }
       }

       if (!$user) {
        return $this->returnWithErrorsData(CUserMessages::USER_NOT_UPDATED, [ 'user' => $this->accountInformationTransformer->transform($user)]);
       }
       return $this->returnSuccess(
            [
                'user' => $this->accountInformationTransformer->transform($user)
            ]
       );
    }

    /**
     * Search archived users
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * 
     * @return array
     */
    public function searchArchivedUsers($model)
    {

        $users = $this->transformWithPagination(
            $this->userTransformer,
            $this->userDomain->searchArchivedUsers($model)
        );

        return $this->returnSuccess(
            [
                'archivedUsers' => $users
            ]
        );
    }

    /**
     * Search archived users
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * 
     * @return array
     */
    public function activateArchivedUser($userId)
    {
       $user = $this->userDomain->activateArchivedUser($userId);
      
       if ($user == null) {
        return $this->returnWithErrors(CUserMessages::USER_NOT_REACTIVATED);
       }

       $user =$this->userTransformer->transform($user);

       if (!$this->isLocal()) {
            $mailer = new CMailer($user['userEmail'],null,"activateUser",["employeeName"=>["firstName"=>$user['userFirstName'],"middleName"=>$user['userMiddleName'],"lastName"=>$user['userLastName']]]);
            $isEmailSend = $mailer->composeMessage();

            if (!$isEmailSend) return $this->returnWithErrors(CAuthMessages::EMAIL_NOT_SEND);
       }

       return $this->returnSuccess(
            [
                'user' => $user
            ]
       );
    }

    /**
     * Check if the user is active
     *
     * @param Integer user id
     * 
     * @return array
     */
    public function checkUserStatus($userId)
    {
        $user = $this->userDomain->checkUserStatus($userId);
        // var_dump($user);die();
       if ($user === null) {
        return CAuthMessages::USER_STATUS_ARCHIVED;
       }else{
           return CAuthMessages::USER_STATUS_ACTIVE;
       }
    }
}