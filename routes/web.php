<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::any('/Dbaction/add', 'DbComActionController@add'); 
Route::any('/Dbaction/update', 'DbComActionController@update'); 

Route::group(['namespace' => 'Merchant'], function () {
    Route::any('/merchantMain', 'LogincheckController@displayReg'); 
    Route::any('/sendCode', 'LogincheckController@regCode'); 
    Route::any('/merLogin', 'LogincheckController@merLogin'); 
    Route::any('/MerchantUser/addMerchant', 'MerchantUserController@addMerchant'); 
    Route::any('/MerchantUser/updateMerchant', 'MerchantUserController@updateMerchant'); 
    Route::any('/MerchantUser/reg_code', 'MerchantUserController@updateMerchant'); 
   
});