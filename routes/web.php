<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LpjController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
})->name('home');

// Health check untuk Load Balancer (publik, tanpa auth, ringan).
// Mengembalikan HTTP 200 + identitas instance agar mudah memverifikasi
// instance mana yang melayani request: curl http://<ALB-DNS>/health
Route::get('/health', function () {
    return response()->json([
        'status'    => 'ok',
        'server_id' => config('app.server_id'),
        'host'      => gethostname(),
    ]);
})->name('health');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Proposal routes
    Route::resource('proposal', ProposalController::class);
    Route::get('/proposal/{proposal}/generate', [ProposalController::class, 'generatePdf'])
        ->name('proposal.generate');

    // LPJ routes
    Route::resource('lpj', LpjController::class);
    Route::get('/lpj/{lpj}/generate', [LpjController::class, 'generatePdf'])
        ->name('lpj.generate');
    Route::post('/lpj/{lpj}/dana-keluar', [LpjController::class, 'saveDanaKeluar'])
        ->name('lpj.dana-keluar.save');
});

require __DIR__.'/auth.php';
