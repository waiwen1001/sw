<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@homeIndex')->name('homeIndex');
Route::post('/start', 'HomeController@startCampaign')->name('startCampaign');
Route::get('/customers', 'HomeController@getCustomerList')->name('getCustomerList');
Route::get('/redeem/{customer_id}', 'HomeController@redeemLink')->name('redeemLink');
Route::post('/submitRedeem', 'HomeController@submitRedeem')->name('submitRedeem');
