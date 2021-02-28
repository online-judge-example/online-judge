<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProblemSetter;
use App\Http\Controllers\BasicController;

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



Auth::routes(['verify' => true]);

Route::get('/', function(){
    return view('index');
});


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/practice', [HomeController::class, 'category'])->name('practice');
Route::get('/practice/{category_name}', [HomeController::class, 'practice_problem_list']);
Route::get('/problem/{problem_id}', [HomeController::class, 'practice_problem']);
Route::get('/submissions', [HomeController::class, 'submissions'])->name('submissions');
Route::get('/submission/{submission_id}', [HomeController::class, 'submission_details']);
Route::get('/profile/{username}', [HomeController::class, 'profile']);
Route::Post('/profile/upload', [HomeController::class, 'upload_profile_pic']);
Route::Post('/profile/country', [HomeController::class, 'update_country']);
Route::Post('/profile/institution', [HomeController::class, 'update_institution']);

Route::post('/submit', [HomeController::class, 'submit']);

/// problem setter
    /// get request
Route::get('/setter', [ProblemSetter::class, 'index']);
Route::get('/setter/users', [ProblemSetter::class, 'users']);
Route::get('/setter/problems/', [ProblemSetter::class, 'problems']);     //my problem list
Route::get('/setter/submissions/{problem_id}', [ProblemSetter::class, 'problem_submissions']);     // all submission of this problem
Route::get('/setter/submissions', [ProblemSetter::class, 'submissions']);     // all submission of this problem

Route::get('/setter/create', [ProblemSetter::class, 'add_problem']);
Route::get('/setter/images', [ProblemSetter::class, 'problem_images']);
Route::get('/setter/configure/{problem_id}', [ProblemSetter::class, 'configure_problem']);
Route::get('/setter/problem/update/{problem_id}', [ProblemSetter::class, 'update_problem_description']); // update view
Route::get('/setter/testcase/download/{tc_id}/{case_no}/{problem_id}', [ProblemSetter::class, 'download_testcase']);
Route::get('/setter/category', [ProblemSetter::class, 'category']);
Route::get('/setter/category/visibility/{id}/{status}', [ProblemSetter::class, 'change_category_visibility']);
Route::get('/setter/category/delete/{id}', [ProblemSetter::class, 'delete_category']);

    /// post request
Route::post('/setter/category/create', [ProblemSetter::class, 'create_category']);
Route::post('/setter/category/update', [ProblemSetter::class, 'update_category']);
Route::post('/setter/problem/save', [ProblemSetter::class, 'save_problem']);
Route::post('/setter/problem/update/category', [ProblemSetter::class, 'update_problem_category']);
Route::post('/setter/problem/update/timelimit', [ProblemSetter::class, 'update_problem_timelimit']);
Route::post('/setter/problem/update/memorylimit', [ProblemSetter::class, 'update_problem_memorylimit']);
Route::post('/setter/testcase/upload', [ProblemSetter::class, 'upload_testcase']);
Route::post('/setter/images/upload', [ProblemSetter::class, 'upload_image']);
Route::post('/setter/problem/update/', [ProblemSetter::class, 'update_description']); // update request


/// ajax request
Route::post('/parse_markdown', [ProblemSetter::class, 'parse_markdown']);
Route::get('/setter/problem/update_status', [ProblemSetter::class, 'update_problem_status']); /// setter hide/show the problem from user
Route::get('/setter/sub', [ProblemSetter::class, 'getSubmissionsAjax']); /// setter hide/show the problem from user
Route::get('/setter/problem/delete_category', [ProblemSetter::class, 'delete_problem_category']); /// setter hide/show the problem from user
Route::get('/setter/testcase/delete', [ProblemSetter::class, 'delete_testcase']); /// setter hide/show the problem from user
Route::post('/setter/search_problem/', [ProblemSetter::class, 'search_problem']);


