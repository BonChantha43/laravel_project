<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

// Controllers Import
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================================================
// ផ្នែកទី ១: Public Routes (អ្នកណាក៏ចូលបាន មិនបាច់ Login)
// =========================================================

// ១. ទំព័រដើម (Root) - បើកមកភ្លាម រត់ទៅរកកន្លែង Login មុនគេ (ដើម្បីសុវត្ថិភាព)
Route::get('/', function () {
    return redirect()->route('login');
});

// ២. ទំព័រ POS (ដាក់នៅទីនេះ ដើម្បីកុំឱ្យជាប់ Login - តាមសំណើបង)
Route::get('/pos', [PosController::class, 'index'])->name('pos');

// ៣. ផ្នែក Login/Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');


// =========================================================
// ផ្នែកទី ២: Protected Routes (Admin Panel - ត្រូវតែ Login)
// =========================================================
// Route ទាំងអស់ដែលស្ថិតនៅក្នុង group នេះ នឹងមិនអាចចូលបានទេ បើមិនទាន់ Login
Route::middleware(['auth'])->group(function () {

    // 1. ចាកចេញ (Logout)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 2. Dashboard (ការពារ)
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 3. គ្រប់គ្រងទំនិញ (Products - CRUD)
    Route::resource('/admin/products', AdminProductController::class);

    // 4. គ្រប់គ្រងប្រភេទ (Categories)
    Route::prefix('admin/categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    // 5. គ្រប់គ្រងស្តុក (Stock Operations)
    Route::get('/admin/stock/in', [StockController::class, 'stockInForm']);
    Route::post('/admin/stock/in', [StockController::class, 'processStockIn']);

    // 6. របាយការណ៍ (Reports)
    Route::prefix('admin/reports')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales']);
        Route::get('/stock', [ReportController::class, 'stock']);
        Route::get('/transactions', [ReportController::class, 'transactions']);
    });
});
Route::resource('/admin/employees', \App\Http\Controllers\EmployeeController::class);
Route::resource('/admin/suppliers', \App\Http\Controllers\SupplierController::class);
