<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProblemSetter;
use App\Http\Controllers\ProblemController;

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

Auth::routes(['verify' => true]);

Route::get('/', function(){
    return view('index');
});

Route::middleware(['auth'])->group(function (){

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/practice', [HomeController::class, 'category'])->name('practice');
    Route::get('/practice/{category_name}', [HomeController::class, 'practice_problem_list']);
    Route::get('/problem/{problem_id}', [HomeController::class, 'practice_problem']);
    Route::get('/submissions', [HomeController::class, 'submissions'])->name('submissions');
    Route::get('/submission/{submission_id}', [HomeController::class, 'submission_details']);


    Route::post('/submit', [HomeController::class, 'submit']);

    // group for profile
    Route::prefix('profile')->group(function (){
        Route::get('/{username}', [HomeController::class, 'profile']);
        Route::Post('/upload', [HomeController::class, 'upload_profile_pic']);
        Route::Post('/country', [HomeController::class, 'update_country']);
        Route::Post('/institution', [HomeController::class, 'update_institution']);
    });

});


Route::middleware(['auth','setter'])->prefix('setter')->group(function () {

    Route::get('/', [ProblemController::class, 'index']);
    Route::get('/users', [ProblemSetter::class, 'users']);
    Route::get('/problems', [ProblemController::class, 'problems']);     //my problem list
    Route::get('/create', [ProblemController::class, 'add_problem']);
    Route::get('/images', [ProblemSetter::class, 'problem_images']);
    Route::get('/sub', [ProblemSetter::class, 'getSubmissionsAjax']); // (ajax) setter hide/show the problem from user
    Route::get('/configure/{problem_id}', [ProblemController::class, 'configure_problem']);
    Route::get('/submissions', [ProblemSetter::class, 'submissions']);     // all user submission
    Route::get('/submissions/{problem_id}', [ProblemSetter::class, 'problem_submissions']);    // all submission of this problem

    Route::get('/testcase/download/{tc_id}/{case_no}/{problem_id}', [ProblemSetter::class, 'download_testcase']);
    Route::get('/testcase/delete', [ProblemSetter::class, 'delete_testcase']);  // (ajax) setter hide/show the problem from user

    // post request
    Route::post('/testcase/upload', [ProblemSetter::class, 'upload_testcase']);
    Route::post('/images/upload', [ProblemSetter::class, 'upload_image']);
    Route::post('/search_problem', [ProblemController::class, 'search_problem']); // (ajax)
    Route::post('/parse_markdown', [ProblemSetter::class, 'parse_markdown']); // (ajax request)

    // group for problem
    Route::prefix('problem')->group(function (){

        Route::get('/update/{problem_id}', [ProblemController::class, 'update_problem_description']); // update view
        Route::get('/update_status', [ProblemController::class, 'update_problem_status']); // (ajax) setter hide/show the problem from user
        Route::get('/delete_category', [ProblemSetter::class, 'delete_problem_category']); // (ajax) setter hide/show the problem from user

        // post request
        Route::post('/save', [ProblemController::class, 'save_problem']);
        Route::post('/update', [ProblemController::class, 'update_description']); // update request
        Route::post('/update/category', [ProblemController::class, 'update_problem_category']);
        Route::post('/update/timelimit', [ProblemController::class, 'update_problem_time_limit']);
        Route::post('/update/memorylimit', [ProblemController::class, 'update_problem_memory_limit']);

    });


    // group for category
    Route::prefix('/category')->group(function (){
        Route::get('/', [ProblemSetter::class, 'category']);
        Route::get('/visibility/{id}/{status}', [ProblemSetter::class, 'change_category_visibility']);
        Route::get('/delete/{id}', [ProblemSetter::class, 'delete_category']);
        // post request
        Route::post('/create', [ProblemSetter::class, 'create_category']);
        Route::post('/update', [ProblemSetter::class, 'update_category']);
    });

});
