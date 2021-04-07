<?php

$router->group([
  'prefix' => 'branch'
],function() use ($router){
  $router->group([
    'namespace' => 'Auth'
  ],function() use ($router){
    $router->post('login','BranchUserController@login');

    $router->group([
      'middleware' => 'auth:vendor'
    ],function() use ($router){
      $router->get('get_user','BranchUserController@get_user');
      $router->post('logout','BranchUserController@logout');
    });
  });
});
