<?php

namespace App\Transformer;

use App\Helper\CCommon;

abstract class CTransformer
{

    /**
     * Transform a collection of items
     *
     * @param items
     * @return array
     */
    public function transformCollection($items, $method = 'transform')
    {
        return array_map([$this, $method], $items);
    }
    public function transformCollectionActivity($items, $method = 'transform')
    {
        $list = new \stdClass();
        $corporateArray = array();
        foreach ($items as $item) {
            if ($item->phase->id == CCommon::CORPORATE_NAME_APPROVAL_PHASE) {
                $corporateArray[] = call_user_func(array($this, $method), $item);
                $list->{CCommon::ACTIVITY_MAPPING[$item->phase->id]} = $corporateArray;
            } else if ($item->phase->id == CCommon::SITE_ACCEPTANCE_PHASE) {
                $siteAcceptanceArray[] = call_user_func(array($this, $method), $item);
                $list->{CCommon::ACTIVITY_MAPPING[$item->phase->id]} = $siteAcceptanceArray;
            }  else if ($item->phase->id == CCommon::SITE_ASSESSMENT_PHASE) {
                $siteAssessmentArray[] = call_user_func(array($this, $method), $item);
                $list->{CCommon::ACTIVITY_MAPPING[$item->phase->id]} = $siteAssessmentArray;
            }else if ($item->phase->id == CCommon::OWNERSHIP_DIRECTION_PHASE) {
                $ownershipArray[] = call_user_func(array($this, $method), $item);
                $list->{CCommon::ACTIVITY_MAPPING[$item->phase->id]} = $ownershipArray;
            } else if ($item->phase->id == CCommon::SITE_PAIRING_PHASE) {
                $sitePairingArray[] = call_user_func(array($this, $method), $item);
                $list->{CCommon::ACTIVITY_MAPPING[$item->phase->id]} = $sitePairingArray;
            } else {
                $list->{CCommon::ACTIVITY_MAPPING[$item->phase->id]} = call_user_func(array($this, $method), $item);
            }

        }
        
        return $list;
    }
    public function transformListObject($items, $method = 'transform')
    {
        $list = [];
        foreach ($items as $item) {
            $list[] = call_user_func(array($this, $method), $item);
        }
        return $list;
    }

    abstract public function transform($item);
}
