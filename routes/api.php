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
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('current-user', 'currentUser')->middleware('auth:api');
});
Route::controller(UserController::class)->group(function () {
    Route::get('users', 'index')->middleware('auth:api');
    // Route::post('users', 'store');
    Route::get('users/{user}', 'show')->middleware('auth:api');
    Route::post('users/{user}', 'update')->middleware('auth:api');
    Route::delete('users/{user}', 'destroy')->middleware('auth:api');
});


// Route::apiResource("sites", SiteController::class);
Route::controller(SiteController::class)->group(function () {
    Route::get('sites', 'index');
    Route::post('sites', 'store')->middleware('auth:api');
    Route::get('sites/{site}', 'show');
    Route::post('sites/{site}', 'update')->middleware('auth:api');
    Route::delete('sites/{site}', 'destroy')->middleware('auth:api');
    Route::get('sites/type/{type}', 'showSiteType');
});

// Route::apiResource("types", TypeController::class);
Route::controller(TypeController::class)->group(function () {
    Route::get('types', 'index');
    Route::post('types', 'store')->middleware('auth:api');
    Route::get('types/{type}', 'show');
    Route::post('types/{type}', 'update')->middleware('auth:api');
    Route::delete('types/{type}', 'destroy')->middleware('auth:api');
});

// Route::apiResource("events", EventController::class);
Route::controller(EventController::class)->group(function () {
    Route::get('events', 'index');
    Route::post('events', 'store')->middleware('auth:api');
    Route::get('events/{event}', 'show');
    Route::post('events/{event}', 'update')->middleware('auth:api');
    Route::delete('events/{event}', 'destroy')->middleware('auth:api');
});

// Route::apiResource("items", ItemController::class);
Route::controller(ItemController::class)->group(function () {
    Route::get('items', 'index');
    Route::post('items', 'store')->middleware('auth:api');
    Route::get('items/{item}', 'show');
    Route::post('items/{item}', 'update')->middleware('auth:api');
    Route::delete('items/{item}', 'destroy')->middleware('auth:api');
});

// Route::apiResource("comments", CommentController::class);
Route::controller(CommentController::class)->group(function () {
    Route::get('comments', 'index');
    Route::post('comments', 'store')->middleware('auth:api');
    Route::get('comments/{comment}', 'show');
    // Route::post('comments/{comment}', 'update')->middleware('auth:api');
    Route::delete('comments/{comment}', 'destroy')->middleware('auth:api');
    Route::get('comments/user/{user}', 'indexComUser')->middleware('auth:api');
});

// Route::apiResource("contacts", ContactController::class);
Route::controller(ContactController::class)->group(function () {
    Route::get('contacts', 'index')->middleware('auth:api');
    Route::post('contacts', 'store');
    Route::get('contacts/{contact}', 'show')->middleware('auth:api');
    // Route::post('contacts/{contact}', 'update');
    Route::delete('contacts/{contact}', 'destroy')->middleware('auth:api');
});
