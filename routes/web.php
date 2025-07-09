<?php

use App\Http\Controllers\BarcodeImageController;
use Illuminate\Support\Facades\Route;
use App\Filament\Pages\GenerateBarcode;
use App\Http\Controllers\BarcodeController;

Route::match(['get', 'post'], '/', [BarcodeController::class, 'create'])->name('barcode.create');
Route::get('/barcode/image/{text}', [BarcodeController::class, 'image'])->name('barcode.image');
Route::get('/barcode/pesanan/{buyer}', [BarcodeController::class, 'getPesanan']);
Route::get('/barcode/item-variant/{nomor_pesanan}', [BarcodeController::class, 'getItemVariant'])
    ->where('nomor_pesanan', '.*'); // Allow any characters in the parameter
Route::post('/barcode/generate', [BarcodeController::class, 'generate'])->name('barcode.generate');
Route::post('/barcode/decode', [BarcodeController::class, 'decode'])->name('barcode.decode');
