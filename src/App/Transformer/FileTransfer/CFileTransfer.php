<?php

namespace App\Transformer\Auth;

use App\Transformer\FileTransfer;

class CFileTransfer extends CTransformer
{

    /**
     * Transform the FileTransfer response 
     *
     * @param array $item
     * @return json object
     */
    public function transform($item)
    {
        return [
            'Avatar' => $item->profile_pic,
            'UserId' => $item->id
        ];
    }
}