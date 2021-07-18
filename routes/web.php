<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
    $data['status'] = 200;
    $data['success'] = 1;
    $data['message'] = "API running in " . env('APP_ENV') . " environment.";
    return response()->json($data, $data['status']);
});

$router->group(['prefix' => 'api/v1', 'middleware' => 'auth'], function () use ($router) {
    require __DIR__ . '/api/with_auth_v1.0.php';
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    require __DIR__ . '/api/without_auth_v1.0.php';
});
