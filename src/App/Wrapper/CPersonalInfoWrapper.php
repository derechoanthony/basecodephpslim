<?php

namespace App\Wrapper;

use App\Model\PersonalAddress;
use App\Model\PersonalInfo;

use App\Wrapper\CWrapper;

class CPersonalInfoWrapper extends CWrapper
{
    /**
     * Create a new CPersonalInfoWrapper instance.
     * @return void
     */
    public function __construct()
    {
        $this->attributes = [
            'personalInfo' => null,
            'personalAddress' => null
        ];

        $this->parentName = 'personalInfo';
        $this->parentModel = new PersonalInfo();
        
        $this->childSchemas = [
            'personalInfoPersonalAddress' => [
                'name' => 'personalAddress', 
                'model' => new PersonalAddress()
            ],
        ];
    }

    // TODO: Try to implement this factory-style
    public function convertToList($collection)
    {
        $output = [];

        foreach($collection as $dataNode){
            $output[] = (new CPersonalInfoWrapper())->convert($dataNode);
        }

        return $output;
    }
}
