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


Route::get('', 'PageController@index')->name('home');
Route::get('/posts/{post}', 'PostController@show')->name('post-entry');
Route::get('/posts/category/{category}', 'PostController@categoryPosts')->name('category-posts');
Route::get('/content/search', 'SearchController@contentSearch')->name('content-search');

Route::group([ 'middleware' => ['auth']], function () {
	Route::post('/posts/{post}/like', 'PostController@like')->name('post-like');
	Route::post('/posts/{post}/comment', 'PostController@comment')->name('post-comment');
	Route::delete('/posts/{post}/comment/{comment}/remove', 'PostController@removeComment')->name('post-remove-comment');
	Route::get('/posts/create/_', 'PostController@create')->name('post-create-page');
	Route::post('/posts', 'PostController@store')->name('post-store');
	Route::delete('/posts/{post}', 'PostController@destroy')->name('post-destroy');
	Route::get('/posts/{post}/edit', 'PostController@edit')->name('post-update-page');
	Route::put('/posts/{post}', 'PostController@update')->name('post-update');
	Route::delete('/posts/{post}', 'PostController@destroy')->name('post-delete');

	Route::post('/users/{user}/follow', 'UserController@follow')->name('user-follow');

	Route::post('upload/photo', 'FileController@uploadPhoto')->name('upload.photo');
});

Auth::routes();

// Admin routes
Route::group(
	[
		'middleware' => ['auth'],
		'prefix' => 'admin',
		'namespace' => 'Admin',
		'as' => 'admin.'
	],
	function () {
		Route::get('', 'PageController@index')->name('home');
		Route::get('posts/show-all', 'PostController@showAll');
        
        Route::resource('posts', 'PostController');
        Route::resource('users', 'UserController');
	}
);