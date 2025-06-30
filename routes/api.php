<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventPesertaController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\EventStatistikController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\PaymentController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
// api.php
Route::get('/cek-header', function (Request $request) {
    return response()->json([
        'Authorization' => $request->header('Authorization'),
        'user' => auth()->user()
    ]);
})->middleware('jwt.auth');

Route::middleware('jwt.auth')->group(function () {
    // Untuk user yang sudah login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // CRUD Event (admin)
    Route::post('/ubah-sandi', [AuthController::class, 'updatePassword']); //
    Route::get('/user/{id}', [AuthController::class, 'show']); //
    Route::get('/user', [AuthController::class, 'getUser']); //
    Route::get('/events', [EventController::class, 'apiIndex']); //
    Route::get('/events/{id}', [EventController::class, 'showAPI']); //
    Route::put('/events/{id}', [EventController::class, 'update']); //
    Route::get('/events/{id}', [EventPesertaController::class, 'show']);
    Route::get('/event/{id}/statistik', [EventStatistikController::class, 'show']);
    Route::post('/events', [EventController::class, 'store']); //
    Route::get('/peserta/{id}', [RegistrationController::class, 'show']); //
    Route::put('/peserta/{id}', [RegistrationController::class, 'updateAPI']); //
    Route::get('/events/{id_event}/peserta', [RegistrationController::class, 'getByEvent']); //
    Route::post('/registration-event/{id}', [RegistrationController::class, 'store']); //
    Route::post('/payments', [PaymentController::class, 'store']); //
    Route::post('/payments/{id}/upload-proof', [PaymentController::class, 'uploadProof']);//
    Route::post('/payments/{id}/verify', [PaymentController::class, 'verify']);//
    Route::post('/ticket/store', [TicketController::class, 'store']);
    Route::get('/ticket/qrcode/{id_registration}', [TicketController::class, 'apishowQrCode']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::post('/attendance', [AttendanceController::class, 'store']);
    Route::get('/sertifikat/{id_registration}', [SertifikatController::class, 'generate']);
    Route::get('/verify-sertifikat/{id}', [SertifikatController::class, 'verify']);
    Route::get('/event/{id}/participants', [PesertaController::class, 'index']);
    Route::put('/attendance/{registration_id}', [PesertaController::class, 'updateAttendance']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    Route::post('/attandance/scan', [AttandanceController::class, 'verifikasi']);
    Route::get('/panduan', [PanduanController::class, 'show']); //
    Route::post('/panduan', [PanduanController::class, 'store']); //
    Route::post('/certificate/generate/{registrationId}', [SertifikatController::class, 'generateAPI']);
    Route::get('/certificate/verifikasi/{kode}', [SertifikatController::class, 'verifikasiAPI']);


    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});