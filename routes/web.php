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

Route::get('/', [AuthController::class, 'index']);
Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
Route::get('/customer/booking', [CustomerController::class, 'booking'])->name('customer.booking');
Route::get('/customer/login', [CustomerController::class, 'login'])->name('customer.login');
Route::post('/customer/login', [CustomerController::class, 'submitLogin'])->name('customer.login.submit');
Route::post('/customer/logout', [CustomerController::class, 'logout'])->name('customer.logout');
Route::get('/customer/phong/{maPhong}', [CustomerController::class, 'roomDetail'])->name('customer.room-detail');
Route::get('/customer/tour/{maTour}', [CustomerController::class, 'tourDetail'])->name('customer.tour-detail');
Route::get('/customer/dich-vu/{maDV}', [CustomerController::class, 'serviceDetail'])->name('customer.service-detail');
Route::post('/customer/book-service', [CustomerController::class, 'storeBooking'])->name('customer.book-service');
Route::get('/test-db', [AuthController::class, 'testDb'])->name('test-db');

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

        Route::group(['prefix' => 'hd-dich-vu'], function () {
            Route::get('/', [HDDichVuController::class, 'index'])->name('hd-dich-vu.index');
            Route::get('/create', [HDDichVuController::class, 'create'])->name('hd-dich-vu.create');
            Route::post('/', [HDDichVuController::class, 'store'])->name('hd-dich-vu.store');
            Route::get('/{maHD}/{maDV}', [HDDichVuController::class, 'show'])->name('hd-dich-vu.show');
            Route::get('/{maHD}/{maDV}/edit', [HDDichVuController::class, 'edit'])->name('hd-dich-vu.edit');
            Route::put('/{maHD}/{maDV}', [HDDichVuController::class, 'update'])->name('hd-dich-vu.update');
            Route::delete('/{maHD}/{maDV}', [HDDichVuController::class, 'destroy'])->name('hd-dich-vu.destroy');
        });

        Route::group(['prefix' => 'hd-phong'], function () {
            Route::get('/', [HDPhongController::class, 'index'])->name('hd-phong.index');
            Route::get('/create', [HDPhongController::class, 'create'])->name('hd-phong.create');
            Route::post('/', [HDPhongController::class, 'store'])->name('hd-phong.store');
            Route::get('/{maHD}/{maPhong}', [HDPhongController::class, 'show'])->name('hd-phong.show');
            Route::get('/{maHD}/{maPhong}/edit', [HDPhongController::class, 'edit'])->name('hd-phong.edit');
            Route::put('/{maHD}/{maPhong}', [HDPhongController::class, 'update'])->name('hd-phong.update');
            Route::delete('/{maHD}/{maPhong}', [HDPhongController::class, 'destroy'])->name('hd-phong.destroy');
        });

        Route::group(['prefix' => 'hd-tour'], function () {
            Route::get('/', [HDTOURController::class, 'index'])->name('hd-tour.index');
            Route::get('/create', [HDTOURController::class, 'create'])->name('hd-tour.create');
            Route::post('/', [HDTOURController::class, 'store'])->name('hd-tour.store');
            Route::get('/{maHD}/{maLKH}', [HDTOURController::class, 'show'])->name('hd-tour.show');
            Route::get('/{maHD}/{maLKH}/edit', [HDTOURController::class, 'edit'])->name('hd-tour.edit');
            Route::put('/{maHD}/{maLKH}', [HDTOURController::class, 'update'])->name('hd-tour.update');
            Route::delete('/{maHD}/{maLKH}', [HDTOURController::class, 'destroy'])->name('hd-tour.destroy');
        });
    });

    // Tour: chỉ quản lý tour và lịch khởi hành.
    Route::middleware(['role.check:quan-ly,le-tan,nhan-vien-tour'])->group(function () {
        Route::resource('tour', TourController::class);
        Route::resource('lich-khoi-hanh', LichKhoiHanhController::class);
    });
});

