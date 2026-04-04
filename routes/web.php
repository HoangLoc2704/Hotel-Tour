<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChucVuController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\LoaiPhongController;
use App\Http\Controllers\PhongController;
use App\Http\Controllers\HuongDanVienController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\LichKhoiHanhController;
use App\Http\Controllers\DichVuController;
use App\Http\Controllers\HDTOURController;
use App\Http\Controllers\HDDichVuController;
use App\Http\Controllers\HDPhongController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AnhPhongController;
use App\Http\Controllers\AnhTourController;
use App\Http\Controllers\AnhDichVuController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

Route::get('/', [AuthController::class, 'index']);
Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
Route::get('/customer/booking', [CustomerController::class, 'booking'])->name('customer.booking');
Route::get('/customer/cart', [CustomerController::class, 'cart'])->name('customer.cart');
Route::post('/customer/cart/remove', [CustomerController::class, 'removeCartItem'])->name('customer.cart.remove');
Route::post('/customer/cart/checkout', [CustomerController::class, 'checkoutCart'])->name('customer.cart.checkout');
Route::get('/customer/login', [CustomerController::class, 'login'])->name('customer.login');
Route::get('/customer/register', [CustomerController::class, 'register'])->name('customer.register');
Route::post('/customer/login', [CustomerController::class, 'submitLogin'])->name('customer.login.submit');
Route::post('/customer/register/send-otp', [CustomerController::class, 'sendRegisterOtp'])->name('customer.register.send-otp');
Route::post('/customer/register', [CustomerController::class, 'submitRegister'])->name('customer.register.submit');
Route::post('/customer/logout', [CustomerController::class, 'logout'])->name('customer.logout');
Route::get('/customer/invoices', [CustomerController::class, 'invoices'])->name('customer.invoices');
Route::get('/customer/invoices/{maHD}', [CustomerController::class, 'showInvoice'])->name('customer.invoices.show');
Route::get('/customer/check-available-rooms', [CustomerController::class, 'checkAvailableRooms'])->name('customer.check-available-rooms');
Route::get('/customer/get-tour-schedules', [CustomerController::class, 'getTourSchedules'])->name('customer.get-tour-schedules');
Route::post('/payment/sepay/webhook', [CustomerController::class, 'sepayWebhook'])
    ->withoutMiddleware([ValidateCsrfToken::class])
    ->name('payment.sepay.webhook');
Route::get('/customer/services/hotel', [CustomerController::class, 'hotelServices'])->name('customer.services.hotel');
Route::get('/customer/services/tour', [CustomerController::class, 'tourServices'])->name('customer.services.tour');
Route::get('/customer/services/addon', [CustomerController::class, 'addonServices'])->name('customer.services.addon');
Route::get('/customer/phong/{maLoai}', [CustomerController::class, 'roomDetail'])->name('customer.room-detail');
Route::get('/customer/tour/{maTour}', [CustomerController::class, 'tourDetail'])->name('customer.tour-detail');
Route::get('/customer/dich-vu/{maDV}', [CustomerController::class, 'serviceDetail'])->name('customer.service-detail');
Route::post('/customer/book-service', [CustomerController::class, 'storeBooking'])->name('customer.book-service');
Route::get('/test-db', [AuthController::class, 'testDb'])->name('test-db');

// Public image management routes: anyone can access directly.
Route::resource('anh-phong', AnhPhongController::class);
Route::resource('anh-tour', AnhTourController::class);
Route::resource('anh-dich-vu', AnhDichVuController::class);

// Authentication Routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postLogin'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Protected Routes (cần đăng nhập)
Route::middleware(['auth.check'])->group(function () {
    Route::get('/admin', [AuthController::class, 'showAdmin'])->name('admin');

    // Quản lý toàn bộ chức năng.
    Route::middleware(['role.check:quan-ly'])->group(function () {
        Route::resource('nhan-vien', NhanVienController::class);
        Route::resource('chuc-vu', ChucVuController::class);
    });

    // Lễ tân: không được quản lý nhân sự, còn lại được truy cập.
    Route::middleware(['role.check:quan-ly,le-tan'])->group(function () {
        Route::resource('khach-hang', KhachHangController::class);
        Route::resource('loai-phong', LoaiPhongController::class);
        Route::resource('phong', PhongController::class);
        Route::resource('huong-dan-vien', HuongDanVienController::class);
        Route::resource('hoa-don', HoaDonController::class);
        Route::resource('dich-vu', DichVuController::class);

        $hdRouteConfigs = [
            [
                'prefix' => 'hd-dich-vu',
                'name' => 'hd-dich-vu',
                'controller' => HDDichVuController::class,
                'params' => '{maHD}/{maDV}',
            ],
            [
                'prefix' => 'hd-phong',
                'name' => 'hd-phong',
                'controller' => HDPhongController::class,
                'params' => '{maHD}/{maPhong}',
            ],
            [
                'prefix' => 'hd-tour',
                'name' => 'hd-tour',
                'controller' => HDTOURController::class,
                'params' => '{maHD}/{maLKH}',
            ],
        ];

        foreach ($hdRouteConfigs as $config) {
            Route::controller($config['controller'])
                ->prefix($config['prefix'])
                ->name($config['name'] . '.')
                ->group(function () use ($config) {
                    $params = $config['params'];

                    Route::get('/', 'index')->name('index');
                    Route::get('/create', 'create')->name('create');
                    Route::post('/', 'store')->name('store');
                    Route::get('/' . $params, 'show')->name('show');
                    Route::get('/' . $params . '/edit', 'edit')->name('edit');
                    Route::put('/' . $params, 'update')->name('update');
                    Route::delete('/' . $params, 'destroy')->name('destroy');
                });
        }
    });

    // Tour: chỉ quản lý tour và lịch khởi hành.
    Route::middleware(['role.check:quan-ly,le-tan,nhan-vien-tour'])->group(function () {
        Route::resource('tour', TourController::class);
        Route::resource('lich-khoi-hanh', LichKhoiHanhController::class);
    });
});

