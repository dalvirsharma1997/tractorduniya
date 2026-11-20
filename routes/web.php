<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImplementController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TractorController;
use App\Http\Controllers\UsedListingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/tractors', [TractorController::class, 'index'])->name('tractors.index');
Route::get('/compare/{slugs}', [TractorController::class, 'compare'])->name('tractors.compare');
Route::get('/tractors/{brand:slug}', [BrandController::class, 'show'])->name('brands.show');
Route::get('/tractors/{brand:slug}/{tractor:slug}', [TractorController::class, 'show'])->name('tractors.show');

Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/implements', [ImplementController::class, 'index'])->name('implements.index');
Route::get('/implements/{implement:slug}', [ImplementController::class, 'show'])->name('implements.show');

Route::get('/used-tractors', [UsedListingController::class, 'index'])->name('used-listings.index');
Route::get('/sell', [UsedListingController::class, 'create'])->name('used-listings.create');
Route::post('/sell', [UsedListingController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('used-listings.store');

Route::get('/dealers', [DealerController::class, 'index'])->name('dealers.index');
Route::get('/dealers/{dealer:slug}', [DealerController::class, 'show'])->name('dealers.show');

Route::get('/emi-calculator', [PageController::class, 'emiCalculator'])->name('emi-calculator');
Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::post('/leads', [LeadController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('leads.store');

Route::get('/tractors-in-{state:slug}', [PageController::class, 'stateLanding'])->name('geo.state');
