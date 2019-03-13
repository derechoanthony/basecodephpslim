<?php

namespace App\Repository\Auth;

use App\Model\Password;
use App\Model\User;
use Illuminate\Database\Capsule\Manager as DB;
use App\Repository\Users\CUserRepository;
use App\Model\UserEditTrail;
use App\Helper\CCommon;
use App\Helper\CUserFactory;
use App\Model\ChangePassword;
use App\Model\ActivationValidity;
use Carbon\Carbon;

class CAuthRepository
{
    /**
     * Create a new CAuthRepository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userRepository = new CUserRepository();
    }

    /**
     * Get the user by email
     *
     * @param string $email
     *
     * @return boolean|\App\Models\User
     */
    public function getUserByEmail($email)
    {
        return User::with('positions')->withTrashed()->where('email', $email)->first();
    }

    /**
     * Get the user by email excluding archived users
     *
     * @param string $email
     *
     * @return boolean|\App\Models\User
     */
    public function getLoginUserByEmail($email)
    {
        return User::with('positions')->where('email', $email)->first();
    }

    /**
     * Get User by id
     *
     * @param Int $id
     * @return boolean|\App\Models\User
     */
    public function getUserById(Int $id)
    {
        try {
            return User::where('id', $id)->first();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getArchiveUser($id){
        try {
            return User::withTrashed()->where('id', $id)->first();
        } catch (Exception $e) {
            return $e;
        }
    }
    /**
     * Update user password
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function updatePassword($user)
    {
        try {
            DB::beginTransaction();
            $userModel = User::find($user->id);
            $userModel->password = $user->password;
            $userModel->save();

            $passwordLog = new Password;
            $passwordLog->user_id = $userModel->id;
            $passwordLog->password = $user->password;
            $passwordLog->save();

            if($userModel){
            CUserFactory::setId($userModel->id);
            $editTrail = new UserEditTrail();
            $editTrail->user_id = $userModel->id;
            $editTrail->action = CCommon::EDIT_TRAIL_UPDATE;
            $editTrail->remarks = CCommon::EDIT_TRAIL_UPDATED_REMARKS;
            $this->userRepository->addUserEditTrail($editTrail);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Delete the oldest password in the `password_log` table
     *
     * @param Int $user_id
     *
     */
    public function deleteOldPassword(Int $user_id)
    {
        $oldPassword = Password::where('user_id', $user_id)->first();
        $oldPassword->delete();
    }

    /**
     * Get all the password log of the specific id
     *
     * @param Int $id
     * @return  boolean|\App\Models\Password
     */
    public function getPasswordLog(Int $id)
    {
        return Password::where('user_id', $id)->get();
    }

    /**
     * Register a new user
     *
     * @param \App\Models\User $user
     *
     * @return boolean
     */
    public function registerUser($user)
    {
        $userRegistration = null;

        try {
            DB::beginTransaction();
            $userRegistration = new User;
            $userRegistration->first_name = $user->first_name;
            $userRegistration->middle_name = $user->middle_name;
            $userRegistration->last_name = $user->last_name;
            $userRegistration->email = $user->email;
            $userRegistration->password = $user->password;
            $userRegistration->position_id = CCommon::USER_POSITION_ID;
            $userRegistration->save();

            $userRegistration->roles()->attach(5);
            $passwordLog = new Password;
            $passwordLog->user_id = $userRegistration->id;
            $passwordLog->password = $user->password;
            $passwordLog->save();

            if($userRegistration){
                CUserFactory::setId($userRegistration->id);
                $editTrail = new UserEditTrail();
                $editTrail->user_id = $userRegistration->id;
                $editTrail->action = CCommon::EDIT_TRAIL_ADDED;
                $editTrail->remarks = CCommon::EDIT_TRAIL_ADDED_REMARKS;
                $this->userRepository->addUserEditTrail($editTrail);

                $activateValidity = new ActivationValidity();
                $activateValidity->user_id = $userRegistration->id;
                $activateValidity->valid_at = Carbon::now()->addHours(24);
                $activateValidity->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return $userRegistration;
    }

    public function activateAccount($id){
        if($id !== null){
            $user = User::find($id);
            if($user !== null && $user->is_verified === null){
                $validity = ActivationValidity::where('user_id', $id)->first();
                if ($validity->valid_at->greaterThanOrEqualTo(Carbon::now())) {
                    $userModel = User::find($id);
                    $userModel->is_verified = 1;
                    $userModel->save();

                    $validity->deleted_at = Carbon::now();
                    $validity->save();
                    return $userModel->is_verified;
                } else {
                    return 0;
                }
            }else{
                return $user->is_verified;
            }
        }
        return 2;
    }
    public function getChangePasswordRequest($model){
        $validity = ChangePassword::where('user_id', $model['id'])
            ->where('encryption', $model['encryption'])->first();


        if(!$validity){
            return NULL;
        }
        return $validity->valid_at->greaterThanOrEqualTo(Carbon::now()) ? $validity->id : NULL;
    }
    public function deleteChangePassword($uid){
        $changepwd = ChangePassword::select('id')->where('user_id', $uid);
        $changepwd->delete();
        return $changepwd;
    }
    public function resetPassword($data){
        $changepwd = ChangePassword::select('id')->where('user_id',$data['user_id']);
        $changepwd->delete();
        $changePasswordModel = new ChangePassword();
        $changePasswordModel->user_id = $data['user_id'];
        $changePasswordModel->valid_at = $data['valid_at'];
        $changePasswordModel->encryption = $data['encryption'];
        $changePasswordModel->save();
        return $changePasswordModel->id;
    }
}
