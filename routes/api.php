<?php

use Illuminate\Support\Facades\Route;

Route::name('auth.')
    ->prefix('auth')
    ->group(function () {
        Route::post('login', 'Api\AuthController@login');
        Route::post('register', 'Api\AuthController@register');
        Route::post('refresh', 'Api\AuthController@refresh');
        Route::post('logout', 'Api\AuthController@logout');
        Route::post('forgot-password', 'Api\AuthController@forgotPassword');
        Route::patch('change-password', 'Api\AuthController@changePassword');
    });

Route::prefix('pronunciation')
    ->group(function () {
        Route::post('', 'Api\PronunciationController@test');
    });

Route::group(['middleware' => 'auth:api'], function () {
    Route::name('user.')
        ->prefix('user')
        ->group(function () {
            Route::get('detail', 'Api\UserController@detail')
                ->name('detail');
            Route::get('my-courses', 'Api\UserController@getMyCourses');
            Route::post('', 'Api\UserController@update');
        });

    Route::prefix('banner')
        ->group(function () {
            Route::get('', 'Api\BannerController@getBannerByConditions');
        });

    Route::prefix('series-combo')
        ->group(function () {
            Route::get('', 'Api\LmsSeriesComboController@getSeriesCombo');
            Route::get('/{seriesComboId}', 'Api\LmsSeriesComboController@getSeriesComboDetail');
        });

    Route::prefix('series')
        ->group(function () {
            Route::get('{seriesId}', 'Api\LmsSeriesController@getSeriesDetail');
        });

    Route::prefix('lesson')
        ->group(function () {
            Route::get('', 'Api\LmsContentController@getContents');
            Route::get('in-progress', 'Api\LmsContentController@getInProgressContent');
            Route::get('{lessonId}', 'Api\LmsContentController@getContentById');
            Route::post('finish', 'Api\LmsContentController@finishContent');

        });

    Route::prefix('test')
        ->group(function () {
            Route::post('/{lessonId}/evaluate-test', 'Api\LmsTestController@evaluateTest');
        });

    Route::prefix('v2')
        ->group(function () {
            Route::prefix('lesson')
            ->group(function () {
                Route::get('', 'Api\LmsContentController@getContentsV2');
                Route::get('{lessonId}', 'Api\LmsContentController@getContentByIdV2');
                Route::post('finish', 'Api\LmsContentController@finishContentV2');
            });
        });
});
