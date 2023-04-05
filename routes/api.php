<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\SiteController;
use App\Http\Controllers\API\TypeController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\ContactController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    //     Route::post('logout', 'logout');
    //     Route::post('refresh', 'refresh');
});
Route::controller(UserController::class)->group(function () {
    Route::get('users', 'index');
    // Route::post('users', 'store');
    Route::get('users/{user}', 'show');
    Route::post('users/{user}', 'update');
    Route::delete('users/{user}', 'destroy');
});


// Route::apiResource("sites", SiteController::class);
Route::controller(SiteController::class)->group(function () {
    Route::get('sites', 'index');
    Route::post('sites', 'store');
    Route::get('sites/{site}', 'show');
    Route::post('sites/{site}', 'update');
    Route::delete('sites/{site}', 'destroy');
    Route::get('sites/type/{type}', 'showSiteType');
});

// Route::apiResource("types", TypeController::class);
Route::controller(TypeController::class)->group(function () {
    Route::get('types', 'index');
    Route::post('types', 'store');
    Route::get('types/{type}', 'show');
    Route::post('types/{type}', 'update');
    Route::delete('types/{type}', 'destroy');
});

// Route::apiResource("events", EventController::class);
Route::controller(EventController::class)->group(function () {
    Route::get('events', 'index');
    Route::post('events', 'store');
    Route::get('events/{event}', 'show');
    Route::post('events/{event}', 'update');
    Route::delete('events/{event}', 'destroy');
});

// Route::apiResource("items", ItemController::class);
Route::controller(ItemController::class)->group(function () {
    Route::get('items', 'index');
    Route::post('items', 'store');
    Route::get('items/{item}', 'show');
    Route::post('items/{item}', 'update');
    Route::delete('items/{item}', 'destroy');
});

// Route::apiResource("comments", CommentController::class);
Route::controller(CommentController::class)->group(function () {
    Route::get('comments', 'index');
    Route::post('comments', 'store');
    Route::get('comments/{comment}', 'show');
    Route::post('comments/{comment}', 'update');
    Route::delete('comments/{comment}', 'destroy');
});

Route::apiResource("contacts", ContactController::class);
// Route::controller(labelController::class)->group(function () {
//     Route::get('labels', 'index');
//     Route::post('labels', 'store');
//     Route::get('labels/{label}', 'show');
//     Route::post('labels/{label}', 'update');
//     Route::delete('labels/{label}', 'destroy');
// });
