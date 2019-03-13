<?php

namespace App\Service\Domain;

use App\Helper\CCommon;
use App\Repository\Auth\CAuthRepository;
use App\Repository\Users\CUserRepository;

class CUserDomain
{
    /**
     * Auth module repository
     *
     * @var \App\Repository\Auth\CAuthRepository
     */
    private $authRepository;

    /**
     * User module repository
     *
     * @var \App\Repository\Users\CUserRepository
     */
    private $userRepository;

    /**
     * Create a new CUserDomain instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authRepository = new CAuthRepository();
        $this->userRepository = new CUserRepository();
    }
    /**
     * update userPassword
     *
     * @param \App\Model\CUser $user
     * @return void
     */
    public function updatePassword(Object $user)
    {
        return $this->authRepository->updatePassword($user);
    }

    /**
     * Get user by id
     *
     * @param Int $id
     * @return Array
     */
    public function getUserById(Int $id)
    {
        return $this->authRepository->getUserById($id);
    }

    public function getArchiveUser($id){
        return $this->authRepository->getArchiveUser($id);
    }

    /**
     * Get the password log of the specific account
     *
     * @param Int $id
     * @return Array
     */
    public function getPasswordLog(Int $id)
    {
        return $this->authRepository->getPasswordLog($id);
    }

    /**
     * Get user by email
     *
     * @param string $email
     *
     * @return array
     */
    public function getUserByEmail($email)
    {
        return $this->authRepository->getUserByEmail($email);
    }

    /**
     * Get user by email excluding archived users
     *
     * @param string $email
     *
     * @return array
     */
    public function getLoginUserByEmail($email)
    {
        return $this->authRepository->getLoginUserByEmail($email);
    }

    /**
     * Register a new user
     *
     * @param \App\Model\CUser $user
     *
     * @return array
     */
    public function registerUser($user)
    {
      var_dump($user);

        return $this->authRepository->registerUser($user);
    }
    /**
     * Activate account
     *
     * @param String $account
     * @return void
     */
    public function activateAccount($id)
    {
        return $this->authRepository->activateAccount($id);
    }
    /**
     * Delete the oldest password
     *
     * @param Int $id
     *
     * @return void
     */
    public function deleteOldPassword(Int $id)
    {
        return $this->authRepository->deleteOldPassword($id);
    }

    /**
     * Check if email exist
     *
     * @param string $email
     *
     * @return array
     */
    public function checkEmailExist($email)
    {
        return $this->authRepository->getUserByEmail($email) == null ? false : true;
    }

    /**
     * Search user by user model
     *
     * @param \App\Model\CUser $user
     *
     * @return array
     */
    public function isUserFranchisee($user)
    {
        return $this->userRepository->hasRole($user, CCommon::FRANCHISEE_ROLE);
    }

    /**
     * Search user by user model
     *
     * @param \App\Model\CUser $user
     *
     * @return array
     */
    public function searchUsers($user)
    {
        return $this->userRepository->searchUsers($user);
    }

    /**
     * Get user personal info by id
     *
     * @param int $userId
     *
     * @return App\Model\User
     */
    public function getFranchiseeInfo($userId)
    {
        return $this->userRepository->getUserById($userId, ['personalInfo.personalAddress']);
    }

    /**
     * Get latest background info of the user using id
     *
     * @param int $userId
     *
     * @return App\Model\BackgroundInfo
     */
    public function getLatestBackgroundInfo($userId)
    {
        return $this->userRepository->getUserById($userId,
            [
                'latestBackgroundInfo.familyMembers',
                'latestBackgroundInfo.backgroundQuestions',
                'latestBackgroundInfo.educationalBackgrounds',
                'latestBackgroundInfo.trainingExperiences',
                'latestBackgroundInfo.workExperiences',
                'latestBackgroundInfo.references',
                'latestBackgroundInfo.backgroundAttachments',
                'latestBackgroundInfo.businessExperiences.city.province',
            ]
        );
    }

    /**
     * Add new user
     *
     * @param \App\Model\CUser $user
     *
     * @return \App\Model\CUser $user
     */
    public function addUser($user)
    {
        return $this->userRepository->addNewUser($user);
    }

    /**
     * Update user
     *
     * @param \App\Model\CUser $user
     *
     * @return \App\Model\CUser $user
     */
    public function updateUser($user)
    {
        return $this->userRepository->updateUser($user);
    }

    public function archiveUser($id)
    {
        return $this->userRepository->archiveUser($id);
    }

    public function activateArchivedUser($id)
    {
        return $this->userRepository->activateArchivedUser($id);
    }

    /**
     * Update account information
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    public function updateAccountInformation($model)
    {

        return $this->userRepository->updateAccountInformation($model);
    }

    /**
     * Search archived user by user model
     *
     * @param \App\Model\CUser $user
     *
     * @return array
     */
    public function searchArchivedUsers($model)
    {
        return $this->userRepository->searchArchivedUsers($model);
    }
    /**
     * Insert Avatar for specific user
     *
     * @param Array $data
     * @return Boolean
     */
    public function insertAvatar($data)
    {
        return $this->userRepository->insertAvatar($data);
    }
    /**
     * reset password
     *
     * @param array $data
     * @return void
     */
    public function resetPassword(array $data)
    {
        return $this->authRepository->resetPassword($data);
    }
    public function getChangePasswordRequest($uid)
    {
        return $this->authRepository->getChangePasswordRequest($uid);
    }
    public function deleteChangePassword($uid)
    {
        return $this->authRepository->deleteChangePassword($uid);
    }
    public function getUserId($data){
        return $this->userRepository->getUserId($data);
    }
    public function fetchExistingApplication($uid){
        return $this->userRepository->fetchExistingApplication($uid);
    }

    /**
     * get all user managers
     *
     * @param
     * @return void
     */
    public function getSBUManagers($sbus)
    {
        return $this->userRepository->getSBUManagers($sbus);
    }

    /**
     * get all franchising head
     *
     * @param
     * @return void
     */
    public function getFranchisingHead($sbus)
    {
        return $this->userRepository->getFranchisingHead($sbus);
    }

    /**
    * Checks the user's status
    *
    * @param
    * @return void
    */
   public function checkUserStatus($userId)
   {
       return $this->userRepository->checkUserStatus($userId);
   }
   /**
    * validate the change password url
    *
    * @param Object $model
    * @return void
    */
   public function checkChangePassword( $model){
    return $this->userRepository->checkChangePassword($model);
   }
}
