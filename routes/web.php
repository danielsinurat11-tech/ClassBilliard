<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryAdminController;
use App\Http\Controllers\MenuAdminController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/testimonial/submit', [HomeController::class, 'submitTestimonial'])->name('testimonial.submit');

// Logout Route (accessible without shift time check)
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout')->middleware('auth.custom');


Route::get('/menu', [App\Http\Controllers\MenuController::class, 'index'])->name('menu');

// Kitchen Routes (Hanya untuk role kitchen)
Route::middleware(['auth.custom', 'role:kitchen', 'check.shift.time'])->group(function () {
    Route::get('/dapur', [App\Http\Controllers\OrderController::class, 'index'])->name('dapur');
    Route::get('/tutup-hari', [App\Http\Controllers\OrderController::class, 'tutupHari'])->name('tutup-hari');
    Route::get('/tutup-hari/struk', [App\Http\Controllers\OrderController::class, 'generateStrukHarian'])->name('tutup-hari.struk');
    Route::post('/tutup-hari/kirim-email', [App\Http\Controllers\OrderController::class, 'sendStrukHarianEmail'])->name('tutup-hari.kirim-email');
    Route::post('/orders/{id}/complete', [App\Http\Controllers\OrderController::class, 'complete'])->name('orders.complete');
Route::get('/orders/active', [App\Http\Controllers\OrderController::class, 'activeOrders'])->name('orders.active');
Route::get('/reports', [App\Http\Controllers\OrderController::class, 'reports'])->name('reports');
Route::get('/reports/category-stats', [App\Http\Controllers\OrderController::class, 'getCategoryStats'])->name('reports.category-stats');
Route::get('/reports/export', [App\Http\Controllers\OrderController::class, 'exportExcel'])->name('reports.export');
    Route::post('/reports/send-email', [App\Http\Controllers\OrderController::class, 'sendReportEmail'])->name('reports.send-email');
    Route::get('/test-email', [App\Http\Controllers\OrderController::class, 'testEmail'])->name('test.email');
    Route::get('/pengaturan-audio', function () {
        return view('dapur.pengaturan-audio');
    })->name('pengaturan-audio');
    Route::get('/notification-sounds/active', [App\Http\Controllers\NotificationSoundController::class, 'getActive'])->name('notification-sounds.active');
    
    // Notification Sounds Management untuk Kitchen
    Route::prefix('notification-sounds')->name('notification-sounds.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationSoundController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\NotificationSoundController::class, 'store'])->name('store');
        Route::delete('/{id}', [App\Http\Controllers\NotificationSoundController::class, 'destroy'])->name('destroy');
    });
});

// Public order route (untuk customer membuat pesanan)
Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{id}/data', [App\Http\Controllers\OrderController::class, 'getOrderData'])->name('orders.data');
Route::put('/orders/{id}', [App\Http\Controllers\OrderController::class, 'update'])->name('orders.update');
Route::post('/orders/{id}/add-item', [App\Http\Controllers\OrderController::class, 'addItem'])->name('orders.add-item');
Route::delete('/orders/{id}/remove-item/{itemId}', [App\Http\Controllers\OrderController::class, 'removeItem'])->name('orders.remove-item');
Route::post('/orders/{id}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');

// ========================================
// SUPER_ADMIN + ADMIN ROUTES 
// All admin and super_admin access /admin dashboard
// Controllers/Policies control what each role can actually do
// ========================================
Route::prefix('admin')->name('admin.')->middleware(['auth.custom', 'role:super_admin|admin', 'check.shift.time'])->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
    
    // Profile routes (all roles)
    Route::controller(AdminController::class)->group(function () {
        Route::get('/profile', 'profileEdit')->name('profile.edit');
        Route::put('/profile/update', 'profileUpdate')->name('profile.update');
        Route::put('/profile/password', 'profilePassword')->name('profile.password');
    });
    
    // CMS Routes (super_admin & admin - add authorization in controller)
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/hero', [App\Http\Controllers\AdminController::class, 'heroIndex'])->name('hero');
        Route::post('/hero', [App\Http\Controllers\AdminController::class, 'heroUpdate'])->name('hero.update');
        
        Route::get('/tentang-kami', [App\Http\Controllers\AdminController::class, 'tentangKamiIndex'])->name('tentang-kami');
        Route::post('/tentang-kami', [App\Http\Controllers\AdminController::class, 'tentangKamiUpdate'])->name('tentang-kami.update');
        
        Route::get('/about-founder', [App\Http\Controllers\AdminController::class, 'aboutFounderIndex'])->name('about-founder');
        Route::post('/about-founder', [App\Http\Controllers\AdminController::class, 'aboutFounderUpdate'])->name('about-founder.update');
        
        Route::get('/keunggulan-fasilitas', [App\Http\Controllers\AdminController::class, 'keunggulanFasilitasIndex'])->name('keunggulan-fasilitas');
        Route::post('/keunggulan-fasilitas', [App\Http\Controllers\AdminController::class, 'keunggulanFasilitasStore'])->name('keunggulan-fasilitas.store');
        Route::post('/keunggulan-fasilitas/{id}', [App\Http\Controllers\AdminController::class, 'keunggulanFasilitasUpdate'])->name('keunggulan-fasilitas.update');
        Route::delete('/keunggulan-fasilitas/{id}', [App\Http\Controllers\AdminController::class, 'keunggulanFasilitasDestroy'])->name('keunggulan-fasilitas.destroy');
        
        Route::get('/portfolio-achievement', [App\Http\Controllers\AdminController::class, 'portfolioAchievementIndex'])->name('portfolio-achievement');
        Route::post('/portfolio-achievement', [App\Http\Controllers\AdminController::class, 'portfolioAchievementStore'])->name('portfolio-achievement.store');
        Route::post('/portfolio-achievement/{id}', [App\Http\Controllers\AdminController::class, 'portfolioAchievementUpdate'])->name('portfolio-achievement.update');
        Route::delete('/portfolio-achievement/{id}', [App\Http\Controllers\AdminController::class, 'portfolioAchievementDestroy'])->name('portfolio-achievement.destroy');
        
        Route::get('/tim-kami', [App\Http\Controllers\AdminController::class, 'timKamiIndex'])->name('tim-kami');
        Route::post('/tim-kami', [App\Http\Controllers\AdminController::class, 'timKamiStore'])->name('tim-kami.store');
        Route::post('/tim-kami/{id}', [App\Http\Controllers\AdminController::class, 'timKamiUpdate'])->name('tim-kami.update');
        Route::delete('/tim-kami/{id}', [App\Http\Controllers\AdminController::class, 'timKamiDestroy'])->name('tim-kami.destroy');
        
        Route::get('/testimoni-pelanggan', [App\Http\Controllers\AdminController::class, 'testimoniPelangganIndex'])->name('testimoni-pelanggan');
        Route::post('/testimoni-pelanggan', [App\Http\Controllers\AdminController::class, 'testimoniPelangganStore'])->name('testimoni-pelanggan.store');
        Route::post('/testimoni-pelanggan/{id}', [App\Http\Controllers\AdminController::class, 'testimoniPelangganUpdate'])->name('testimoni-pelanggan.update');
        Route::delete('/testimoni-pelanggan/{id}', [App\Http\Controllers\AdminController::class, 'testimoniPelangganDestroy'])->name('testimoni-pelanggan.destroy');
        
        Route::get('/event', [App\Http\Controllers\AdminController::class, 'eventIndex'])->name('event');
        Route::post('/event', [App\Http\Controllers\AdminController::class, 'eventStore'])->name('event.store');
        Route::post('/event/{id}', [App\Http\Controllers\AdminController::class, 'eventUpdate'])->name('event.update');
        Route::delete('/event/{id}', [App\Http\Controllers\AdminController::class, 'eventDestroy'])->name('event.destroy');
        
        Route::get('/footer', [App\Http\Controllers\AdminController::class, 'footerIndex'])->name('footer');
        Route::post('/footer', [App\Http\Controllers\AdminController::class, 'footerUpdate'])->name('footer.update');
    });

    // Order Management (super_admin, admin)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\OrderController::class, 'adminIndex'])->name('index');
        Route::get('/check-new', [App\Http\Controllers\OrderController::class, 'checkNewOrders'])->name('check-new');
        Route::post('/{id}/approve', [App\Http\Controllers\OrderController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [App\Http\Controllers\OrderController::class, 'reject'])->name('reject');
        Route::post('/{id}/rekap', [App\Http\Controllers\OrderController::class, 'rekapOrder'])->name('rekap');
        Route::delete('/{id}', [App\Http\Controllers\OrderController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/process-payment', [App\Http\Controllers\OrderController::class, 'processPayment'])->name('process-payment');
        
        // Recap Orders
        Route::get('/recap', [App\Http\Controllers\OrderController::class, 'recapIndex'])->name('recap.index');
        Route::post('/recap', [App\Http\Controllers\OrderController::class, 'recap'])->name('recap');
        Route::get('/recap/{id}', [App\Http\Controllers\OrderController::class, 'recapDetail'])->name('recap.detail');
        Route::put('/recap/{id}', [App\Http\Controllers\OrderController::class, 'updateRecap'])->name('recap.update');
        Route::get('/recap/{id}/export', [App\Http\Controllers\OrderController::class, 'exportRecap'])->name('recap.export');
        Route::post('/recap/{id}/send-email', [App\Http\Controllers\OrderController::class, 'sendRecapEmail'])->name('recap.send-email');
    });

    // Notification Sounds Management
    Route::prefix('notification-sounds')->name('notification-sounds.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationSoundController::class, 'index'])->name('index');
        Route::get('/active', [App\Http\Controllers\NotificationSoundController::class, 'getActive'])->name('active');
        Route::post('/', [App\Http\Controllers\NotificationSoundController::class, 'store'])->name('store');
        Route::post('/{id}/set-active', [App\Http\Controllers\NotificationSoundController::class, 'setActive'])->name('set-active');
        Route::delete('/{id}', [App\Http\Controllers\NotificationSoundController::class, 'destroy'])->name('destroy');
    });

    // Menu Management (super_admin & admin only)
    Route::resource('menus', MenuAdminController::class)->names([
        'index'   => 'menus.index',
        'create'  => 'menus.create',
        'store'   => 'menus.store',
        'edit'    => 'menus.edit',
        'update'  => 'menus.update',
        'destroy' => 'menus.destroy',
    ]);

    // Category Management (super_admin & admin only)
    Route::resource('categories', CategoryAdminController::class)->names([
        'index'   => 'categories.index',
        'create'  => 'categories.create',
        'store'   => 'categories.store',
        'edit'    => 'categories.edit',
        'update'  => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);

    // Table Management (super_admin, admin)
    Route::prefix('tables')->name('tables.')->group(function () {
        Route::get('/', [App\Http\Controllers\TableController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\TableController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\TableController::class, 'store'])->name('store');
        Route::post('/{id}/generate-qr', [App\Http\Controllers\TableController::class, 'generateQR'])->name('generate-qr');
        Route::get('/{id}/barcode', [App\Http\Controllers\TableController::class, 'showBarcode'])->name('barcode');
        Route::delete('/{id}', [App\Http\Controllers\TableController::class, 'destroy'])->name('destroy');
    });
    
    // Redirect old barcode route to tables
    Route::get('/barcode', function () {
        return redirect()->route('admin.tables.index');
    })->name('barcode.index');

    // User Management - check user.view permission
    Route::prefix('manage-users')->name('manage-users.')->middleware('permission:user.view')->group(function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('update');
        Route::patch('/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
    });

    // Permission Management (super_admin only)
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/select-user', [App\Http\Controllers\PermissionController::class, 'selectUser'])->name('select-user');
        Route::get('/{userId}/manage', [App\Http\Controllers\PermissionController::class, 'managePermissions'])->name('manage');
        Route::post('/{userId}/update', [App\Http\Controllers\PermissionController::class, 'updatePermissions'])->name('update');
        Route::post('/{userId}/toggle', [App\Http\Controllers\PermissionController::class, 'togglePermission'])->name('toggle');
    });
});
