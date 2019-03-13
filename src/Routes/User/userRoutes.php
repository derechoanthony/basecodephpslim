<?php

use App\Controller\Users\CUserController;
use App\Helper\CCommon;

$app->group('/users', function () use ($app) {
    $app->get('/existing/application/{userId}', CUserController::class . ':fetchExistingApplication');
     $app->group('/archived', function () use ($app) {
        $app->put('/{userId}', CUserController::class . ':activateArchivedUser');
        $app->get('[/{params:.*}]', CUserController::class . ':searchArchivedUsers');
     })->add(new RoleMiddleware($app->getContainer(),CCommon::ADMINISTRATOR_ROLE));

    $app->get('/managers[/{params:.*}]', CUserController::class . ':getSBUManagers');
    $app->get('/{userId}', CUserController::class . ':getUser');
    $app->group('/account', function () use ($app) {
        $app->group('/{userId}', function () use ($app) {
            $app->get('', CUserController::class . ':getUser');
            $app->put('',CUserController::class . ':updateAccountInformation');
            $app->get('/franchisee', CUserController::class . ':getFranchiseeInfo')->add(new RoleMiddleware($app->getContainer(), [CCommon::FRANCHISEE_ROLE, CCommon::App_ROLE, CCommon::OPERATIONS_ROLE, CCommon::CRE_ROLE]));
            $app->get('/background', CUserController::class . ':getLatestBackgroundInfo')->add(new RoleMiddleware($app->getContainer(), [CCommon::FRANCHISEE_ROLE]));
        });
    });

     $app->group('', function () use ($app) {
        $app->get('[/{params:.*}]', CUserController::class . ':searchUsers');
        $app->post('', CUserController::class . ':addUser');
        $app->put('/{userId}', CUserController::class . ':updateUser');
        $app->delete('/{userId}', CUserController::class . ':archivedUser');
    })->add(new RoleMiddleware($app->getContainer(),CCommon::ADMINISTRATOR_ROLE));

});
