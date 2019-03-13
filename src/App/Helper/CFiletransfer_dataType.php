<?php

namespace App\Helper;

use App\Helper\Messages\CUploadMessages;

class CFiletransfer_dataType
{
    public function getType($type)
    {
        switch ($type) {
            case 'CIBI':
            case 'harrisonAssessment':
            case 'GTM':
            case 'interviewSummary':
            case 'siteAssessmentDocument':
                return ["dataType" => "documents", "uploadError" => CUploadMessages::NO_DOCUMENT_UPLOADED];
                break;

            case 'franchiseApplication':
                return ["dataType" => "all", "uploadError" => CUploadMessages::NO_FILE_UPLOADED];
                break;

            default:
                return ["dataType" => "image", "uploadError" => CUploadMessages::NO_IMAGE_UPLOADED];
                break;
        }
    }
}
