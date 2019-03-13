<?php

namespace App\Transformer\Users;

use App\Transformer\CTransformer;

class CSBUManagerTransformer extends CTransformer {

    /**
     * Transform the position response 
     *
     * @param array $item
     * @return json object
     */
    public function transform($item)
    {
        
        $data = [
            'managerSBU' => $this->transformCollection($item['sbus'], 'sbus'),
            'managerEmail' => $item['email'],
            'managerPosition' => $item['positions']['title'],
            'managerName' => $item['first_name']." ".$item['middle_name']." ".$item['last_name'],
            
        ];

        return $data;
    }
    public function sbus($item) 
    {
        return [
            'id' => $item['id'],
            'title' => $item['title'],
            'acronym' => $item['acronym']
        ];
    }
}