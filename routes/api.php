<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/redirect/{shot_url}', 'App\Http\Controllers\Api\UrlShortenerController@redirectShortUrl');

Route::get('/top-100','App\Http\Controllers\Api\UrlShortenerController@topTenUrlVisits');
Route::post('/generate/short_url','App\Http\Controllers\Api\UrlShortenerController@short');
