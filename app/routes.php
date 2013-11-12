<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('main');
});

// filter by card name
Route::get('card/{id}', function($id) {
	return Card::find($id);
});

// filter by card name
Route::get('cards/{name}', function($name) {
	return Card::getCards($name);
});

// filter by card name and class
Route::get('cards/{name}/{class}', function($name, $class) {
	return Card::getCards($name, $class);
});

Route::resource('arena', 'ArenaController');
