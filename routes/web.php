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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', 'AuthController@login');
        $router->post('register', 'AuthController@register');

        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->get('me', 'AuthController@me');
            $router->post('refresh', 'AuthController@refresh');
            $router->post('logout', 'AuthController@logout');
        });
    });

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('categories', 'CategoryController@index');

        $router->group(['prefix' => 'feeds'], function () use ($router) {
            $router->get("/", "FeedController@index");
            $router->post("/", "FeedController@store");
            $router->post("image", "FeedController@storeImage");
            $router->get("{id}", "FeedController@get");
            $router->put("{id}", "FeedController@update");
            $router->delete("{id}", "FeedController@destroy");
            $router->get("{id}/answers", "FeedController@indexAnswer");
            $router->post("{id}/answers", "FeedController@storeAnswer");
        });

        $router->group(['prefix' => 'profile'], function () use ($router) {
            $router->get("/", "ProfileController@index");
            $router->put("/", "ProfileController@update");
        });

        $router->group(['prefix' => 'users'], function () use ($router) {
            $router->get("/", "UserController@index");
            $router->get("{id}", "UserController@get");
            $router->post("{id}/follow", "UserController@follow");
            $router->post("{id}/unfollow", "UserController@unfollow");
        });

        $router->get('highlight', 'HighlightController@index');
    });
});
