<?php

use App\Http\Controllers\OrderTeknikController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [OrderTeknikController::class, 'showLogin'])->name('login');
Route::post('/login', [OrderTeknikController::class, 'login']);
Route::get('/logout', [OrderTeknikController::class, 'logout'])->name('logout');

Route::get('/', [OrderTeknikController::class, 'index'])->middleware('auth.custom');
Route::post('/order-teknik', [OrderTeknikController::class, 'store'])->name('order-teknik.store')->middleware('auth.custom');
Route::get('/report', [OrderTeknikController::class, 'report'])->name('report')->middleware('auth.custom');
Route::get('/report/export/pdf', [OrderTeknikController::class, 'exportPdf'])->name('report.export.pdf')->middleware('auth.custom');
Route::get('/report/export/csv', [OrderTeknikController::class, 'exportCsv'])->name('report.export.csv')->middleware('auth.custom');
Route::get('/report/export/word', [OrderTeknikController::class, 'exportWord'])->name('report.export.word')->middleware('auth.custom');
Route::get('/order-teknik/{orderTeknik}/edit', [OrderTeknikController::class, 'edit'])->name('order-teknik.edit')->middleware('auth.custom');
Route::put('/order-teknik/{orderTeknik}', [OrderTeknikController::class, 'update'])->name('order-teknik.update')->middleware('auth.custom');
