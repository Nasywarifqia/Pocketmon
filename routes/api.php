<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\IncomeController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\BrankasController;
use App\Http\Controllers\Api\TransactionController;

// Auth routes (public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::get('/profile',  [AuthController::class, 'profile']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Wallet
    Route::get('/wallets',             [WalletController::class, 'index']);
    Route::post('/wallets',            [WalletController::class, 'store']);
    Route::put('/wallets/{wallet}',    [WalletController::class, 'update']);
    Route::delete('/wallets/{wallet}', [WalletController::class, 'destroy']);
    Route::post('/wallets/transfer',   [WalletController::class, 'transfer']);

    // Income
    Route::get('/incomes',             [IncomeController::class, 'index']);
    Route::post('/incomes',            [IncomeController::class, 'store']);
    Route::put('/incomes/{income}',    [IncomeController::class, 'update']);
    Route::delete('/incomes/{income}', [IncomeController::class, 'destroy']);

    // Expense
    Route::get('/expenses',              [ExpenseController::class, 'index']);
    Route::post('/expenses',             [ExpenseController::class, 'store']);
    Route::put('/expenses/{expense}',    [ExpenseController::class, 'update']);
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy']);

    // Brankas
    Route::get('/brankas',         [BrankasController::class, 'index']);
    Route::post('/brankas',        [BrankasController::class, 'store']);
    Route::put('/brankas/{id}',    [BrankasController::class, 'update']);
    Route::delete('/brankas/{id}', [BrankasController::class, 'destroy']);

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
});