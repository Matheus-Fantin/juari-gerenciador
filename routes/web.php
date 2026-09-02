<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiteImageController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/depoimentos', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::patch('/depoimentos/{testimonial}/aprovar', [TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::patch('/depoimentos/{testimonial}/despublicar', [TestimonialController::class, 'unpublish'])->name('testimonials.unpublish');
    Route::delete('/depoimentos/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

    Route::get('/galeria', [GalleryController::class, 'index'])->name('galleries.index');
    Route::post('/galeria', [GalleryController::class, 'store'])->name('galleries.store');
    Route::patch('/galeria/{photo}/legenda', [GalleryController::class, 'updateCaption'])->name('galleries.legenda');
    Route::patch('/galeria/{photo}/mover', [GalleryController::class, 'move'])->name('galleries.mover');
    Route::delete('/galeria/{photo}', [GalleryController::class, 'destroy'])->name('galleries.destroy');

    Route::get('/imagens-do-site', [SiteImageController::class, 'index'])->name('site-images.index');
    Route::post('/imagens-do-site/{slot}', [SiteImageController::class, 'update'])->name('site-images.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
