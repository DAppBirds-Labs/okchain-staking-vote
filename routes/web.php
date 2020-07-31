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

function homeRoute($router)
{
    //    return $router->app->version();
//    return 'DAppBirds is Blockchain service providers';

    return view('okchain-voter/index');
//    return view('test/index');
}

$router->get('/', function ()use ($router){
    return homeRoute($router);
});

$router->get('/profile', function ()use ($router){
    return homeRoute($router);
});

$router->get('/node/{path:.*}', function ()use ($router){
    return homeRoute($router);
});

//proxy
$router->get('route/proxy/{path:.*}', 'Route\ProxyController@index');
$router->post('route/proxy/{path:.*}', 'Route\ProxyController@index');

foreach ([
             'main' => 'MainController@index',
	     'main/vote-store' => 'MainController@voteStore',
             'account/votes' => 'AccountController@votes',
             'account/info' => 'AccountController@info',
             'validator/info' => 'ValidatorController@info',
             'validator/vote-addresses' => 'ValidatorController@voteAddresses'
         ] as $uri => $controler)
{
    $uri = 'api/route/'.$uri;
    $router->get($uri, $controler);
    $router->post($uri, $controler);
}

// Route Model
//$router->get('example/{id}', 'ExampleController@show');
//$router->get('foo', 'Photos\TestController@method');

//$router->get('profile', [
//    'middleware' => 'auth',
//    'uses' => 'UserController@showProfile'
//]);
