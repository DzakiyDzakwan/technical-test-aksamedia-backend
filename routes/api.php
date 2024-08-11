<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NilaiController;
use App\Models\Employee;
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

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('login',  [AuthController::class, 'login'])->middleware('guest')->name('login');

    Route::middleware(['auth:sanctum', "auth"])->group(function () {

        Route::apiResource('divisions', DivisionController::class);
        Route::apiResource('employees', EmployeeController::class);

        Route::post('logout',  [AuthController::class, 'logout'])->name('logout');

        Route::get("/file/avatar/{filename}", function () {
            return "test";
        })->name('file.avatar');
    });

    Route::get('/nilaiRT', [NilaiController::class, "nilaiRT"])->name('nilaiRT');
    Route::get('/nilaiST', [NilaiController::class, "nilaiST"])->name('nilaiST');
});
