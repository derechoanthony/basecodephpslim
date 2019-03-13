<?php

namespace App\Handler\FileTransfer;

class CFiletransferHandler_Activity
{
    private $entryMapping = [
        "fileName",
    ];
    public function activityImageValidation($upload)
    {
        $error = [];
        foreach ($upload as $key => $value) {
            $filename = explode('_', $key)[0];


            if (!in_array($filename, $this->entryMapping)) {
                array_push($error, $filename . " is required");
            }
        }

        return $error;
    }
}
