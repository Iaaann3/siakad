<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JenisKeuanganController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =============================
// AUTH ROUTES
// =============================
Auth::routes();

// =============================
// WELCOME PAGE / LANDING
// =============================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// =============================
// GLOBAL DASHBOARD (Redirect Role Based)
// =============================
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    return redirect()->route($role . '.dashboard');
})->middleware('auth')->name('dashboard');

// =============================
// ADMIN ROUTES
// =============================
Route::group([
    'prefix'     => 'admin',
    'as'         => 'admin.',
    'middleware' => ['auth', IsAdmin::class],
], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('guru', GuruController::class);
    Route::resource('jurusan', JurusanController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('mapel', MapelController::class);
    Route::resource('semester', SemesterController::class);
    Route::resource('jeniskeuangan', JenisKeuanganController::class);
    Route::resource('penilaian', PenilaianController::class);
    Route::resource('absensi', AbsensiController::class);
    Route::resource('jadwal', JadwalController::class);
    Route::resource('keuangan', KeuanganController::class);
});

// =============================
// GURU ROUTES
// =============================
Route::group([
    'prefix'     => 'guru',
    'as'         => 'guru.',
    'middleware' => ['auth'],
], function () {
    Route::get('/', [GuruDashboardController::class, 'index'])->name('dashboard');
    Route::resource('mapel', MapelController::class);
    Route::resource('penilaian', PenilaianController::class);
    Route::resource('absensi', AbsensiController::class);
    Route::resource('jadwal', JadwalController::class);
});


// =============================
// SISWA ROUTES
// =============================
Route::group([
    'prefix'     => 'siswa',
    'as'         => 'siswa.',
    'middleware' => ['auth'],
], function () {
    Route::get('/', fn() => view('siswa.dashboard'))->name('dashboard');
    Route::resource('mapel', MapelController::class);
    Route::resource('absensi', AbsensiController::class);
    Route::resource('keuangan', KeuanganController::class);
    Route::resource('kelas', KelasController::class);
});
