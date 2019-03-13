<?php

namespace App\Transformer\Users;

use App\Transformer\CTransformer;
use App\Transformer\Users\CPersonalInfoTransformer;
use App\Helper\CCommon;

class CFranchiseeInfoTransformer extends CTransformer {

    /**
     * Transform the login response 
     *
     * @param array $item
     * @return json object
     */
    public function transform($item)
    {
        if($item->personalInfo){
            $personalInfo = (new CPersonalInfoTransformer())->transform($item->personalInfo);
            $personalInfo['isExist'] = true;
        } else {
            $personalInfo = [
                'personalInfoExistingFranchisee' => 0,
                'isExist' => false
            ];
        }

        $personalInfo['user'] = [
            'userFirstName' => $item->first_name,
            'userMiddleName' => $item->middle_name,
            'userLastName' => $item->last_name,
            'userEmail' => $item->email
        ];

        if($item->latestBackgroundInfo){
            $tempSignature = $item->latestBackgroundInfo
                         ->backgroundAttachments()
                         ->where('attachment_type', '=', CCommon::SIGNATURE_ATTACHMENT_TYPE)
                         ->first();
            
            $personalInfo['user']['userSignature'] = $tempSignature ? $tempSignature->file_name : NULL;
        }

        return $personalInfo;
    }
}