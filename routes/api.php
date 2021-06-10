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

Route::post('auth/register', '\App\Http\Controllers\ApiTokenController@register');
Route::post('auth/login', '\App\Http\Controllers\ApiTokenController@login');

Route::middleware('auth:sanctum')->post('auth/me', '\App\Http\Controllers\ApiTokenController@me');
Route::middleware('auth:sanctum')->post('auth/logout', '\App\Http\Controllers\ApiTokenController@logout');

Route::middleware('auth:sanctum')->post('tasks/create', '\App\Http\Controllers\TaskController@create');
Route::middleware('auth:sanctum')->get('tasks', '\App\Http\Controllers\TaskController@getTasks');
Route::middleware('auth:sanctum')->get('tasks/{id}', '\App\Http\Controllers\TaskController@getTask');
Route::middleware('auth:sanctum')->post('tasks/update/{id}', '\App\Http\Controllers\TaskController@update');
Route::middleware('auth:sanctum')->get('tasks/delete/{id}', '\App\Http\Controllers\TaskController@delete');
