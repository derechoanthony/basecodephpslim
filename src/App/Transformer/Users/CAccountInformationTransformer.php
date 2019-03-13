<?php

namespace App\Transformer\Users;

use App\Transformer\CTransformer;

class CAccountInformationTransformer extends CTransformer {

    /**
     * Transform the user response
     *
     * @param array $item
     * @return json object
     */
    public function transform($item)
    {
        return [
            'userId' => $item->id,    
            'userFirstName' => $item->first_name,    
            'userMiddleName' => $item->middle_name,
            'userLastName' => $item->last_name,
            'userEmail' => $item->email,
            'userSbus' => $this->transformCollection($item->sbus->toArray(), 'sbus'),
            'userRbus' => $this->transformCollection($item->rbus->toArray(), 'rbus'),
            'userRoles' => $this->transformCollection($item->roles->toArray(), 'roles'),
            'userPosition' => $item->position,
            'userProfilePicture' => $item->profile_pic
        ];
    }

    public function sbus($item) 
    {
        return [
            'id' => $item['id'],
            'title' => $item['title'],
            'acronym' => $item['acronym']
        ];
    }
    public function rbus($item) 
    {
        return [
            'id' => $item['id'],
            'title' => $item['title'],
            'acronym' => $item['acronym']
        ];
    }
    public function roles($item) 
    {
        return [
            'id' => $item['id'],
            'title' => $item['title']
        ];
    }
}