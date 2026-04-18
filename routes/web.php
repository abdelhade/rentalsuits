<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SafeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function() {
    Route::resource('users', UserController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('safes', SafeController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/reports/ledger', function () {
        return view('reports.ledger');
    })->name('reports.ledger');

    Route::get('/api/calendar/events', [DashboardController::class, 'events']);
    Route::post('/api/transactions/store', [DashboardController::class, 'storeTransaction']);
    Route::get('/api/reports/ledger', [ReportController::class, 'ledger']);
});


