<?php

namespace App\Handler\Users;

use App\Helper\CCommon;
use App\Helper\CValidator;
use App\Handler\CHandler;
use Respect\Validation\Validator as v;

class CPersonalInfoHandler extends CHandler
{
    /**
     * Holds the name of the model to be handled
     *
     * @var string
     */
    protected $modelName = 'Personal Info';

    /**
     * Respect Validator
     *
     * @var \App\Helper\CValidator
     */
    private $validator;

    /**
     * Create a new CPersonalInfoHandler instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->validator = new CValidator();
    }

    /**
     * Validate personal info request
     *
     * @param \App\Model\PersonalInfo $model
     *
     * @return array
     */
    public function handle($model)
    {
        $mapping = [
            'Date of Birth' => 'birthdate',
            'Mobile Number' => 'mobile_num',
            'Home Telephone Number' => 'tel_num',
            'Citizenship' => 'citizenship',
            'Birth Country' => 'birth_country',
            'Birth Zipcode' => 'birth_zipcode',
            'Civil Status' => 'civil_status',
            // 'Existing Franchisee' => 'existing_franchisee'
        ];

        $values = $this->setValues($mapping, $model);

        return $this->validator->validate($values,
            [
                'Date of Birth' => v::notEmpty()
                    ->date(CCommon::DATE_FORMAT),
                'Mobile Number' => v::notEmpty()
                    ->phone(),
                'Home Telephone Number' => v::notEmpty()
                    ->phone(),
                'Citizenship' => v::notEmpty()
                    ->length(1, 50),
                'Birth Country' => v::notEmpty()
                    ->length(1, 50),
                'Birth Zipcode' => v::noWhitespace()
                    ->notEmpty()
                    ->numeric()
                    ->length(1, 10),
                'Civil Status' => v::notEmpty()
                    ->length(1, 50),
                // 'Existing Franchisee' => v::not(v::nullType())
                //     ->boolVal(),
            ]
        );
    }
}
