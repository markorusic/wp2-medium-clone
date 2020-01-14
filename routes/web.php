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
Route::get('/posts', 'PostController@index')->name('posts');
Route::get('/posts/{post}', 'PostController@show')->name('post-entry');
Route::get('/posts/category/{category}', 'PostController@categoryPosts')->name('category-posts');

Route::group([ 'middleware' => ['auth']], function () {
	Route::post('/posts/{post}/like', 'PostController@like')->name('post-like');
	Route::post('/posts/{post}/comment', 'PostController@comment')->name('post-comment');
	Route::delete('/posts/{post}/comment/{comment}/remove', 'PostController@removeComment')->name('post-remove-comment');

	Route::post('/users/{user}/follow', 'UserController@follow')->name('user-follow');
});

Auth::routes([ 'register' => false ]);

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