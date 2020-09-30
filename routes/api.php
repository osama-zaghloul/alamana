<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//user Controller routes
Route::post('register','API\userController@register');
Route::post('login','API\userController@login');
Route::post('profile','API\userController@profile');
Route::post('rechangepass','API\userController@rechangepass');
Route::post('updateprofile','API\userController@update');
Route::post('forgetpassword','API\userController@forgetpassword');
Route::post('activcode','API\userController@activcode');
Route::post('mynotification','API\userController@mynotification');
Route::post('myfavoriteitems','API\userController@myfavoriteitems');
Route::post('updatefirebasebyid','API\userController@updatefirebasebyid');
Route::post('deletenotification','API\userController@deletenotification');
Route::post('changepassword','API\userController@changepassword');


//App Setting Controller 
Route::post('settinginfo','API\appsettingController@settingindex');
Route::post('contactus','API\appsettingController@contactus');
Route::post('categories','API\appsettingController@categories');
Route::post('home','API\appsettingController@home');
Route::post('addtransfer','API\appsettingController@addtransfer');
Route::post('items','API\appsettingController@items');


//Item Controller 
// Route::post('allitems','API\itemController@allitems');
// Route::post('showitem','API\itemController@showitem');
Route::post('addrate','API\itemController@addrate');
Route::post('makefavoriteitem','API\itemController@makefavoriteitem');
Route::post('cancelfavoriteitem','API\itemController@cancelfavoriteitem');


//Order Controller 
Route::post('makeorder','API\orderController@makeorder');
Route::post('myorders','API\orderController@myorders');
Route::post('showorder','API\orderController@showorder');



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
