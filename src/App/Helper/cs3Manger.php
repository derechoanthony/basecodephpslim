<?php

namespace App\Helper;

use Aws\Exception\MultipartUploadException;
use Aws\S3\MultipartUploader;
use Aws\S3\S3Client;

class cs3Manger
{
    private $region;
    private $version;
    public function __construct()
    {
        $this->region = 'ap-southeast-1';
        $this->version = 'latest';
    }
    public function s3Settings()
    {
        return new S3Client([
            'region' => $this->region,
            'version' => $this->version,
            'debug' => false,
        ]);
    }

    public function uploadFile($src, $filename)
    {
        $s3 = $this->s3Settings();
        $uploader = new MultipartUploader($s3, $src . $filename, [
            'bucket' => 'franchising-system',
            'key' => $filename,
        ]);
        try {
            $result = $uploader->upload();
            return [
                "success" => true,
                "uploadmessage" => "Upload complete {$result['ObjectURL']}",
            ];
        } catch (MultipartUploadException $e) {
            return [
                "success" => false,
                "uploadmessage" => "Upload error {$e->getMessage()}",
            ];
        }

    }

    public function getFile($filename)
    {
        $s3 = $this->s3Settings();
        $cmd = $s3->getCommand('GetObject', [
            'Bucket' => 'franchising-system',
            'Key' => $filename,
        ]);
        $request = $s3->createPresignedRequest($cmd, '+7 days');
        $secureUrl = (string) $request->getUri();
        // $secureUrl = (string) $request->getUri();
        return $secureUrl;
    }
}
