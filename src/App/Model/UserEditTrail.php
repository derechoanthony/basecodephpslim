<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserEditTrail extends Model implements CModelInterface
{
    /**
     * User Edit Trail table
     */
    protected $table = 'user_edit_trail';
    /**
     * Set create timestamp handling to false
     *
     * @var string
     */
    const UPDATED_AT = null;
    
    protected $dates = ['created_at'];
    /**
     * User Edit Trail columns
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'action',
        'remarks',
        'created_at',
        'created_by',
    ];

     /**
     * Mapping for conversion of the model
     *
     * @var array
     */
    private $mapping = [
        'id' => 'userEditTrailId',
        'user_id' => 'userEditTrailUserId',
        'action' => 'userEditTrailAction',
        'remarks' => 'userEditTrailRemarks',
        'created_at' => 'userEditTrailCreatedAt',
        'created_by' => 'userEditTrailCreatedBy',
    ];
    

    /**
     * The user tracked by this edit trail.
     * 
     * @return Illuminate\Database\Query\Builder
     */
    public function user()
    {
        return $this->belongsTo('App\Model\User')->withTrashed();
    }

    /**
     * The user tracked by this edit trail.
     * 
     * @return Illuminate\Database\Query\Builder
     */
    public function createdBy()
    {
        return $this->belongsTo('App\Model\User','created_by')->withTrashed();
    }
    /**
     * Scope a query to only include one edit trail for CREATE
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEditTrail($query,$action)
    {   
        return $query->where('action',$action);
    }

   /**
     * Convert the request to associated model
     *
     * @param Request $request
     * @return $this
     */ 
    public function convert($request)
    {
        foreach($this->mapping as $column => $param){
            if($request->getParam($param)){
                $this->{$column} = $request->getParam($param);
            }            
        }
        return $this;
    }
    
}