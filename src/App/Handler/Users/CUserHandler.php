<?php

namespace App\Handler\Users;

use Respect\Validation\Validator as v;
use App\Helper\CValidator;

class CUserHandler
{

     /**
     * Respect Validator
     *
     * @var \App\Helper\CValidator
     */
    private $validator;

     /**
     * Create a new CAuthHandler instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->validator = new CValidator();
    }

    /**
     * Validate add user request
     *
     * @param \App\model\CUser $model
     *
     * @return array
     */
    public function addUserHandler($model)
    {
        $values = [
            'First Name' => $model['first_name'],
            'Last Name' => $model['last_name'],
            'Email' => $model['email'],
            'Sbu' => $model['user_sbus'],
            'Rbu' => $model['user_rbus'],
            'Role' => $model['user_roles'],
            'Position' => $model['position_id']
        ];

        return $this->validator->validate($values,
            [
                'First Name' => v::alpha()
                    ->notEmpty()
                    ->length(1, 50),
                'Last Name' => v::alpha()
                    ->notEmpty()
                    ->length(1, 50),
                'Email' => v::noWhitespace()
                    ->notEmpty()
                    ->email()
                    ->length(10, 50),
                'Sbu' =>v::notEmpty()
                    ->length(1, 50),
                'Rbu' =>v::notEmpty()
                    ->length(1, 50),
                'Role' =>v::notEmpty()
                    ->length(1, 50),
                'Position' =>v::notEmpty()
                    ->length(1, 50),
            ]
        );
    }

    /**
     * Validate update user request
     *
     * @param \App\model\CUser $model
     *
     * @return array
     */
    public function updateUserHandler($model)
    {
        $values = [
            'First Name' => $model['first_name'],
            'Last Name' => $model['last_name'],
            'Sbu' => $model['user_sbus'],
            'Rbu' => $model['user_rbus'],
            'Position' => $model['position_id'],
        ];

        return $this->validator->validate($values,
            [
                'First Name' => v::alpha()
                    ->notEmpty()
                    ->length(1, 50),
                'Last Name' => v::alpha()
                    ->notEmpty()
                    ->length(1, 50),
                'Sbu' =>v::notEmpty(),
                'Rbu' =>v::notEmpty(),
                'Position' =>v::notEmpty()
                    ->length(1, 50),
            ]
        );
    }

     /**
     * Validate update account information request
     *
     * @param \App\model\CUser $model
     *
     * @return array
     */
    public function updateAccountInformation($model)
    {
        $values = [
            'First Name' => $model['first_name'],
            'Last Name' => $model['last_name'],
        ];

        return $this->validator->validate($values,
            [
                'First Name' => v::alpha()
                    ->notEmpty()
                    ->length(1, 50),
                'Last Name' => v::alpha()
                    ->notEmpty()
                    ->length(1, 50)
            ]
        );
    }
}
