<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController; // Untuk manajemen staff

/*
|--------------------------------------------------------------------------
| 1. Public Routes (Pelanggan / Guest)
|--------------------------------------------------------------------------
| Pelanggan tidak perlu login dan tidak memiliki user_id.
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/menu', function () {
    return view('menu');
})->name('menu');

// Pelanggan mengirim pesanan
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');


/*
|--------------------------------------------------------------------------
| 2. Kitchen Routes (Staff Dapur)
|--------------------------------------------------------------------------
| Hanya bisa diakses oleh user dengan role 'kitchen' atau 'admin'.
*/
Route::middleware(['auth', 'role:kitchen'])->group(function () {
    Route::get('/dapur', [OrderController::class, 'index'])->name('dapur');
    Route::post('/orders/{id}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::get('/orders/active', [OrderController::class, 'activeOrders'])->name('orders.active');
});


/*
|--------------------------------------------------------------------------
| 3. Admin Routes (Pemilik / Manager)
|--------------------------------------------------------------------------
| Hanya bisa diakses oleh user dengan role 'admin'.
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard & Reports
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/reports', [OrderController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [OrderController::class, 'exportExcel'])->name('reports.export');

    // FITUR UTAMA: Manajemen User (Admin buat Admin/Kitchen)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Manajemen Konten Landing Page (Hero, Tentang Kami, dll)
    Route::controller(AdminController::class)->group(function () {
        Route::get('/hero', 'heroIndex')->name('hero');
        Route::post('/hero', 'heroUpdate')->name('hero.update');

        Route::get('/tentang-kami', 'tentangKamiIndex')->name('tentang-kami');
        Route::post('/tentang-kami', 'tentangKamiUpdate')->name('tentang-kami.update');

        Route::get('/about-founder', 'aboutFounderIndex')->name('about-founder');
        Route::post('/about-founder', 'aboutFounderUpdate')->name('about-founder.update');

        Route::get('/keunggulan-fasilitas', 'keunggulanFasilitasIndex')->name('keunggulan-fasilitas');
        Route::post('/keunggulan-fasilitas', 'keunggulanFasilitasStore')->name('keunggulan-fasilitas.store');
        Route::post('/keunggulan-fasilitas/{id}', 'keunggulanFasilitasUpdate')->name('keunggulan-fasilitas.update');
        Route::delete('/keunggulan-fasilitas/{id}', 'keunggulanFasilitasDestroy')->name('keunggulan-fasilitas.destroy');

        Route::get('/portfolio-achievement', 'portfolioAchievementIndex')->name('portfolio-achievement');
        Route::post('/portfolio-achievement', 'portfolioAchievementStore')->name('portfolio-achievement.store');
        Route::post('/portfolio-achievement/{id}', 'portfolioAchievementUpdate')->name('portfolio-achievement.update');
        Route::delete('/portfolio-achievement/{id}', 'portfolioAchievementDestroy')->name('portfolio-achievement.destroy');

        Route::get('/tim-kami', 'timKamiIndex')->name('tim-kami');
        Route::post('/tim-kami', 'timKamiStore')->name('tim-kami.store');
        Route::post('/tim-kami/{id}', 'timKamiUpdate')->name('tim-kami.update');
        Route::delete('/tim-kami/{id}', 'timKamiDestroy')->name('tim-kami.destroy');

        Route::get('/testimoni-pelanggan', 'testimoniPelangganIndex')->name('testimoni-pelanggan');
        Route::post('/testimoni-pelanggan', 'testimoniPelangganStore')->name('testimoni-pelanggan.store');
        Route::post('/testimoni-pelanggan/{id}', 'testimoniPelangganUpdate')->name('testimoni-pelanggan.update');
        Route::delete('/testimoni-pelanggan/{id}', 'testimoniPelangganDestroy')->name('testimoni-pelanggan.destroy');

        Route::get('/event', 'eventIndex')->name('event');
        Route::post('/event', 'eventStore')->name('event.store');
        Route::post('/event/{id}', 'eventUpdate')->name('event.update');
        Route::delete('/event/{id}', 'eventDestroy')->name('event.destroy');

        Route::get('/footer', 'footerIndex')->name('footer');
        Route::post('/footer', 'footerUpdate')->name('footer.update');
    });
});
