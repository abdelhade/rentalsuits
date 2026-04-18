<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ReportController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/reports/ledger', function () {
    return view('reports.ledger');
})->name('reports.ledger');

Route::get('/api/calendar/events', [DashboardController::class, 'events']);
Route::post('/api/transactions/store', [DashboardController::class, 'storeTransaction']);
Route::get('/api/reports/ledger', [ReportController::class, 'ledger']);


