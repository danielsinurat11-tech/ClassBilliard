<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/menu', function() {
    return view('menu');
})->name('menu');

Route::get('/dapur', [App\Http\Controllers\OrderController::class, 'index'])->name('dapur');
Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
Route::post('/orders/{id}/complete', [App\Http\Controllers\OrderController::class, 'complete'])->name('orders.complete');
Route::get('/orders/active', [App\Http\Controllers\OrderController::class, 'activeOrders'])->name('orders.active');
Route::get('/reports', [App\Http\Controllers\OrderController::class, 'reports'])->name('reports');
Route::get('/reports/export', [App\Http\Controllers\OrderController::class, 'exportExcel'])->name('reports.export');