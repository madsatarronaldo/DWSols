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

Route::get('/', function () {
    return view('login');
});

Route::get('/registeration', function () {
    return view('register');
});
Route::get('/registeration/account/verify/{token}', [App\Http\Controllers\AuthenticationController::class, 'verifyAccount'])->name('user.verify'); 
Route::post('/login' , 'App\Http\Controllers\AuthenticationController@UserLogin')->name('userlogin');
Route::post('/registeration/user' , 'App\Http\Controllers\AuthenticationController@UserRegisteration')->name('userregisteration');


Route::get('/dashboard', 'App\Http\Controllers\AuthenticationController@UserDashboard')->middleware('authuser');