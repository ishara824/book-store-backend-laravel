<?php

use App\Http\Controllers\API\V1\AdminController;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\AuthorController;
use App\Http\Controllers\API\V1\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [AuthController::class, 'registerAuthor']);
Route::post('login', [AuthController::class, 'authenticateUser']);
Route::get('books', [BookController::class, 'index']);
Route::get('search-books', [BookController::class, 'searchBooks']);

Route::group(['middleware' => 'auth:api'], function() {
    Route::group(['middleware' => 'role:AUTHOR'], function() {
        Route::get('get-books-by-author', [AuthorController::class, 'getBooksByAuthor']);
        Route::post('books', [BookController::class, 'store']);
    });

    Route::group(['middleware' => 'role:ADMIN'], function() {
        Route::get('authors', [AdminController::class, 'getAuthors']);
        Route::post('activate-author', [AdminController::class, 'activateAuthor']);
        Route::post('deactivate-author', [AdminController::class, 'deactivateAuthor']);
    });

    Route::post('logout', [AdminController::class, 'logut']);
});

