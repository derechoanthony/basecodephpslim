<?php

namespace App\Transformer\Users;

use App\Transformer\CTransformer;
use \Datetime;
use App\Helper\CCommon;
class CUserTransformer extends CTransformer {

    /**
     * Transform the user response
     *
     * @param array $item
     * @return json object
     */
    public function transform($item)
    {
        if($item->latestUpdate){
            $updatedDate = DateTime::createFromFormat(CCommon::DATE_FORMAT, $item->latestUpdate->created_at->toDateString())->format(CCommon::DATE_FORMAT_FOR_DISPLAY);
            if($item->latestUpdate->user){
                $updatedBy =  $item->latestUpdate->createdBy->first_name." ".$item->latestUpdate->createdBy->last_name;
            }
            else{
                $updatedBy =  CCommon::USER_DELETE;
            }
        }else{
            $updatedDate = CCommon::NOT_YET_UPDATED_DATE;
            $updatedBy = CCommon::NOT_YET_UPDATED_BY;
        } 
        if($item->latestCreate){
            $latestCreate = DateTime::createFromFormat(CCommon::DATE_FORMAT, $item->latestCreate->created_at->toDateString())->format(CCommon::DATE_FORMAT_FOR_DISPLAY);
        }else{
            $latestCreate = null;
        }
        return [
            'userId' => $item->id,    
            'userFirstName' => $item->first_name,    
            'userMiddleName' => $item->middle_name,
            'userLastName' => $item->last_name,
            'userEmail' => $item->email,
            'userSbus' => $this->transformCollection($item->sbus->toArray(), 'sbus'),
            'userRbus' => $this->transformCollection($item->rbus->toArray(), 'rbus'),
            'userRoles' => $this->roles($item->roles->first()),
            'userPosition' => $this->positions($item->positions->toArray()),
            'userCreatedAt' => $latestCreate,
            'userCreatedBy' => $item->latestCreate->createdBy->first_name." ".$item->latestCreate->createdBy->last_name,
            'userUpdatedAt' => $updatedDate,
            'userUpdatedBy' => $updatedBy
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
    public function positions($item) 
    {
        return [
            'id' => $item['id'],
            'title' => $item['title']
        ];
    }
}