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

Auth::routes();

Route::get('/', 'Front\\HomeController@index')->name('home');
Route::get('category/{slug}', 'Front\\CategoryController@showArticles')->name('front.category.articles');

Route::get('articles', 'Front\\ArticleController@index')->name('front.articles');
Route::get('article/{slug}', 'Front\\ArticleController@show')->name('front.article.slug');

Route::get('contacts', 'Front\\ContactController@index')->name('contacts');
Route::post('contacts', 'Front\\ContactController@sendMessage');

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function() {

    Route::get('/', 'AdminController@index')->name('admin.home');

    Route::resource('article', 'ArticleController');

    Route::resource('author', 'AuthorController')->except(['show', 'destroy']);
    Route::resource('category', 'CategoryController')->except(['show', 'destroy']);
    Route::resource('user', 'UserController')->only(['index']);

});
