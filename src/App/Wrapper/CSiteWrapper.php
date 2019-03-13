<?php

namespace App\Wrapper;

use App\Model\SiteAddress;
use App\Model\SiteAttachment;
use App\Model\Site;

use App\Wrapper\CWrapper;

class CSiteWrapper extends CWrapper
{
    /**
     * Create a new CSiteWrapper instance.
     * @return void
     */
    public function __construct()
    {
        $this->attributes = [
            'site' => null,
            'siteAddress' => null,
            'siteAttachments' => null
        ];

        $this->parentName = 'site';
        $this->parentModel = new Site();
        
        $this->childSchemas = [
            'siteAddress' => [
                'name' => 'siteAddress', 
                'model' => new SiteAddress()
            ],
            'siteAttachments' => [
                'name' => 'siteAttachments', 
                'model' => new SiteAttachment()
            ],
        ];
    }

    // TODO: Try to implement this factory-style
    public function convertToList($collection)
    {
        $output = [];

        foreach($collection as $dataNode){
            $output[] = (new CSiteWrapper())->convert($dataNode);
        }

        return $output;
    }
}
