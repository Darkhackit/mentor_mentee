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

Route::post('auth/login',[\App\Http\Controllers\AuthController::class,'login']);
Route::get('auth/refresh',[\App\Http\Controllers\AuthController::class,'refresh'])->middleware('auth:api');

Route::post('add-mentee',[\App\Http\Controllers\MenteeController::class,'create']);
Route::post('update-mentee',[\App\Http\Controllers\MenteeController::class,'update']);
Route::get('mentee',[\App\Http\Controllers\MenteeController::class,'index']);
Route::get('edit/{id}',[\App\Http\Controllers\MenteeController::class,'edit']);
Route::get('delete/{id}',[\App\Http\Controllers\MenteeController::class,'delete']);


Route::post('add-mentor',[\App\Http\Controllers\MentorController::class,'create']);
Route::post('update-mentor',[\App\Http\Controllers\MentorController::class,'update']);
Route::get('mentor',[\App\Http\Controllers\MentorController::class,'index']);
Route::get('mentor/edit/{id}',[\App\Http\Controllers\MentorController::class,'edit']);
Route::get('mentor/delete/{id}',[\App\Http\Controllers\MentorController::class,'delete']);
Route::get('get_mentees',[\App\Http\Controllers\MentorController::class,'getMentees']);



Route::get('mentee_without_id',[\App\Http\Controllers\MenteeController::class,'mentee_without_mentor']);
Route::post('shuffle',[\App\Http\Controllers\MentorController::class,'shuffle']);
Route::post('reset',[\App\Http\Controllers\MentorController::class,'reset']);


Route::post('mentee_login',[\App\Http\Controllers\MenteeController::class,'login']);
