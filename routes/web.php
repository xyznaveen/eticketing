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
    return $router->app->version();
});

$router->post('/test', 'UserController@authentication');
$router->post('/signup', 'UserController@createUser');

$router->group(['middleware' => 	'auth', 'prefix' 	=> 	'api'], function () use ($router) {
	
	$router->post('/test', 'UserController@authentication');

	$router->get('/gen', 'ExampleController@gen');

});
