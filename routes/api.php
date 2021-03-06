<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */




Route::group(['middleware' => ['HasAccess']], function () {

    /**
     * user -- fully done!
     */
    Route::group(['prefix' => 'user'], function () {
        Route::get('login', "UserController@login");
        Route::get('find/{q}', "UserController@find");
        Route::get('get/{id}', "UserController@get");
        Route::post('', "UserController@signup");
        // need auth
        Route::group(['middleware' => ['auth:api']], function () {
            Route::get('', "UserController@auth")->middleware('auth:api');
            Route::get('logout', "UserController@logout")->middleware('auth:api');
            Route::post('update', "UserController@update")->middleware('auth:api');
            Route::delete('', "UserController@destroy")->middleware('auth:api');
        });
    });

    /**
     * doctor -- in-progress => certificate
     */
    Route::group(['prefix' => 'doctor'], function () {
        Route::get('recommended', "DoctorController@getRecommended");
        Route::post('', "DoctorController@store")->middleware('auth:api');
    });

    /**
     * section -- done!
     */
    Route::group(['prefix' => 'section'], function () {
        Route::get('', "SectionController@index");
        Route::get('doctors', "SectionController@getDoctors");
    });

    /**
     * post -- in-progress
     */
    Route::group(['prefix' => 'post'], function () {
        Route::get('{id}', "PostController@show");

        // auth or not
        $middleware = [];
        if (\Request::header('Authorization'))
          $middleware = ['middleware' => ['auth:api']];
        Route::group($middleware, function (){
          Route::get('', "PostController@index");
        });

        // need auth
        Route::group(['middleware' => ['auth:api']], function () {
            Route::get('voted', "PostController@votedPosts");
            Route::get('{id}/up', "PostController@vote");
            Route::get('{id}/down', "PostController@vote");
            Route::get('{id}/unvote', "PostController@unvote");
            Route::post('', "PostController@store");
            Route::put('{id}', "PostController@update");
            Route::delete('{id}', "PostController@destroy");
        });
    });

    /**
     * comment -- in-progress
     */
    Route::group(['prefix' => 'comment'], function () {
        Route::get('', "CommentController@index");
        Route::get('{id}', "CommentController@show");
        // need auth
        Route::group(['middleware' => ['auth:api']], function () {
            Route::post('', "CommentController@store");
            Route::put('{id}', "CommentController@update");
            Route::delete('{id}', "CommentController@destroy");
        });
    });

    /**
     * chat -- in-progress
     */
    Route::group(['prefix' => 'chat'], function () {
        // need auth
        Route::group(['middleware' => ['auth:api']], function () {
            Route::get('', "ChatController@index");
            Route::get('{id}', "ChatController@show");
            Route::post('', "ChatController@store");
        });
    });

});
