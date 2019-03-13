<?php

use App\Helper\CUserFactory;

require __DIR__ . '/RequestMiddleware.php';
require __DIR__ . '/ResponseMiddleware.php';
require __DIR__ . '/RoleMiddleware.php';

$container = $app->getContainer();

/**
 * Init CSRF
 * @return \Slim\Csrf\Guard
 */
$container["csrf"] = function ($container) {
    $guard = new \Slim\Csrf\Guard();
    $guard->setPersistentTokenMode(true);
    return $guard;
};
// CSRF Protection
// $app->add(new \Slim\Csrf\Guard);

// $container['csrf'] = function () {
//     $guard = new \Slim\Csrf\Guard();
//     $guard->setFailureCallable(function ($request, $response, $next) {
//         $request = $request->withAttribute("csrf_status", false);
//         return $next($request, $response);
//     });
//     return $guard;
// };
// $app->add($container->get('csrf'));

// Authorization
$app->add(new Tuupola\Middleware\JwtAuthentication([
    "attribute" => "jwt",
    "secure" => false,
    "path" => ["/api/" . env('APP_VER') . "/"],
    "ignore" => ["/api/" . env('APP_VER') . "/auth"],
    "secret" => env('JWT_SECRET'),
    "before" => function ($request, $arguments) {
        $user = $request->getAttribute("jwt")['user'];
        CUserFactory::Instance();
        CUserFactory::setId($user->userId);
        CUserFactory::setEmail($user->userEmail);
        CUserFactory::setPosition($user->userPosition->id);
        CUserFactory::setRoles($user->userRoles);
        CUserFactory::setSBUs($user->userSBUs);
    },
]));
//Enable logging
// $app->log->setEnabled(true);
// Request middleware
$app->add(new RequestMiddleware($container));

// Response middleware
$app->add(new ResponseMiddleware($container));
