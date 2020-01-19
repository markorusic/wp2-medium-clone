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

Route::namespace('Auth')->group(function () {
	Route::get('login', 'LoginController@showLoginForm')->name('login');
	Route::post('login', 'LoginController@login');
	Route::post('logout', 'LoginController@logout')->name('logout');
	Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
	Route::post('register', 'RegisterController@register');
});

// Pages
Route::get('', 'PageController@index')->name('home');
Route::get('/posts/category/{category}', 'PostController@categoryPosts')->name('posts.category');
Route::get('/popular-posts', 'PostController@popularPosts')->name('posts.popular');
Route::get('/posts/{post}', 'PostController@show')->name('posts.show');
Route::get('/users/{user}', 'UserController@show')->name('users.show');

// API
Route::get('/content/search', 'SearchController@contentSearch')->name('content.search');
Route::get('/users/{user}/followers', 'UserController@followers')->name('users.followers.index');
Route::get('/users/{user}/following', 'UserController@following')->name('users.following.index');
Route::get('/posts/{post}/likes', 'PostController@likes')->name('posts.likes.index');
Route::get('/posts/{post}/comments', 'PostController@comments')->name('posts.comments.index');

Route::middleware('auth')->group(function () {

	// Pages
	Route::get('/posts/create/_', 'PostController@create')->name('posts.create');
	Route::get('/posts/{post}/edit', 'PostController@edit')->name('posts.edit');
	Route::get('/user/profile', 'UserController@edit')->name('user-profile.edit');
	Route::get('/user/activity', 'UserController@activity')->name('users.activity');

	// API
	Route::post('/posts/{post}/like', 'PostController@like')->name('posts.like');
	Route::post('/posts/{post}/comment', 'PostController@comment')->name('posts.comment');
	Route::delete('/posts/{post}/comment/{comment}/remove', 'PostController@removeComment')->name('posts.remove-comment');
	Route::post('/posts', 'PostController@store')->name('posts.store');
	Route::put('/posts/{post}', 'PostController@update')->name('posts.update');
	Route::delete('/posts/{post}', 'PostController@destroy')->name('posts.destroy');

	Route::post('/users/{user}/follow', 'UserController@follow')->name('user.follow');
	Route::put('/users/profile/update', 'UserController@update')->name('user-profile.update');

	Route::post('upload/photo', 'FileController@uploadPhoto')->name('upload.photo');
	
});


// Admin routes
Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function () {

	Route::namespace('Auth')->group(function () {
		Route::get('/login','LoginController@index')->name('login');
		Route::post('/login','LoginController@login');
		Route::post('/logout','LoginController@logout')->name('logout');
	});

	Route::middleware('auth:admin')->group(function () {
		Route::get('', 'PageController@index')->name('home');

		Route::get('users/{user}/activity', 'UserController@activity')->name('users.activity');
		Route::get('users/all', 'PageController@layoutView')->name('users.index-view');
		Route::get('users/{user}/comments/all', 'PageController@layoutView')->name('users.comments-view');
		Route::get('users/{user}/comments', 'UserController@comments')->name('users.comments-view');
		Route::resource('users', 'UserController');

		Route::get('users/{user}/comments/{comment}/edit', 'CommentController@edit')->name('comments.edit');
		Route::put('comments/{comment}', 'CommentController@update')->name('comments.update');
		Route::delete('users/{id}/comments/{comment}', 'CommentController@destroy')->name('comments.destroy');

		Route::get('posts/all', 'PageController@layoutView')->name('posts.index-view');
		Route::resource('posts', 'PostController');

		Route::get('categories/all', 'PageController@layoutView')->name('categories.index-view');
		Route::resource('categories', 'CategoryController');

	});

});
