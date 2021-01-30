<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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
/**
Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/test', 'App\Http\Controllers\TestModel@index');
//Route::get('/user', [UserController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/problems', [HomeController::class, 'category']);
Route::get('/problems/{name}', [HomeController::class, 'problems']);
Route::get('/problem/{id}', [HomeController::class, 'single_problems']);
