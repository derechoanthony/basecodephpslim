<?php

namespace App\Handler\Auth;

use App\Helper\CValidator;
use Respect\Validation\Validator as v;

class CAuthHandler
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
     * Validate login request
     *
     * @param \App\model\CUser $model
     *
     * @return array
     */
    public function loginHandler($model)
    {
        $values = [
            'Email' => $model['email'],
            'Password' => $model['password'],
        ];

        return $this->validator->validate($values,
            [
                'Email' => v::noWhitespace()
                    ->notEmpty()
                    ->length(10, 50),
                'Password' => v::noWhitespace()
                    ->notEmpty()
                    ->length(8, 20),
            ]
        );
    }

    /**
     * Validate register request
     *
     * @param \App\model\CUser $model
     *
     * @return array
     */
    public function registerHandler($model)
    {
        $values = [
            'First Name' => $model['first_name'],
            'Last Name' => $model['last_name'],
            'Email' => $model['email'],
            'Password' => $model['password'],
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
                'Password' => v::noWhitespace()
                    ->notEmpty()
                    ->length(8, 20),
            ]
        );
    }

    /**
     * validate the resetPassword
     *
     * @param \App\model\CUser $model
     * @return Array
     */
    public function resetPasswordHandler($model)
    {
        $values = [
            'Email' => $model['email'],
        ];
        return $this->validator->validate($values,
            [
                'Email' => v::noWhitespace()
                    ->notEmpty()
                    ->email()
                    ->length(10, 50),
            ]);
    }

    /**
     * Undocumented function
     *
     * @param \App\model\CUser $model
     * @return Array
     */
    public function changePasswordHandler($model)
    {
        $values = [
            'id' => $model['id'],
            'Password' => $model['password'],
        ];
        return $this->validator->validate(
            $values,
            [
                'id' => v::noWhitespace()
                    ->notEmpty()
                    ->intType(),
                'Password' => v::noWhitespace()
                    ->notEmpty()
                    ->length(8, 50),
            ]
        );
    }
}
