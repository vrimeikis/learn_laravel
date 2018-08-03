<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function(Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function() {
    Route::group(['prefix' => 'articles'], function() {
        Route::get('/', 'API\\ArticleController@getPaginate');
        Route::get('one/{articleId}', 'API\\ArticleController@getById');
        Route::get('one/{articleId}/full', 'API\\ArticleController@getByIdFull');
        Route::get('full', 'API\\ArticleController@getFullData');
    });

    Route::apiResource('categories', 'API\\CategoryController')->only(['index', 'show']);
    Route::apiResource('author', 'API\\AuthorController')->only(['index', 'show']);
});

