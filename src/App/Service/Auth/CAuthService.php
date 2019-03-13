<?php

namespace App\Service\Auth;

use DateTime;
use App\Helper\CCiper;
use App\Helper\Mailer\CMailer;
use App\Helper\Messages\CAuthMessages;
use App\Model\User;
use App\Service\BaseService;
use App\Service\Domain\CUserDomain;
use App\Transformer\Auth\CLoginTransformer;
use App\Helper\CUserFactory;
use App\Helper\CCommon;
use Carbon\Carbon;
// use App\Helper\cs3Manger;
class CAuthService extends BaseService
{
    /**
     * CUser business logic
     *
     * @var \App\Service\CUserDomain
     */
    private $userDomain;

    /**
     * encryption & decryption
     *
     * @var \App\Helper\CCiper
     */
    private $ciper;

    /**
     * Transformer for login response
     *
     * @var \App\Transformer\Auth\CLoginTransformer
     */
    private $loginTransformer;
    /**
     * S3 manager
     *
     * @var \App\Helper\cs3Manger
     */
    private $s3;

    /**
     * Create a new CAuthService instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userDomain = new CUserDomain();
        $this->loginTransformer = new CLoginTransformer();
        $this->ciper = new CCiper();
        // $this->s3 =  new cs3Manger();
    }

    /**
     * Logged in the user
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    public function login($model)
    {
        $user = $this->userDomain->getLoginUserByEmail(
            $model->getAttributes()['email']
        );

        if (!$user) {
            return $this->returnWithErrors(CAuthMessages::ACCOUNT_INVALID);
        }

        if (is_null($user->is_verified)) {
            return $this->returnWithErrors(CAuthMessages::INACTIVE_ACCOUNT);
        }

        if ($user->is_verified==0) {
            return $this->returnWithErrors(CAuthMessages::INACTIVE_ACCOUNT);
        }

        if (!password_verify($model->getAttributes()['password'], $user->password)) {
            return $this->returnWithErrors(CAuthMessages::ACCOUNT_INVALID);
        }

        $user->token = $this->generateToken($user);

        $profileImage =null;
        // if( getenv('APP_ENV') !== 'local' ){
        //   if(is_null($user->profile_pic)){
        //       $profileImage = $this->s3->getFile('primarypic.png');
        //   }else{
        //       $profileImage = $this->s3->getFile($user->profile_pic);
        //   }
        // }

        $user['profileImage']= $profileImage;

        return $this->returnSuccess(
            [
                'user' => $this->loginTransformer->transform($user),
            ]
        );
    }

    /**
     * Register a new user
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    public function register($model)
    {
        $model['password'] = $this->convertPassword(
            $model['password']
        );

        if ($this->userDomain->checkEmailExist($model['email'])) {
            return $this->returnWithErrors(CAuthMessages::ACCOUNT_EXIST);
        }

        $user = $this->userDomain->registerUser(
            $model
        );

        if (!$this->isLocal()) {
            $url = env('URL') . $this->ciper->cipher('encrypt', $model['email']) . '';

            $mailer = new CMailer($user['email'], $url);
            $isEmailSend = $mailer->composeMessage();

            if (!$isEmailSend) {
                return $this->returnWithErrors(CAuthMessages::EMAIL_NOT_SEND);
            }
        }

        return $this->returnSuccess(
            [
                'message' => [CAuthMessages::SUCCESS_REGISTER],
            ]
        );
    }

    /**
     * Generate a new JWT token
     *
     * @param \App\Models\CUser $user
     *
     * @return string
     */
    public function generateToken(User $user)
    {
        $appUrl = getenv('APP_URL');
        $appSecret = getenv('JWT_SECRET');

        $now = new DateTime();
        $future = new DateTime("now +1 hours");

        $roles = [];
        foreach ($user->roles as $role) {
            array_push($roles, $role->id);
        }

        $payload = [
            'iat' => $now->getTimeStamp(),
            'exp' => $future->getTimeStamp(),
            'jti' => base64_encode(random_bytes(16)),
            'iss' => $appUrl, // Issuer
            'user' => [
                'userId' => $user->id,
                'userFirstName' => $user->first_name,
                'userMiddleName' => $user->middle_name,
                'userLastName' => $user->last_name,
                'userEmail' => $user->email,
                'userPosition' => $user->positions,
                'userRoles' => $roles,
            ],
        ];
        $token = \Firebase\JWT\JWT::encode($payload, $appSecret, "HS256");
        return $token;
    }

    /**
     * reset Password
     *
     * @param \App\Models\CUser $user
     * @return Array
     */
    public function resetPassword(User $user)
    {
        $isEmailExist = $this->userDomain->getUserByEmail(
            $user['email']
        );

        if (empty($isEmailExist)) {
            return $this->returnWithErrors(CAuthMessages::ACCOUNT_NOT_FOUND);
        }
        $user_id = $isEmailExist->id;
        $currentDate = Carbon::now();
        $valid_until = $currentDate->addHours(24);
        $dataEncryption = $this->ciper->cipher(CCommon::ENCRYPT, ''.$valid_until.'');
        $payload = [
            'user_id'=> $user_id,
            'valid_at'=> $valid_until,
            'encryption' => $dataEncryption
        ];

        $restPassword = $this->userDomain->resetPassword($payload);
        if(is_null($restPassword)){
            return $this->returnWithErrors(CAuthMessages::ACCOUNT_NOT_FOUND);
        }

        if (!$this->isLocal()) {
            $encryption = $user_id.'+'.$dataEncryption;
            $url = env("CHANGE_PASSWORD_URL") . $this->ciper->cipher(CCommon::ENCRYPT, $encryption) . '';
            $mailer = new CMailer($user['email'], $url, 'reset');
            $isEmailSend = $mailer->composeMessage();

            if (!$isEmailSend) {
                return $this->returnWithErrors(CAuthMessages::EMAIL_NOT_SEND);
            }
        }

        return [
            'success' => true,
            'message' => [
                CAuthMessages::SUCCESS_SEND,
            ],
            'data' => [
                "link" => $url,
            ],
        ];
    }
    /**
     * validate change password request
     *
     * @param \App\Models\CUser $model
     * @return Array
     */
    public function validatePasswordRequest($model){
        $validityId = $this->userDomain->getChangePasswordRequest($model);
        if (is_null($validityId)) {
            return $this->returnWithErrors(CAuthMessages::REST_PASSWORD_LINK_EXPIRED);
        }

        return [
            'success' => true,
            'resetPasswordId'=> $validityId,
            'message' => [
                CAuthMessages::REST_PASSWORD_LINK,
            ],
        ];
    }

    /**
     * change password
     *
     * @param \App\Models\CUser $model
     * @return Array
     */
    public function changePassword(User $model)
    {

        $passwordLog_container = [];
        $user = $this->userDomain->getUserById(
            $model['id']
        );

        if (empty($user)) {
            return $this->returnWithErrors(CAuthMessages::ACCOUNT_ID);
        }


        $passwordLog = $this->userDomain->getPasswordLog(
            $model['id']
        );
        foreach ($passwordLog as $log) {
            $isPasswordExist = (password_verify(base64_decode($model['password']), $log->password)) ? true : false;
            array_push($passwordLog_container, $isPasswordExist);
        }



        if (in_array(true, $passwordLog_container)) {
            return $this->returnWithErrors(CAuthMessages::PASSWORD_EXIST);
        }

        if (count($passwordLog) >= 3) {
            $this->userDomain->deleteOldPassword($model['id']);
        }

        $model['password'] = $this->convertPassword(
            $model['password']
        );

        $validateChangePassword = $this->userDomain->checkChangePassword($model);
        if(!$validateChangePassword){
            return $this->returnWithErrors(CAuthMessages::REST_PASSWORD_LINK_EXPIRED);
        }

        $updatePassword = $this->userDomain->updatePassword($model);
        $deleteChangePassword =  $this->userDomain->deleteChangePassword($model->id);

        return [
            'success' => true,
            'message' => [
                CAuthMessages::SUCCESS_UPDATED,
            ],
        ];
    }

    public function activateAccount($model){
        $cipher = new CCiper();

        $emailAccount = $cipher->cipher('decrypt',$model['email']);

        if (empty($emailAccount)) {
            return $this->returnWithErrors(CAuthMessages::ACCOUNT_NOT_FOUND);
        }

        $isEmailExist = $this->userDomain->getUserByEmail(
            $emailAccount
        );
        if($isEmailExist->is_verified === 1){
            return $this->returnWithErrors(CAuthMessages::VERIFICATION_EXPIRED);
        }
        $user_id = $isEmailExist->id;
        $updatePassword = $this->userDomain->activateAccount($user_id);
        switch ($updatePassword) {
            case 0:
                return $this->returnWithErrors(CAuthMessages::VERIFICATION_EXPIRED);
                break;
            case 1:
                $msg = CAuthMessages::SUCCESS_ACTIVATED;
                break;
            default:
                return $this->returnWithErrors(CAuthMessages::ACCOUNT_NOT_FOUND);
                break;
        }
        return [
            'success' => true,
            'message' => [
                $msg,
            ],
        ];

    }

    public function validateChangeRequestURL($request){
        $token = $request->getAttribute('token');
        $ciper = new CCiper();
        $key=explode('+',$ciper->cipher('decrypt', $token));
        $model = [
            "id" => $key[0],
            "encryption" => $key[1]
        ];
        $validateChangePassword = $this->userDomain->checkChangePassword($model);
        if(!$validateChangePassword){
            return $this->returnWithErrors(CAuthMessages::REST_PASSWORD_LINK_EXPIRED);
        }

        return [
            'success' => true,
            'message' => [
                CAuthMessages::REST_PASSWORD_LINK,
            ],
        ];

    }
}
