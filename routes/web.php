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
Route::get('/posts/{post}', 'PostController@show')->name('posts.index');
Route::get('/posts/category/{category}', 'PostController@categoryPosts')->name('posts.category');
Route::get('/content/search', 'SearchController@contentSearch')->name('content.search');

Route::group([ 'middleware' => ['auth']], function () {
	// API
	Route::post('/posts/{post}/like', 'PostController@like')->name('posts.like');
	Route::post('/posts/{post}/comment', 'PostController@comment')->name('posts.comment');
	Route::delete('/posts/{post}/comment/{comment}/remove', 'PostController@removeComment')->name('posts.remove-comment');
	Route::post('/posts', 'PostController@store')->name('posts.store');
	Route::put('/posts/{post}', 'PostController@update')->name('posts.update');
	Route::delete('/posts/{post}', 'PostController@destroy')->name('posts.destroy');
	Route::post('/users/{user}/follow', 'UserController@follow')->name('user.follow');
	Route::post('upload/photo', 'FileController@uploadPhoto')->name('upload.photo');

	// Pages
	Route::get('/posts/create/_', 'PostController@create')->name('posts.create');
	Route::get('/posts/{post}/edit', 'PostController@edit')->name('posts.edit');
	
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