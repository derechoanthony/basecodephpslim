<?php

namespace App\Transformer\Users;
use App\Transformer\CTransformer;
class CExistingApplication extends CTransformer
{
    /**
     * Transform the personal(existing application) info response 
     *
     * @param array $item
     * @return json object
     */
    public function transform($item)
    {
        if($item->franchisee_id[0] === 'F'){
            $exist = true;
        }else{
            $exist = false;
        }
        return ["exist"=>$exist];
    }
}