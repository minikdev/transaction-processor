<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['prefix'=>'transactions'],static function(){
    Route::post('/','TransactionController@store');
    Route::get('/','TransactionController@index');
});
