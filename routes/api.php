<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('add-album', 'App\Http\Controllers\AlbumsController@addAlbum');
Route::post('upload', 'App\Http\Controllers\MusicController@uploadMusic');
Route::post('new-music', 'App\Http\Controllers\MusicController@postMusic');
Route::put('music/{id}', 'App\Http\Controllers\MusicController@putMusic');
Route::get('music/latest', 'App\Http\Controllers\MusicController@lastestMusic');
Route::put('music/{id}/change-status', 'App\Http\Controllers\MusicController@changeStatusMusic');
