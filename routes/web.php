<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('members', MembersController::class); 
    Route::resource('books', BookController::class);
    Route::resource('borrowings', BorrowingController::class);
    Route::resource('returns', ReturnController::class);
    
    Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::post('/returns/process/{id}', [ReturnController::class, 'process'])->name('returns.process');



});

require __DIR__.'/auth.php';
