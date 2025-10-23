<?php

use Illuminate\Support\Facades\Route;

// Public/User controllers
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CollectionPointController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HomeController;

// Admin controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ArticleAdminController;
use App\Http\Controllers\Admin\CollectionPointAdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Aturan akses:
| - Guest (belum login): Beranda, Artikel (list & detail), TPA/TPS (list).
|   Link Diskusi & Pelaporan tetap terlihat di navbar, tapi saat di-klik
|   akan diarahkan ke /login karena kedua fitur itu dilindungi middleware 'auth'.
|
| - User (sudah login): Bisa akses semua (Beranda, Artikel, TPA/TPS, Diskusi,
|   Pelaporan, Cek Laporan).
|
| - Admin (sudah login & is_admin): Akses halaman kelola (artikel, TPA/TPS,
|   diskusi, laporan) dan dashboard grafik.
|
| Pastikan 'require __DIR__/auth.php' di bagian bawah agar route login/register aktif.
|
*/

// ---------------------------- GUEST (Publik) ----------------------------

// Beranda (guest dan user akan melihat halaman yang sama, tapi route name berbeda untuk state)
Route::get('/', [HomeController::class, 'index'])->name('landing');

// Artikel - publik
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

// TPA/TPS - publik
Route::get('/collection-points', [CollectionPointController::class, 'index'])
    ->name('collection-points.index');


// ---------------------------- USER (Login diperlukan) ----------------------------
Route::middleware(['auth'])->group(function () {

    // Beranda setelah login (tetap gunakan view landing agar konsisten UI)
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Diskusi (flat thread)
    Route::get('/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
    Route::post('/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::post('/discussions/{discussion}/reply', [DiscussionController::class, 'reply'])->name('discussions.reply');

    // Pelaporan
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/check', [ReportController::class, 'check'])->name('reports.check');
});


// ---------------------------- ADMIN (Login + Admin) ----------------------------
Route::prefix('admin')->middleware(['auth', 'admin'])->as('admin.')->group(function () {

    // Dashboard - Lihat Grafik
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kelola Laporan
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/{report}/edit', [DashboardController::class, 'editReport'])->name('reports.edit');
    Route::put('/reports/{report}', [DashboardController::class, 'updateReport'])->name('reports.update');
    Route::delete('/reports/{report}', [DashboardController::class, 'destroyReport'])->name('reports.destroy');

    // Kelola Artikel (resource, tanpa show) -> nama route: admin.articles.*
    Route::resource('articles', ArticleAdminController::class)->except(['show']);

    // Kelola TPA/TPS (resource, tanpa show) -> nama route: admin.collection-points.*
    Route::resource('collection-points', CollectionPointAdminController::class)->except(['show']);

    // Kelola Diskusi
    Route::get('/discussions', [DashboardController::class, 'adminDiscussions'])->name('discussions.index');
    Route::post('/discussions/{discussion}/reply', [DashboardController::class, 'adminReply'])->name('discussions.reply');
    Route::delete('/discussions/{discussion}', [DashboardController::class, 'destroyDiscussion'])->name('discussions.destroy'); // <-- TAMBAHAN
});


// ---------------------------- AUTH ROUTES (Login/Register) ----------------------------
require __DIR__ . '/auth.php';
