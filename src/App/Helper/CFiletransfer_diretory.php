<?php

namespace App\Helper;

class CFiletransfer_diretory
{

    /**
     * upload directory
     *
     * @return Array
     */
    public static function uploadDirectory()
    {
        return [
            "franchiseApplication" => UPLOAD_DIR . env('FRANCHISE_UPLOAD'),
            "siteApplication" => UPLOAD_DIR . env('SITEAPPLICATION'),
            "siteAssessmentDocument" => UPLOAD_DIR . env('SITEAPPLICATIONDOCUMENTS'),
            "CIBI" => UPLOAD_DIR . env('CIBI'),
            "avatar" => UPLOAD_DIR . env('PROFILEPIC_UPLOAD'),
            "harrisonAssessment" => UPLOAD_DIR . env('HA'),
            "GTM" => UPLOAD_DIR . env('GTM'),
            "interviewSummary" => UPLOAD_DIR . env('INTERVIEWSUMMARY'),
            "profileImage" => UPLOAD_DIR . env('PROFILEPIC_UPLOAD'),
        ];
    }
}
