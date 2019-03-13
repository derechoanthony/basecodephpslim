<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Password extends Model implements CModelInterface
{
    /**
     * Password Log table
     */    
    protected $table = 'password_log';
    /**
     * Set update timestamp handling to false
     *
     * @var string
     */
    const UPDATED_AT = null;
    /**
     * Password columns
     *
     * @var array
     */

    protected $fillable = [
        'id',
        'user_id',
        'password',
        'created_at'
    ];

    /**
     * The user account of this password log
     * 
     * @return Illuminate\Database\Query\Builder
     */
    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

    /**
     * Mapping for conversion of the model
     *
     * @var array
     */
    private $mapping = [
        'id' => 'passwordId',
        'user_id' => 'passwordUserId',
        'password' => 'passwordValue',
    ];
    
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
        $model = new Password();
        foreach($this->mapping as $column => $param){
            if(array_key_exists($param, $list) && $list[$param]){
                $model->{$column} = $list[$param];
            }         
        }

        return $model;
    }

    
}