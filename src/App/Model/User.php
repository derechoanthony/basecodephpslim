<?php

namespace App\Model;

use App\Helper\CCiper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements CModelInterface
{
    use SoftDeletes;

    /**
     * Users table
     */
    protected $table = 'user';
    /**
     * Set Eloquent timestamp handling to false
     *
     * @var boolean
     */
    public $timestamps = false;
    /**
     * Date columns to be handled by Eloquent ORM as a Carbon instance
     *
     * @var array
     */
    protected $dates = ['deleted_at','created_at'];
    /**
     * User columns
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'position_id',
        'profile_pic',
        'prefix',
        'created_at',
        'deleted_at',
        'profile_pic',
    ];

      /**
     * Mapping for conversion of the model
     *
     * @var array
     */
    private $mapping = [
        'id' => 'userId',
        'profile_pic' => 'userProfilePic',
        'first_name' => 'userFirstName',
        'middle_name' => 'userMiddleName',
        'last_name' => 'userLastName',
        'email' => 'userEmail',
        'password' => 'userPassword',
        'role' => 'userRole',
        'position_id' => 'userPosition',
        'status' => 'userStatus',
        'user_roles' => 'userRoles',
        'user_rbus' => 'userRbus',
        'user_sbus' => 'userSbus',
        '_id' => 'ref_id',
        'encryption' => 'encryption',
        'page' => 'page',
    ];

    /**
     * The Personal Information for this user.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function personalInfo()  
    {
        return $this->hasOne('App\Model\PersonalInfo');
    }

    /**
     * The Background Information for this user.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function backgroundInfos()
    {
        return $this->hasMany('App\Model\BackgroundInfo');
    }

    /**
     * The latest Background Information for this user.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function latestBackgroundInfo()
    {
        return $this->hasOne('App\Model\BackgroundInfo')->latest();
    }

    /**
     * The list of existing stores for this user.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function pairableSites()
    {
        return $this->hasMany('App\Model\PairableSite', 'user_id');
    }

    /**
     * The roles that the user has created.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function createdRoles()
    {
        return $this->hasMany('App\Model\Role', 'created_by');
    }

    /**
     * The roles that the user has updated.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function updatedRoles()
    {
        return $this->hasMany('App\Model\Role', 'updated_by');
    }

    /**
     * The roles that the user has been assigned to.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function roles()
    {
        return $this->belongsToMany('App\Model\Role', 'user_roles');
    }

    /**
     * The RBUs that the user has been assigned to.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function rbus()
    {
        return $this->belongsToMany('App\Model\rbu', 'user_rbu', 'user_id', 'rbu_id');
    }

    /**
     * The SBUs that the user has been assigned to.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function sbus()
    {
        return $this->belongsToMany('App\Model\sbu', 'user_sbu', 'user_id', 'sbu_id');
    }

    /**
     * The schedules this user has created.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function createdSchedules()
    {
        return $this->hasMany('App\Model\Schedule', 'scheduler_id');
    }

    /**
     * The interviews this user has been associated with.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function interviewRatings()
    {
        return $this->hasMany('App\Model\InterviewRating');
    }

    /**
     * The activities which this user has been
     *  assigned to.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function activities()
    {
        return $this->hasMany('App\Model\Activity', 'pic_id');
    }

    /**
     * The applications which this user has created.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function applications()
    {
        return $this->hasMany('App\Model\Application');
    }

     /**
     * The latest user created edit trail
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function latestCreate()
    {
        return $this->hasOne('App\Model\UserEditTrail')->latest();
    }

    /**
     * The latest updated user edit trail
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function latestUpdate()
    {
        return $this->hasOne('App\Model\UserEditTrail')->latest();
    }
    /**
     * The edits that have been done to the user account.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function edits()
    {
        return $this->hasMany('App\Model\UserEditTrail');
    }

    /**
     * The password logs associated with the user account.
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function passwords()
    {
        return $this->hasMany('App\Model\Password');
    }

    /** CMF
     * The sponsored training
     */
    public function sponsoredTrainings()
    {
        return $this->hasMany('App\Model\TrainingInvitation', 'sponsor_id');
    }

    /** CMF
     * The training used by the invitee
     */
    public function inviteeTrainings()
    {
        return $this->hasMany('App\Model\TrainingInvitation', 'invitee_id');
    }

    /** CMF
     * trainings created by
     */
    public function createdByTrainings()
    {
        return $this->hasMany('App\Model\Training', 'created_by');
    }

    /** CMF
     * trainings updated by
     */
    public function updatedByTrainings()
    {
        return $this->hasMany('App\Model\Training', 'updated_by');
    }

    /** CMF
     * rbu created by
     */
    public function createdByRBU()
    {
        return $this->hasMany('App\Model\rbu', 'created_by');
    }

    /** CMF
     * rbu updated by
     */
    public function updatedByRBU()
    {
        return $this->hasMany('App\Model\rbu', 'updated_by');
    }

    /** CMF
     * sbu created by
     */
    public function createdBySBU()
    {
        return $this->hasMany('App\Model\sbu', 'created_by');
    }

    /** CMF
     * sbu updated by
     */
    public function updatedBySBU()
    {
        return $this->hasMany('App\Model\sbu', 'updated_by');
    }

    /** CMF
     * user positions
     */
    public function positions()
    {
        return $this->belongsTo('App\Model\Position', 'position_id');
    }

    /** CMF
     * Attendees of the batch
     */
    public function userBatchAttendee()
    {
        return $this->belongsToMany('App\Model\TrainingBatch', 'batch_attendee');
    }

    /** CMF
     * Fetch the existence status (New/Existing) by SBU
     */
    public function getExistenceBySBU($sbuId)
    {
        return empty($this->applications()->where('sbu_id', $sbuId)->where('is_pending', 0)->first());
    }

    /**
     * Convert the request to associated model
     *
     * @param Request $request
     * @return $this
     */
    public function convert($request, $args = [])
    {
        if (array_key_exists('userId', $args)) {
            $this->id = $args['userId'];
        }
        if(is_array($request)){
            return $this->convertList($request);
        } else {
            return $this->convertRequest($request);
        }
    }

    /**
     * Convert the request to associated model
     *
     * @param \Slim\Http\Request $request
     * @return $this
     */
    private function convertRequest($request)
    {
        foreach($this->mapping as $column => $param){
            
            if($request->getParam($param)){
                if ($param == 'userRoles' || $param =='userRbus' || $param =='userSbus') {
                    $this->{$column} = explode(",",$request->getParam($param));
                }
                else if ($param == 'ref_id' ) {
                    //add cipher
                    $ciper = new CCiper();
                    $key=explode('+',$ciper->cipher('decrypt', $request->getParam($param)));
                    $id = $key[0];
                    $this->encryption = $key[1];
                    $this->{str_replace('_','', $column)} = $id;
                }
                else{
                    $this->{$column} = $request->getParam($param);
                }
            }     
        }
        return $this;
    }

    /**
     * Convert the data list to associated model
     *
     * @param array $list
     * @return \App\Model\$this->model
     */
    private function convertList($list)
    {
        $model = new User();
        foreach($this->mapping as $column => $param){
            if(array_key_exists($param, $list) && $list[$param]){
                if ($param == 'userRoles' || $param =='userRbus' || $param =='userSbus') {
                    $model->{$column} = explode(",",$list[$param]);
                }
                else if ($param ==('ref_id')) {
                    //add cipher
                    $ciper = new CCiper();
                    $model->{$column} = $ciper->cipher('decrypt', $list[$param]);
                }
                else{
                    $model->{$column} = $list[$param];
                }
            }         
        }
        return $model;
    }
}
