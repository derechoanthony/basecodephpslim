<?php


$app->group('/api/' . getenv('APP_VER'), function () use ($app) {
    require __DIR__ . '/Auth/authRoutes.php';
    // require __DIR__ . '/FileTransfer/fileTransfer.php';
    // require __DIR__ . '/User/userRoutes.php';
});
