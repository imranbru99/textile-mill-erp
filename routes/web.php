<?php

use App\Http\Controllers\PortalController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebsiteController::class, 'home']);
Route::get('/products', [WebsiteController::class, 'products']);
Route::post('/quote', [WebsiteController::class, 'quote'])->middleware('throttle:20,1');
Route::get('/contact', [WebsiteController::class, 'contact']);
Route::post('/contact', [WebsiteController::class, 'sendContact'])->middleware('throttle:10,1');
Route::get('/track', [WebsiteController::class, 'trackForm']);
Route::post('/track', [WebsiteController::class, 'track'])->middleware('throttle:10,1');
Route::get('/careers', [WebsiteController::class, 'careers']);
Route::get('/sitemap.xml', [WebsiteController::class, 'sitemap']);
Route::get('/robots.txt', [WebsiteController::class, 'robots']);

Route::get('/desk/login', [PortalController::class, 'loginForm'])->name('login');
Route::post('/desk/login', [PortalController::class, 'login'])->middleware('throttle:10,1');

Route::middleware(['auth', 'throttle:60,1'])->prefix('desk')->group(function (): void {
    Route::get('/buyer', [PortalController::class, 'buyer']);
    Route::post('/buyer/lab-dips/{labDip}', [PortalController::class, 'decideDip']);
    Route::get('/supplier', [PortalController::class, 'supplier']);
});

Route::get('/{slug}', [WebsiteController::class, 'page'])->where('slug', '[A-Za-z0-9\-]+');
