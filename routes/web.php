<?php

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

$router->get('/', function () use ($router) {
//    return $router->app->version();
    return 'DAppBirds is Blockchain service providers';
});

$router->get('main', 'MainController@index');
$router->get('account/votes', 'AccountController@votes');
$router->get('account/info', 'AccountController@info');

// Route Model
//$router->get('example/{id}', 'ExampleController@show');
//$router->get('foo', 'Photos\TestController@method');

//$router->get('profile', [
//    'middleware' => 'auth',
//    'uses' => 'UserController@showProfile'
//]);