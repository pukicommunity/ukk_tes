<?php

use App\Http\Controllers\GeneratePDFController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('generate-pdf',[GeneratePDFController::class,'userPDF']);
Route::get('/borrow/generate-pdf',[GeneratePDFController::class,'borrowPDF']);
Route::get('/return/generate-pdf',[GeneratePDFController::class,'returnPDF']);