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

Route::get('/', 'HomeController@index');

Route::get('/wishlist', 'HomeController@wishlist')->name('wishlist');
Route::get('/add-to-wishlist/{id}', 'HomeController@addToWishlist');
Route::get('/delete-from-wishlist/{id}', 'HomeController@deleteFromWishlist');

Route::get('/products.js');

Auth::routes();

Route::get('/search', 'HomeController@search')->name('search');

Route::get('/profile', 'UserController@index')->name('profile');
Route::post('/profile/edit', 'UserController@edit')->name('profile.edit');

Route::get('/cart', 'OrderController@index')->name('cart');
Route::get('/add-to-cart/{id}', 'OrderController@addToCart');
Route::get('/delete-from-cart/{id}', 'OrderController@deleteFromCart');