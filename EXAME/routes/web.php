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
    return view('welcome');
});

Route::get('/home', 'Shelter@index');

Route::get('/pets/{id?}', 'Shelter@pets');

Route::get('/register', 'Shelter@register');
Route::post('/register_action', 'Shelter@registerAction');

Route::get('/login', 'Shelter@login');
Route::post('/login_action', 'Shelter@loginAction');

Route::get('/logout', 'Shelter@logout');

Route::get('/adopt/{id}', 'Shelter@adopt');

Route::get('/mypets', 'Shelter@myPets');