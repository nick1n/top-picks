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

// TODO: filter by class
Route::get('cards/{any}', function($name) {
	return Card::where('name', 'like', "%$name%")->get();
});

Route::resource('arena', 'ArenaController');
