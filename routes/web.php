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
    return 'Laravel Api Test';
});

// Competitions
Route::get('/competitions', 'CompetitionController@index')->name('competitions');
Route::get('/competitions/{competition}', 'CompetitionController@show')->name('competitions.show');

//Teams
Route::get('/teams', 'TeamController@index')->name('teams');
Route::get('/teams/{team}', 'TeamController@show')->name('teams.show');

//Players
Route::get('/players', 'PlayerController@index')->name('players');
