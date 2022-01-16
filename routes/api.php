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

Route::group(['middleware' => 'auth:sanctum','Roles:artist','Roles:admin'], function(){
    //albums
    Route::post('add-album', 'App\Http\Controllers\AlbumsController@addAlbum');
    Route::post('album/music', 'App\Http\Controllers\AlbumsController@addSongtoAlbum');

    //upload
    Route::post('upload', 'App\Http\Controllers\MusicController@uploadMusic');

    //Singles
    Route::post('single/music', 'App\Http\Controllers\MusicController@postSingleMusic');
    Route::put('single/music/{id}', 'App\Http\Controllers\MusicController@putSingleMusic');

    Route::put('music/{id}/change-status', 'App\Http\Controllers\MusicController@changeStatusMusic');

});

Route::group(['middleware' => 'auth:sanctum','Roles:admin'], function(){
    Route::post('admin/artist', 'App\Http\Controllers\ArtistController@addArtist');
    Route::get('admin/artist', 'App\Http\Controllers\ArtistController@getallArtist');
    Route::get('admin/albums', 'App\Http\Controllers\AlbumsController@getAllAlbums');
});



Route::get('music/latest', 'App\Http\Controllers\MusicController@lastestMusic');
Route::get('album/latest', 'App\Http\Controllers\AlbumsController@lastestAlbums');

//User Routes
Route::post('register/{role}', 'App\Http\Controllers\UserController@Register');
Route::post('login', 'App\Http\Controllers\UserController@loginUser');
Route::post('check-token', 'App\Http\Controllers\UserController@checkToken');