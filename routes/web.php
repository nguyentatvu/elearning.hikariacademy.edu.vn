<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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

// Page (Client)
Route::get('/', function () {
    return view('client.pages.home');
});

Route::group([], function () {
    Route::post('/login', 'Auth\LoginController@login')->name('login');
    Route::post('/register', 'Auth\RegisterController@register')->name('register');
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
});

// My page (Client)
Route::get('/mypage/leaderboard', function () {
    return view('client.mypage.leaderboard');
})->name('mypage.leaderboard');

Route::get('/mypage/reward-point', function () {
    return view('client.mypage.reward-point');
})->name('mypage.reward-point');

Route::get('/mypage/recharge-point', function () {
    return view('client.mypage.recharge-point');
})->name('mypage.recharge-point');
