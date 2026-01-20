<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\StockController;

Route::post('/pos/sale', [PosController::class, 'storeSale']);
Route::post('/stock/in', [StockController::class, 'stockIn']);
