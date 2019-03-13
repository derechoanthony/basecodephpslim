<?php

namespace App\Transformer\Auth;

use App\Transformer\CTransformer;

class CLoginTransformer extends CTransformer
{

    /**
     * Transform the login response
     *
     * @param array $item
     * @return json object
     */
    public function transform($item)
    {
        return [
            
            'token' => $item->token,
            'firstName' => $item->first_name,
            'middleName' => $item->middle_name,
            'lastName' => $item->last_name,
            'email' => $item->email,
            'profileImage' => $item->profileImage,
            'position' => $this->positionTransform($item),
        ];
    }

    /**
     * Transform position
     *
     * @param array $item
     * @return json object
     */
    public function positionTransform($item)
    {
        return [
            'id' => $item->positions->id,
            'title' => $item->positions->title,
        ];
    }
}
