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


Route::get('/', 'Customer@index');

Route::post('customer/signup', 'Customer@signup');
Route::get('customer/update/{email}', 'Customer@update');
// User submit form login sẽ gọi function login trong Customer Controller
Route::post('customer/login', 'Customer@login');
/* Route login facebook gg */
// nhan vao provider de xac dinh la gg hay facebook
Route::get('auth/{provider}','Signin@redirectToProvider');

Route::get('auth/{provider}/callback','Signin@handleProviderCallback');
Route::get('customer', function () {
    return view('customer.home');
});

Route::get('customer/logout', 'Customer@logout');
//route khi ng dùng submit email để reset pass
Route::post('customer/resetpassword', 'Customer@sendMailForgotPass');
////route khi người dùng nhập  password mới confirm-pass và token 
Route::post('customer/reset-password', 'Customer@reset');
