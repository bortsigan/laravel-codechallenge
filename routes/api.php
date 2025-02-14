<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

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

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::middleware(['auth:sanctum'])->post('/logout', [LoginController::class, 'logout']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/vouchers/generate', [VoucherController::class, 'generate']);
    Route::get('/vouchers', [VoucherController::class, 'index']);
    Route::delete('/voucher/delete/{voucher_code}', [VoucherController::class, 'destroy']);
});