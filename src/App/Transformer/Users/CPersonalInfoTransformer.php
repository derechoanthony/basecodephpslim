<?php

namespace App\Transformer\Users;

use App\Transformer\CTransformer;
use App\Helper\CCommon;

class CPersonalInfoTransformer extends CTransformer {

    /**
     * Transform the personal info response 
     *
     * @param array $item
     * @return json object
     */
    public function transform($item)
    {
        $data = [           
            'personalInfoUserId' => $item->user_id,
            'personalInfoBirthdate' => $item->birthdate ? $item->birthdate->format(CCommon::DATE_FORMAT_FOR_DISPLAY) : null,
            'personalInfoMobileNum' => $item->mobile_num,
            'personalInfoTelNum' => $item->tel_num,
            'personalInfoCivilStatus' => $item->civil_status,
            'personalInfoCitizenship' => $item->citizenship,
            'personalInfoExistingFranchisee' => $item->franchisee_id ? $item->franchisee_id[0] === 'F' : 0,
            'personalInfoApplicantId' => $item->franchisee_id,
            'personalInfoBirthCity' => [
                'cityId' => $item->birthCity->id,
                'cityName' => $item->birthCity->name
            ],
            'personalInfoBirthProvince' => [
                'provinceId' => $item->birthCity->province->id,
                'provinceName' => $item->birthCity->province->name
            ],
            'personalInfoBirthCountry' => $item->birth_country,
            'personalInfoBirthZipcode' => $item->birth_zipcode,
            'personalInfoPersonalAddress' => $this->subTransformPersonalAddress($item->personalAddress)   
        ];

        return $data;
    }

    private function subTransformPersonalAddress($address){
        $output = [
            'personalAddressId' => $address->id,
            'personalAddressStreet' => $address->street,
            'personalAddressCity' => [
                'cityId' => $address->city->id,
                'cityName' => $address->city->name
            ],
            'personalAddressProvince' => [
                'provinceId' => $address->city->province->id,
                'provinceName' => $address->city->province->name
            ],
            'personalAddressCountry' => $address->country,
            'personalAddressZipcode' => $address->zipcode,
        ];        

        return $output;
    }
}