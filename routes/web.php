<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CollectionPointController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome-landing')->name('landing'); // Halaman beranda untuk tamu & user

// Guest-access (tanpa login):
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/collection-points', [CollectionPointController::class, 'index'])->name('collection-points.index');

// Auth required (user):
Route::middleware(['auth'])->group(function () {
    // halaman beranda setelah login (tetap pakai landing dengan CTA berbeda)
    Route::get('/home', fn() => view('welcome-landing'))->name('home');

    // Diskusi (flat)
    Route::get('/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
    Route::post('/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::post('/discussions/{discussion}/reply', [DiscussionController::class, 'reply'])->name('discussions.reply');

    // Pelaporan
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/check', [ReportController::class, 'check'])->name('reports.check'); // cek status milik user
});

// Admin
Route::prefix('admin')->middleware(['auth','admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // CRUD laporan untuk admin
    Route::get('/reports', [DashboardController::class, 'reports'])->name('admin.reports');
    Route::get('/reports/{report}/edit', [DashboardController::class, 'editReport'])->name('admin.reports.edit');
    Route::put('/reports/{report}', [DashboardController::class, 'updateReport'])->name('admin.reports.update');
    Route::delete('/reports/{report}', [DashboardController::class, 'destroyReport'])->name('admin.reports.destroy');

    // Admin tanggapi diskusi (anggap reply juga)
    Route::post('/discussions/{discussion}/reply', [DashboardController::class, 'adminReply'])->name('admin.discussions.reply');
});

require __DIR__.'/auth.php';
