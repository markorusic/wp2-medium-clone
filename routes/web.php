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

// API
Route::get('/content/search', 'SearchController@contentSearch')->name('content.search');

// Pages
Route::get('', 'PageController@index')->name('home');
Route::get('/posts/{post}', 'PostController@show')->name('posts.show');
Route::get('/posts/{post}/comments', 'PostController@comments')->name('posts.comments.index');
Route::get('/posts/{post}/likes', 'PostController@likes')->name('posts.likes.index');
Route::get('/posts/category/{category}', 'PostController@categoryPosts')->name('posts.category');
Route::get('/popular-posts', 'PostController@popularPosts')->name('posts.popular');
Route::get('/users/{user}', 'UserController@show')->name('users.show');
Route::get('/users/{user}/followers', 'UserController@followers')->name('users.followers.index');
Route::get('/users/{user}/following', 'UserController@following')->name('users.following.index');

Route::group([ 'middleware' => ['auth']], function () {
	// API
	Route::post('/posts/{post}/like', 'PostController@like')->name('posts.like');
	Route::post('/posts/{post}/comment', 'PostController@comment')->name('posts.comment');
	Route::delete('/posts/{post}/comment/{comment}/remove', 'PostController@removeComment')->name('posts.remove-comment');
	Route::post('/posts', 'PostController@store')->name('posts.store');
	Route::put('/posts/{post}', 'PostController@update')->name('posts.update');
	Route::delete('/posts/{post}', 'PostController@destroy')->name('posts.destroy');

	Route::post('/users/{user}/follow', 'UserController@follow')->name('user.follow');
	Route::put('/users/{user}/update', 'UserController@update')->name('users.update');

	Route::post('upload/photo', 'FileController@uploadPhoto')->name('upload.photo');

	// Pages
	Route::get('/posts/create/_', 'PostController@create')->name('posts.create');
	Route::get('/posts/{post}/edit', 'PostController@edit')->name('posts.edit');
	Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit');
	
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