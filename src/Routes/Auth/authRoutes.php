<?php

use App\Controller\Auth\CAuthController;

$app->group('/auth', function () use ($app) {
    $app->post('/login', CAuthController::class . ':login');
    $app->post('/register', CAuthController::class . ':register');
    $app->put('/activate/{account}', CAuthController::class . ':activateAccount');
    $app->post('/reset-password', CAuthController::class . ':resetPassword');
    $app->post('/change/password/request', CAuthController::class . ':validatePasswordRequest');
    $app->post('/change-password', CAuthController::class . ':changePassword');
    $app->get('/validateUrl/{token}',CAuthController::class . ':validateChangeRequestURL');
});
