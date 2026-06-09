<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SavingsGoalController;
use App\Http\Controllers\BrankasController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware(['auth'])->group(function () {    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('incomes', IncomeController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('savings', SavingsGoalController::class);
    Route::resource('brankas', BrankasController::class);
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::resource('wallets', WalletController::class)->except(['show', 'create', 'edit']);
    Route::post('/wallets/transfer', [WalletController::class, 'transfer'])->name('wallets.transfer');
});

Route::prefix('guest')->name('guest.')->group(function () {
    Route::get('/', [App\Http\Controllers\GuestController::class, 'index'])->name('dashboard');
    Route::get('/incomes', [App\Http\Controllers\GuestController::class, 'incomeIndex'])->name('incomes');
    Route::post('/incomes', [App\Http\Controllers\GuestController::class, 'incomeStore'])->name('incomes.store');
    Route::delete('/incomes/{id}', [App\Http\Controllers\GuestController::class, 'incomeDestroy'])->name('incomes.destroy');
    Route::get('/expenses', [App\Http\Controllers\GuestController::class, 'expenseIndex'])->name('expenses');
    Route::post('/expenses', [App\Http\Controllers\GuestController::class, 'expenseStore'])->name('expenses.store');
    Route::delete('/expenses/{id}', [App\Http\Controllers\GuestController::class, 'expenseDestroy'])->name('expenses.destroy');
    Route::get('/brankas', [App\Http\Controllers\GuestController::class, 'brankasIndex'])->name('brankas');
    Route::post('/brankas', [App\Http\Controllers\GuestController::class, 'brankasStore'])->name('brankas.store');
    Route::delete('/brankas/{id}', [App\Http\Controllers\GuestController::class, 'brankasDestroy'])->name('brankas.destroy');
    Route::post('/reset', [App\Http\Controllers\GuestController::class, 'reset'])->name('reset');
});

require __DIR__.'/auth.php';