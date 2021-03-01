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

Route::post('/register', 'API\Auth\AuthController@register');
Route::post('/login', 'API\Auth\AuthController@login');

Route::get('/get/incident', 'API\IncidentController@getAllIncident');
Route::post('/create/incident', 'API\IncidentController@createIncident');
