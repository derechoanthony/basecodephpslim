<?php

namespace App\Transformer\Avatar;

use App\Transformer\CTransformer;

class CAvatarTransformer extends CTransformer
{
    /**
     * Transform the login response
     *
     * @param array $item
     * @return json object
     */
    public function transform($item)
    {

        $url = $item['profile_pic'];
        return [
            'uid' => $item->id,
            'profileImage' => $url,
        ];
    }
}
