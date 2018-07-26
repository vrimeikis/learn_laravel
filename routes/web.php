<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('article', 'ArticleController');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('author', 'AuthorController')->except(['show', 'destroy']);
    Route::resource('category', 'CategoryController')->except(['show', 'destroy']);
    Route::resource('user', 'UserController')->only(['index']);
});
