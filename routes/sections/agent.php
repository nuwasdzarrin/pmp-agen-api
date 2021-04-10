<?php

$router->group([
  'prefix' => 'agent'
],function() use ($router){
  /* Auth Namespace */
  $router->group([
    'namespace' => 'Auth'
  ],function() use ($router){
    $router->post('register','CustomerController@register');
    $router->post('login','CustomerController@login');

    /* auth middleware */
    $router->group([
      'middleware' => 'auth:customer'
    ],function() use ($router){
      $router->post('logout','CustomerController@logout');
      $router->get('profile','CustomerController@get_user');
      $router->put('profile/{id}','CustomerController@update');

      $router->get('get_users','CustomerController@get_users');

    });
  });

  /* auth middleware */
  $router->group([
    'middleware' => 'auth:customer'
  ],function() use ($router){
//    $router->post('select_user_branch','BranchController@select_user_branch');
    $router->post('save_lat_lng','BranchController@save_lat_lng');

    /* transaction group */
    $router->group([
      'prefix' => 'transaction'
    ],function() use ($router) {
      $router->get('/','TransactionController@index');
      $router->post('/','TransactionController@store');
      $router->get('/{id}','TransactionController@show');
    });
  });
});
