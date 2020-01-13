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
Route::get('/post/{post}', 'PageController@postEntry')->name('post-entry');

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