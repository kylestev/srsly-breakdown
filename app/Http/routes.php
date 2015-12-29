<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/api/continents/{year?}', function ($year = 2015)
{
	return get_summary($year);
});

Route::get('/{year?}', function ($year = 2015)
{
	return view('main')
            ->with('year', $year)
			->with('continents', get_summary($year));
});
