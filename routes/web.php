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
Route::get('/contact', function () {
    return view('client.pages.contact');
})->name('home.contact');

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

Route::get('/mypage/my-personal', function () {
    return view('client.mypage.personal');
})->name('mypage.personal');

Route::get('/mypage/my-courses', function () {
    return view('client.mypage.my-courses');
})->name('mypage.courses');

Route::get('/mypage/my-exams', function () {
    return view('client.mypage.my-exams');
})->name('mypage.exams');

Route::get('/mypage/my-comments', function () {
    return view('client.mypage.my-comments');
})->name('mypage.my-comments');

Route::get('/mypage/my-result-exam', function () {
    return view('client.mypage.my-result-exam');
})->name('mypage.my-result-exam');

Route::get('/mypage/payment-management', function () {
    return view('client.mypage.payment-management');
})->name('mypage.payment-management');
