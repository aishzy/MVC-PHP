<?php

    /** @var Core\Router $router */

    // Home 
    $router->get('/', 'HomeController@index');
    $router->get('/about', 'HomeController@about');

    // Users (Full CRUD)
     $router->get('/users', 'UserController@index');
     $router->get('/users/create', 'UserController@create');
     $router->post('/users', 'UserController@store');
     $router->get('/users/{id}', 'UserController@show');
     $router->get('/users/{id}/edit', 'UserController@edit');
     $router->post('/users/{id}', 'UserController@update');
     $router->get('/users/{id}/delete', 'UserController@destroy');

?>