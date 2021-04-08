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
  return response()->json(['message' => "Welcome to PMP Agen. this site is for API purpose only!"]);
  // return $router->app->version();
});
$router->get('branches','BranchController@index');
$router->get('products','ProductController@index');
$router->get('transaction_chart','HomeController@transaction_chart');
$router->get('storages/{dir}/{filename}','StorageController');

require dirname(__FILE__).'/sections/admin.php';
require dirname(__FILE__).'/sections/customer.php';
require dirname(__FILE__).'/sections/vendor.php';
