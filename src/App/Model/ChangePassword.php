<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChangePassword extends Model implements CModelInterface
{
    use SoftDeletes;

    /**
     * Users table
     */
    protected $table = 'user_change_password';
    /**
     * Set Eloquent timestamp handling to false
     *
     * @var boolean
     */
    public $timestamps = true;
    /**
     * Date columns to be handled by Eloquent ORM as a Carbon instance
     *
     * @var array
     */
    protected $dates = ['valid_at', 'created_at', 'updated_at', 'deleted_at'];
    /**
     * User columns
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'valid_at',
        'created_at',
        'deleted_at',
        'encryption'
    ];
    /**
     * Mapping for conversion of Application columns
     *
     * @var array
     */
    private $mapping = [
        'id' => 'changePasswordId',
        'user_id' => 'uid',
        'valid_at' => 'validity',
        'encryption' => 'encryptKey',
        'created_at' => 'changePasswordCreatedAt',
        'deleted_at' => 'changePasswordDeletedAt',
    ];
    /**
     * Convert the given data to an associated model
     *
     * @param $request
     * @return $this
     */
    public function convert($request)
    {
        return $this->convertRequest($request);
    }
    /**
     * Convert the request to associated model
     *
     * @param \Slim\Http\Request $request
     * @return $this
     */
    private function convertRequest($request)
    {
        foreach ($this->mapping as $column => $param) {
            $this->{$column} = $request->getParam($param);
        }
        return $this;
    }
}
