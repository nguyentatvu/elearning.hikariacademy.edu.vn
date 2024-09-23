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

// Page (Client)
Route::get('/', function () {
    return view('client.pages.home');
});

Route::group([], function () {
    Route::get('/login', function () {
        return view('client.auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('client.auth.register');
    })->name('register');
});

Route::group([], function () {
    Route::get('/login', function () {
        return view('client.auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('client.auth.register');
    })->name('register');
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
