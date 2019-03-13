<?php

namespace App\Handler\FileTransfer;

class CFiletransferHandler_franchiseApplication
{
    private $entryMapping = [
        "GOVID",
        "FINANCIAL",
        "SIGNATURE",
        "SITEPIC",
        "VICINITYMAP",
        "FLOORPLAN",
        "FILENAME",
        "PROFILEPIC"
    ];
    public function validateIndex($upload)
    {
        $error = [];
        foreach ($upload as $key => $value) {
            $filename = explode('_', $key)[0];
            if (!in_array($filename, $this->entryMapping)) {
                array_push($error, $filename . " not found in the index option");
            }
        }

        return $error;
    }
}
