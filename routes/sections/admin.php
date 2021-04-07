<?php

$router->group([
  'prefix' => 'admin'
],function() use ($router){
  $router->group([
    'namespace' => 'Auth'
  ],function() use ($router){
    $router->post('login','AdminController@login');

    $router->group([
      'middleware' => 'auth:admin'
    ],function() use ($router){
      $router->get('get_user','AdminController@get_user');
      $router->post('logout','AdminController@logout');
    });
  });

  $router->group([
    'middleware' => 'auth:admin'
  ],function() use ($router){
    $router->post('products','ProductController@store');
    $router->get('products/{id}','ProductController@show');
    $router->put('products/{id}','ProductController@update');
    $router->delete('products/{id}','ProductController@destroy');

    $router->get('transaction/select_vendor/{id}','TransactionController@select_vendor');
    $router->post('transaction/submit_vendor/{id}','TransactionController@submit_vendor');

    $router->get('user_branch','BranchController@user_branch');
    $router->post('branches','BranchController@store');
    $router->get('branches/{id}','BranchController@show');
    $router->put('branches/{id}','BranchController@update');
    $router->delete('branches/{id}','BranchController@destroy');
  });
});
