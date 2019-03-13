<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Position extends Model implements CModelInterface
{
    /**
     * Province Log table
     */
    protected $table = 'position';
    /**
     * Set Eloquent timestamp handling to false
     *
     * @var boolean
     */
    public $timestamps = false;
    /**
     * Province columns
     *
     * @var array
     */

    protected $fillable = [
        'id',
        'title',
    ];

    /**
     * The region which this province belongs to
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function userPosition()
    {
        return $this->hasMany('Franchising\Model\User');
    }


    public function convert($request)
    {
    }
}
