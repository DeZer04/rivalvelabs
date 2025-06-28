<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/barcode/print/{code}', function ($code) {
    $pdf = Pdf::loadView('print.barcode', ['code' => $code]);
    return $pdf->stream("barcode-$code.pdf");
})->name('barcode.print');
