<?php

namespace App\Repository\Users;

use App\Model\User;
use App\Model\UserEditTrail;
use Illuminate\Database\Capsule\Manager as DB;
use App\Helper\CCommon;
// use App\Model\sbu;
use App\Helper\CUserFactory;
use \Datetime;
use App\Model\PersonalInfo;
use App\Model\ChangePassword;

class CUserRepository
{
    /**
     * Create a new CAuthRepository instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Filter users
     *
     * @param \App\Models\User $user
     *
     * @return array
     */
    public function searchUsers($user)
    {
        $users = User::with(['sbus','rbus','roles','positions','latestCreate.createdBy','latestCreate' => function ($q){
                            $q->editTrail('CREATE');
                        },'latestUpdate.createdBy','latestUpdate'=> function ($q){
                            $q->editTrail('UPDATE');
                        }])
                        ->where('is_verified',1)
                        ->where('id', '!=', CUserFactory::getId());
         if($user->first_name){
             $users = $users->where('first_name','LIKE',"%{$user->first_name}%");
         }

         if($user->last_name){
             $users = $users->where('last_name','LIKE',"%{$user->last_name}%");
         }

         if($user->email){
             $users = $users->where('email','LIKE',"%{$user->email}%");
         }

         if($user->position_id){
            $users = $users->where('position_id' ,$user->position_id);
         }

         if($user->user_roles){
             $filterRoles = $user->user_roles;
             $users = $users->whereHas('roles', function ($q) use ($filterRoles) {
               $q->whereIn('id',$filterRoles);
            });
         }

         if($user->user_sbus){
            $filterSbus = $user->user_sbus;
             $users = $users->whereHas('sbus', function ($q) use ($filterSbus) {
               $q->whereIn('id',$filterSbus);
            });
         }
        if($user->user_rbus){
            $filterRbus = $user->user_rbus;
             $users = $users->whereHas('rbus', function ($q) use ($filterRbus) {
               $q->whereIn('id',$filterRbus);
            });
         }
         return $users->paginate(CCommon::ITEMS_PER_PAGE, null, null, $user->page);
    }

    /**
     * Get user by ID
     *
     * @param int $id
     *
     * @return \App\Models\User $user
     */
    public function getUserById($id, $with = [])
    {
        return User::with($with)->find($id);
    }

    /**
    * Get all user managers
    *
    * @param
    *
    * @return Collection<Position>
    */
    /**
     * Get sbu id's
     *
     * @param
     *
     * @return sbu list
     */
    public function getAllSbus()
    {
        $sbus = sbu::get(['id']);

        return $sbus;
    }

   public function getSBUManagers($sbus)
   {
        if(!$sbus){
            $sbus = $this->getAllSbus();
        }
       return User::with('positions','sbus')
                            ->where('position_id',CCommon::FRANCHISING_MANAGER_POSITION_ID)
                            ->whereHas('sbus', function ($q) use ($sbus){
                                $q->whereIn('id', $sbus);
                            })->get();
   }

   public function getFranchisingHead($sbus)
   {
        if(!$sbus){
            $sbus = $this->getAllSbus();
        }
       return User::with('positions','sbus')
                            ->whereIn('position_id',CCommon::FRANCHISING_HEAD_POSITION_ID)
                            ->whereHas('sbus', function ($q) use ($sbus){
                                $q->whereIn('id', $sbus);
                            })->get();
   }

    /**
     * Get roles of this user
     *
     * @param \App\Models\User $user
     * @param int $role_id
     *
     * @return Collection<\App\Model\Role>
     */
    public function hasRole($user, $role_id)
    {
        // TODO: Please find better method than roles->contains, try to use Query Builder
        return $user->roles->contains('id', $role_id);
    }

    /**
     * Add new user
     *
     * @param \App\Models\User $user
     *
     * @return \App\Models\User $user
     */
    public function addNewUser($user)
    {
        $newUser = null;

        try {
            DB::beginTransaction();
            $newUser = new User;
            $newUser->first_name = $user->first_name;
            $newUser->middle_name = $user->middle_name;
            $newUser->last_name = $user->last_name;
            $newUser->email = $user->email;
            $newUser->password = $user->password;
            $newUser->position_id = $user->position_id;
            $newUser->is_verified = 1;
            $newUser->save();

            if($newUser){
                $editTrail = new UserEditTrail();
                $editTrail->user_id = $newUser->id;
                $editTrail->action = CCommon::EDIT_TRAIL_ADDED;
                $editTrail->remarks = CCommon::EDIT_TRAIL_ADDED_REMARKS;
                $this->addUserEditTrail($editTrail);
            }
            $this->addPivotTable($newUser,$user);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return $this->getUserById($newUser->id,['positions']);
    }

    public function addPivotTable($newUser,$user)
    {
        foreach($user->user_rbus as $rbu){
            $newUser->rbus()->attach($rbu);
        }
        foreach($user->user_sbus as $sbu){
            $newUser->sbus()->attach($sbu);
        }
        if($user->user_roles){
            foreach($user->user_roles as $role){
                $newUser->roles()->attach($role);
            }
        }
    }

    /**
     * Update user
     *
     * @param \App\Models\User $user
     *
     * @return \App\Models\User $user
     */
    public function updateUser($user)
    {
        $updateUser = null;

        try {
            DB::beginTransaction();
            $updateUser = User::find($user->id);
            $updateUser->first_name = $user->first_name;
            $updateUser->middle_name = $user->middle_name;
            $updateUser->last_name = $user->last_name;
            if($updateUser->position_id !== CCommon::FRANCHISEE_POSITION_ID){
                $updateUser->position_id = $user->position_id;
            }
            $updateUser->save();

            if($updateUser){
                $editTrail = new UserEditTrail();
                $editTrail->user_id = $updateUser->id;
                $editTrail->action = CCommon::EDIT_TRAIL_UPDATE;
                $editTrail->remarks = CCommon::EDIT_TRAIL_UPDATED_REMARKS;
                $this->addUserEditTrail($editTrail);
            }
            $updateUser->rbus()->detach();
            $updateUser->sbus()->detach();
            $this->addPivotTable($updateUser,$user);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return $this->getUserById($updateUser->id,['positions']);
    }

    /**
     * Archive user
     *
     * @param int $id
     *
     * @return int $id
     */
    public function archiveUser($id)
    {
        $archivedId = null;

        try {
            DB::beginTransaction();

            $user = User::find($id);
            $user->delete();
            $archivedId = $user->id;

            if($archivedId){
                $editTrail = new UserEditTrail();
                $editTrail->user_id = $archivedId;
                $editTrail->action = CCommon::EDIT_TRAIL_UPDATE;
                $editTrail->remarks = CCommon::EDIT_TRAIL_ARCHIVE_REMARKS;
                $this->addUserEditTrail($editTrail);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return $archivedId;
    }

    /**
     * search archived users
     *
     * @param \App\Models\User $user
     *
     * @return array
     */
    public function searchArchivedUsers($user)
    {
        $users = User::with(['sbus','rbus','roles','positions','latestCreate.createdBy','latestCreate' => function ($q){
                            $q->editTrail('CREATE');
                        },'latestUpdate.createdBy','latestUpdate'=> function ($q){
                            $q->editTrail('UPDATE');
                        }])->onlyTrashed()->where('is_verified',1);
        if($user->first_name){
            $users = $users->where('first_name','LIKE',"%{$user->first_name}%");
        }

        if($user->last_name){
            $users = $users->where('last_name','LIKE',"%{$user->last_name}%");
        }

        if($user->email){
            $users = $users->where('email','LIKE' ,"%{$user->email}%");
        }

        if($user->position_id){
            $users = $users->where('position_id' ,$user->position_id);
        }

        if($user->user_roles){
            $filterRoles = $user->user_roles;
            $users = $users->whereHas('roles', function ($q) use ($filterRoles) {
                    $q->whereIn('id',$filterRoles);
            });
        }

        if($user->user_sbus){
            $filterSbus = $user->user_sbus;
            $users = $users->whereHas('sbus', function ($q) use ($filterSbus) {
                    $q->whereIn('id',$filterSbus);
            });
        }

        if($user->user_rbus){
            $filterRbus = $user->user_rbus;
            $users = $users->whereHas('rbus', function ($q) use ($filterRbus) {
                    $q->whereIn('id',$filterRbus);
            });
        }
        return $users->paginate(CCommon::ITEMS_PER_PAGE, null, null, $user->page);
    }

    /**
     * Reactivate archived user
     *
     * @param int $id
     *
     * @return int $id
     */
    public function activateArchivedUser($id)
    {

        try {
             DB::beginTransaction();
            $user  = User::onlyTrashed()->find($id);
            $user->restore();
            if($user){
                $editTrail = new UserEditTrail();
                $editTrail->user_id = $user->id;
                $editTrail->action = CCommon::EDIT_TRAIL_UPDATE;
                $editTrail->remarks = CCommon::EDIT_TRAIL_ACTIVATE_REMARKS;
                $this->addUserEditTrail($editTrail);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        return $user;
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
        try {
            DB::beginTransaction();

            $updateUser = User::where('id', $model->id)
                                ->update([
                                'first_name' => $model->first_name,
                                'middle_name' => $model->middle_name,
                                'last_name' => $model->last_name,
                                ]);

            $updatedUser = User::find($model->id);
            if($updatedUser){
                $editTrail = new UserEditTrail();
                $editTrail->user_id = $updatedUser->id;
                $editTrail->action = CCommon::EDIT_TRAIL_UPDATE;
                $editTrail->remarks = CCommon::EDIT_TRAIL_UPDATED_REMARKS;
                $this->addUserEditTrail($editTrail);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        return $updatedUser;
    }

    /**
     * Update account information
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    public function addUserEditTrail($editTrail)
    {
        $editTrail->created_at = new \DateTime();
        $editTrail->created_by = CUserFactory::getId();
        $editTrail->save();
        return $editTrail;
    }
    /**
     * Insert user Avatar
     *
     * @param Array $data
     * @return Boolean
     */
    public function insertAvatar($data){
        $updateUser = User::where('id', $data['id'])
            ->update([
                'profile_pic' => $data['profile_pic']
            ]);
        if(!$updateUser){
            return false;
        }
        return true;
    }

    public function getUserId($name){
        // Split each Name by Spaces
        $names = explode(",", $name);
        // Search each Name Field for any specified Name
        return User::where(function ($query) use ($names) {
            $query->whereIn('first_name', $names);

            $query->orWhere(function ($query) use ($names) {
                $query->whereIn('middle_name', $names);
            });
            $query->orWhere(function ($query) use ($names) {
                $query->whereIn('last_name', $names);
            });
        })->get();
    }
    public function fetchExistingApplication($uid){
        return PersonalInfo::select("franchisee_id")
                            ->where("user_id",$uid)->first();

    }

    /**
    * Checks the user's status
    *
    * @param
    * @return void
    */
   public function checkUserStatus($userId)
   {
       return User::where('id',$userId)->first();
   }

   public function checkChangePassword($model){
       return ChangePassword::where('user_id',$model['id'])
                        ->where('encryption',$model['encryption'])->first();
   }
}
