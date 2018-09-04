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
Route::get('category/{slug}', function() {
    return 'TODO: return articles by category!';
})->name('front.category.articles');

Route::get('article/{slug}', function() {
    return 'TODO: make article page!';
})->name('front.article');

Route::get('contacts', 'Front\\ContactController@index')->name('contacts');
Route::post('contacts', 'Front\\ContactController@sendMessage');

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function() {

    Route::get('/', 'AdminController@index')->name('admin.home');

    Route::resource('article', 'ArticleController');

    Route::resource('author', 'AuthorController')->except(['show', 'destroy']);
    Route::resource('category', 'CategoryController')->except(['show', 'destroy']);
    Route::resource('user', 'UserController')->only(['index']);

});
