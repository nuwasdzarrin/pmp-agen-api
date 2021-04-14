<?php

$router->group([
  'prefix' => 'agent'
],function() use ($router){
  /* Auth Namespace */
  $router->group([],function() use ($router){
    $router->post('register','Auth\CustomerController@register');
    $router->post('login','Auth\CustomerController@login');
    $router->get('articles','ArticleController@index');

    /* auth middleware */
    $router->group([
      'middleware' => 'auth:customer'
    ],function() use ($router){
      $router->post('logout','Auth\CustomerController@logout');
      $router->get('profile','Auth\CustomerController@get_user');
      $router->put('profile/{id}','Auth\CustomerController@update');

      $router->get('get_users','Auth\CustomerController@get_users');

    });
  });

  /* auth middleware */
  $router->group([
    'middleware' => 'auth:customer'
  ],function() use ($router){
//    $router->post('select_user_branch','BranchController@select_user_branch');
    $router->post('save_lat_lng','BranchController@save_lat_lng');

    $router->get('products','ProductController@index');

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
