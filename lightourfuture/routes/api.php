<?php

use Illuminate\Http\Request;

Route::group([
    'prefix' => 'auth'
], function ($router){
    Route::post('login'  , 'AuthController@authenticate');
    Route::post('logout' , 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me'     , 'AuthController@me');  
});