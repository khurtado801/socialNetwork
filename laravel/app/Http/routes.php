<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */


header("Access-Control-Allow-Origin: *");

View::addExtension('html', 'php');


use Illuminate\Support\Facades\Input;

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::post('/signup',  ['users' => 'UserController@postSignUp', 'as' =>'signup']);
});
