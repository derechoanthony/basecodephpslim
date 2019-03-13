<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model implements CModelInterface
{
    /**
     * Role table
     */
    protected $table = 'role';
    /**
     * Date columns to be handled by Eloquent ORM as a Carbon instance
     * 
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * Role columns
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];

    /**
     * Mapping for conversion of the model
     *
     * @var array
     */
    private $mapping = [
        'id' => 'roleId',
        'title' => 'roleTitle',
        'created_at' => 'roleCreatedAt',
        'created_by' => 'roleCreatedBy',
        'updated_at' => 'roleUpdatedAt',
        'updated_by' => 'roleUpdatedBy',
        'deleted_at' => 'roleDeletedBy',
    ];
    /**
     * The users assigned to this role
     * 
     * @return Illuminate\Database\Query\Builder
     */
    public function users()
    {
        return $this->belongsToMany('App\Model\User', 'user_roles');
    }

    /**
     * The user that created this role
     * 
     * @return Illuminate\Database\Query\Builder
     */
    public function createdBy()
    {
        return $this->belongsTo('App\Model\User','created_by');
    }

    /**
     * The user that updated this role
     */
    public function updatedBy()
    {
        return $this->belongsTo('App\Model\User','updated_by');
    }

   /**
     * Convert the request to associated model
     *
     * @param Request $request
     * @return $this
     */ 
    public function convert($request)
    {
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
                $this->{$column} = $request->getParam($param);
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
        $model = new Role();
        foreach($this->mapping as $column => $param){
            if(array_key_exists($param, $list) && $list[$param]){
                $model->{$column} = $list[$param];
            }         
        }

        return $model;
    }

   
}