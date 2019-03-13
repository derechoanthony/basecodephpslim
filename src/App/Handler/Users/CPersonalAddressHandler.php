<?php

namespace App\Handler\Users;

use App\Helper\CCommon;
use App\Helper\CValidator;
use App\Handler\CHandler;
use Respect\Validation\Validator as v;

class CPersonalAddressHandler extends CHandler
{
    /**
     * Holds the name of the model to be handled
     *
     * @var string
     */
    protected $modelName = 'Personal Address';

    /**
     * Respect Validator
     *
     * @var \App\Helper\CValidator
     */
    private $validator;

    /**
     * Create a new CPersonalAddressHandler instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->validator = new CValidator();
    }

    /**
     * Validate personal address request
     *
     * @param \App\Model\PersonalAddress $model
     *
     * @return array
     */
    public function handle($model)
    {
        $mapping = [
            'Unit No., Street, Barangay' => 'street',
            'Country' => 'country',
            'Zipcode' => 'zipcode'

        ];

        $values = $this->setValues($mapping, $model);

        return $this->validator->validate($values,
            [
                'Unit No., Street, Barangay' => v::notEmpty()
                    ->length(1, 200),
                'Country' => v::notEmpty()
                    ->length(1, 50),
                'Zipcode' => v::noWhitespace()
                    ->notEmpty()
                    ->numeric()
                    ->length(1, 10),
            ]
        );
    }
}
