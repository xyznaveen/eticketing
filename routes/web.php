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

$router->group([
	'prefix'	=>	'v1'
	], function() use ($router) {

		// You'll get this
		$router->get('/', function () use ($router) {return response(['message'	=>	'api server','built-on'	=> 'Lumen ( mini Laravel )','api-version'	=> 0.1,]);});

		$router->post('/signup', 'UserController@create');
		$router->post('/login', 'AuthenticationController@login');

		// Requires authentication
		$router->group([
			'middleware' => 	'auth', 
			'prefix' 	=> 	'api'
			], function () use ($router) {
				
				$router->post('/test', 'UserController@authentication');
				$router->get('/gen', 'ExampleController@gen');

		});
});
