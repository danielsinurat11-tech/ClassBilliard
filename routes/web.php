<?php

use App\Http\Controllers\CategoryAdminController;
use App\Http\Controllers\MenuAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/menu', function () {
    return view('menu');
})->name('menu');

// Kitchen Routes (Hanya untuk role kitchen)
Route::middleware(['auth.custom', 'role:kitchen'])->group(function () {
    Route::get('/dapur', [App\Http\Controllers\OrderController::class, 'index'])->name('dapur');
    Route::post('/orders/{id}/complete', [App\Http\Controllers\OrderController::class, 'complete'])->name('orders.complete');
    Route::get('/orders/active', [App\Http\Controllers\OrderController::class, 'activeOrders'])->name('orders.active');
    Route::get('/reports', [App\Http\Controllers\OrderController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [App\Http\Controllers\OrderController::class, 'exportExcel'])->name('reports.export');
    Route::post('/reports/send-email', [App\Http\Controllers\OrderController::class, 'sendReportEmail'])->name('reports.send-email');
    Route::get('/test-email', [App\Http\Controllers\OrderController::class, 'testEmail'])->name('test.email');
});

// Public order route (untuk customer membuat pesanan)
Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');

// Admin Routes (Hanya untuk role admin)
Route::prefix('admin')->name('admin.')->middleware(['auth.custom', 'role:admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');

    // Hero Section
    Route::get('/hero', [App\Http\Controllers\AdminController::class, 'heroIndex'])->name('hero');
    Route::post('/hero', [App\Http\Controllers\AdminController::class, 'heroUpdate'])->name('hero.update');

    // Tentang Kami
    Route::get('/tentang-kami', [App\Http\Controllers\AdminController::class, 'tentangKamiIndex'])->name('tentang-kami');
    Route::post('/tentang-kami', [App\Http\Controllers\AdminController::class, 'tentangKamiUpdate'])->name('tentang-kami.update');

    // About Founder
    Route::get('/about-founder', [App\Http\Controllers\AdminController::class, 'aboutFounderIndex'])->name('about-founder');
    Route::post('/about-founder', [App\Http\Controllers\AdminController::class, 'aboutFounderUpdate'])->name('about-founder.update');

    // Keunggulan Fasilitas
    Route::get('/keunggulan-fasilitas', [App\Http\Controllers\AdminController::class, 'keunggulanFasilitasIndex'])->name('keunggulan-fasilitas');
    Route::post('/keunggulan-fasilitas', [App\Http\Controllers\AdminController::class, 'keunggulanFasilitasStore'])->name('keunggulan-fasilitas.store');
    Route::post('/keunggulan-fasilitas/{id}', [App\Http\Controllers\AdminController::class, 'keunggulanFasilitasUpdate'])->name('keunggulan-fasilitas.update');
    Route::delete('/keunggulan-fasilitas/{id}', [App\Http\Controllers\AdminController::class, 'keunggulanFasilitasDestroy'])->name('keunggulan-fasilitas.destroy');

    // Portfolio Achievement
    Route::get('/portfolio-achievement', [App\Http\Controllers\AdminController::class, 'portfolioAchievementIndex'])->name('portfolio-achievement');
    Route::post('/portfolio-achievement', [App\Http\Controllers\AdminController::class, 'portfolioAchievementStore'])->name('portfolio-achievement.store');
    Route::post('/portfolio-achievement/{id}', [App\Http\Controllers\AdminController::class, 'portfolioAchievementUpdate'])->name('portfolio-achievement.update');
    Route::delete('/portfolio-achievement/{id}', [App\Http\Controllers\AdminController::class, 'portfolioAchievementDestroy'])->name('portfolio-achievement.destroy');

    // Tim Kami
    Route::get('/tim-kami', [App\Http\Controllers\AdminController::class, 'timKamiIndex'])->name('tim-kami');
    Route::post('/tim-kami', [App\Http\Controllers\AdminController::class, 'timKamiStore'])->name('tim-kami.store');
    Route::post('/tim-kami/{id}', [App\Http\Controllers\AdminController::class, 'timKamiUpdate'])->name('tim-kami.update');
    Route::delete('/tim-kami/{id}', [App\Http\Controllers\AdminController::class, 'timKamiDestroy'])->name('tim-kami.destroy');

    // Testimoni Pelanggan
    Route::get('/testimoni-pelanggan', [App\Http\Controllers\AdminController::class, 'testimoniPelangganIndex'])->name('testimoni-pelanggan');
    Route::post('/testimoni-pelanggan', [App\Http\Controllers\AdminController::class, 'testimoniPelangganStore'])->name('testimoni-pelanggan.store');
    Route::post('/testimoni-pelanggan/{id}', [App\Http\Controllers\AdminController::class, 'testimoniPelangganUpdate'])->name('testimoni-pelanggan.update');
    Route::delete('/testimoni-pelanggan/{id}', [App\Http\Controllers\AdminController::class, 'testimoniPelangganDestroy'])->name('testimoni-pelanggan.destroy');

    // Event
    Route::get('/event', [App\Http\Controllers\AdminController::class, 'eventIndex'])->name('event');
    Route::post('/event', [App\Http\Controllers\AdminController::class, 'eventStore'])->name('event.store');
    Route::post('/event/{id}', [App\Http\Controllers\AdminController::class, 'eventUpdate'])->name('event.update');
    Route::delete('/event/{id}', [App\Http\Controllers\AdminController::class, 'eventDestroy'])->name('event.destroy');

    // Footer
    Route::get('/footer', [App\Http\Controllers\AdminController::class, 'footerIndex'])->name('footer');
    Route::post('/footer', [App\Http\Controllers\AdminController::class, 'footerUpdate'])->name('footer.update');

    // User Management
    Route::prefix('manage-users')->name('manage-users.')->group(function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('update');
        Route::patch('/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
    });

    // Order Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\OrderController::class, 'adminIndex'])->name('index');
        Route::post('/{id}/approve', [App\Http\Controllers\OrderController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [App\Http\Controllers\OrderController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [App\Http\Controllers\OrderController::class, 'destroy'])->name('destroy');
    });

    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [App\Http\Controllers\AdminController::class, 'profileEdit'])->name('edit');
        Route::put('/', [App\Http\Controllers\AdminController::class, 'profileUpdate'])->name('update');
        Route::put('/password', [App\Http\Controllers\AdminController::class, 'profilePassword'])->name('password');
    });

    // Menu Management (CRUD)
    Route::resource('menus', MenuAdminController::class)->names([
        'index'   => 'menus.index',
        'create'  => 'menus.create',
        'store'   => 'menus.store',
        'edit'    => 'menus.edit',
        'update'  => 'menus.update',
        'destroy' => 'menus.destroy',
    ]);

    // Category Management (CRUD)
    Route::resource('categories', CategoryAdminController::class)->names([
        'index'   => 'categories.index',
        'create'  => 'categories.create',
        'store'   => 'categories.store',
        'edit'    => 'categories.edit',
        'update'  => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);
});
