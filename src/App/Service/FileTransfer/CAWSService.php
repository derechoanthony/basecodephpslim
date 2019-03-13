<?php

namespace App\Service\FileTransfer;

class CAWSService
{
    public static function uploadToAWS($directory,$files=null){
        $src = $directory.'/*';
       var_dump(exec("bash ".__DIR__."/test.sh $src"));
    }
}