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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/post', 'PostsController@post');
Route::get('/profile', 'ProfilesController@profile');
Route::get('/category', 'CategoriesController@category');
Route::post('/addCategory', 'CategoriesController@addCategory');
Route::post('/addProfile', 'ProfilesController@addProfile');

Route::post('/addPost', 'PostsController@addPost');

Route::get('/view/{id}', 'PostsController@view');

Route::get('/edit/{id}', 'PostsController@edit');

Route::post('/editPost/{id}', 'PostsController@editPost');

Route::get('/delete/{id}', 'PostsController@deletePost');

Route::get('/category/{id}', 'PostsController@category');

Route::get('/like/{id}', 'PostsController@like');
