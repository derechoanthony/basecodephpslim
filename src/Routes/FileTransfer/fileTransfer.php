<?php

use App\Controller\FileTransfer\CFileTransferController;

$app->group('/fileTransfer', function () use ($app) {
    $app->group('/upload', function () use ($app) {
        $app->post('/franchise', CFileTransferController::class . ':franchiseApplicationUpload');
        $app->post('/site', CFileTransferController::class . ':siteApplicationUpload');
        $app->post('/site/assessment/documents', CFileTransferController::class . ':siteAssessmentDocumentUpload');

        $app->group('/profile', function () use ($app) {
            $app->post('/picture', CFileTransferController::class . ':profileImage');
        });

        $app->group('/activity', function () use ($app) {
            $app->post('/CIBI', CFileTransferController::class . ':CIBIUpload');
            $app->post('/HA', CFileTransferController::class . ':HAUpload');
            $app->post('/GTM', CFileTransferController::class . ':GTMUpload');
            $app->post('/inteviewSummary', CFileTransferController::class . ':interviewSummaryUpload');
        });
    });
    $app->group('/shared', function () use ($app) {
        $app->get('/file[{params:.*}]', CFileTransferController::class . ':sharedFiles');
        $app->get('[{params:.*}]', CFileTransferController::class . ':viewSharedFiles');
    });

});
