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
    return view('app');
});

Route::get('/mypage/leaderboard', function () {
    return view('client.mypage.leaderboard');
});

Route::get('/mypage/reward-point', function () {
    return view('client.mypage.reward-point');
});

Route::get('/mypage/recharge-point', function () {
    return view('client.mypage.recharge-point');
});
