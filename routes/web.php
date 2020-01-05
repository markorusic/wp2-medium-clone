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

Auth::routes([ 'register' => false ]);

Route::get('', function () {
    return view('welcome');
});

Route::resource('posts', 'PostController');
Route::resource('users', 'UserController');

Route::group(
	[
		'middleware' => ['auth'],
		'prefix' => 'admin',
		'namespace' => 'Admin',
		'as' => 'admin.'
    ],
    function () {
        Route::get('', 'PageController@index')->name('home');
    }
);
