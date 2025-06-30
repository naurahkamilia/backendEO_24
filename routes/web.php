<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventStatistikController;
use App\Http\Controllers\RegistrationController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'loginWeb'])->name('loginWeb');
Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/events/index', [EventController::class, 'index'])->name('events.index');
    Route::get('/admin/events/create', [EventController::class, 'create'])->name('events.create');
    Route::get('/admin/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::post('/admin/events/store', [EventController::class, 'store'])->name('events.store');
    Route::delete('/admin/events/{id}/destroy', [EventController::class, 'destroy'])->name('events.destroy');
    Route::put('/admin/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::get('/admin/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::get('/admin/peserta/index', [EventController::class, 'indexParticipant'])->name('peserta.index');
    Route::get('/admin/peserta/list/{id_event}', [RegistrationController::class, 'listByEvent'])->name('peserta.listByEvent');
    Route::get('/admin/peserta/{id}/edit', [RegistrationController::class, 'edit'])->name('peserta.edit');
    Route::put('/admin/peserta/{id}', [RegistrationController::class, 'update'])->name('peserta.update');
    Route::get('/admin/peserta/{id}/detail', [RegistrationController::class, 'detail'])->name('peserta.detail');
    Route::post('/admin/kehadiran/scan-kehadiran', [RegistrationController::class, 'scanKehadiran'])->name('kehadiran.scan');
    Route::get('/admin/kehadiran/scan-kehadiran', fn() => view('admin.kehadiran.scan'))->name('kehadiran.form');
    Route::get('/admin/dashboard', [EventStatistikController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/sertifikat', [SertifikatController::class, 'index'])->name('sertifikat.index');
    Route::get('/admin/sertifikat/generate/{registrationId}', [SertifikatController::class, 'generate'])->name('admin.sertifikat.generate');
    Route::get('/sertifikat/generate/{registrationId}', [SertifikatController::class, 'generate'])->name('admin.sertifikat.generate');
    Route::get('/sertifikat/download/{id}', [SertifikatController::class, 'download'])->name('admin.sertifikat.download');
    Route::get('/verifikasi/{kode}', [SertifikatController::class, 'verifikasi'])->name('admin.sertifikat.verifikasi');
    Route::get('/sertifikat/image-to-pdf/{id}', [SertifikatController::class, 'preview'])->name('sertifikat.preview');
    Route::get('/admin/peserta/{id_registration}/qrcode', [RegistrationController::class, 'showQrCode'])->name('peserta.qrcode');
    Route::get('/admin/qrcode/{id_registration}', [TicketController::class, 'showQrCode']);
Route::get('/admin/sertifikat/generate/{registration}', [SertifikatController::class, 'generateCertificate'])->name('admin.sertifikat.generate');

});

Route::get('/verify-sertifikat/{id}', [SertifikatController::class, 'verify']);
Route::get('/', function () {
    return response()->json(['message' => 'Laravel API aktif!']);
});
