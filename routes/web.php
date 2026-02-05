<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UIDController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiInputController;

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Public dashboard route (no auth middleware)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/absen', [AbsenController::class, 'index'])->name('absen');
Route::post('/process-face', [AbsenController::class, 'processFace'])->name('face.process');

// Route untuk face attendance
Route::get('/face-attendance', [AbsenController::class, 'index'])->name('face.attendance');
Route::post('/face-process', [AbsenController::class, 'processFace'])->name('face.process');

// Route untuk data mapping
Route::get('/name-uid-mapping', [AbsenController::class, 'getNameUidMapping'])->name('name.uid.mapping');
Route::get('/all-student-data', [AbsenController::class, 'getAllStudentData'])->name('all.student.data');
Route::get('/search-student', [AbsenController::class, 'searchStudent'])->name('search.student');
Route::post('/report-attendance', [AbsenController::class, 'reportAttendance'])->name('report.attendance');

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {

    // Rute absensi
    Route::prefix('absensi')->group(function () {
        Route::get('/', [AbsensiController::class, 'index'])->name('absensi');
        Route::get('/terlambat', [AbsensiController::class, 'terlambat'])->name('absensi.terlambat');
        Route::match(['put', 'post'], '/{id}', [AbsensiController::class, 'update'])->name('absensi.update');
    });

    // Rute untuk halaman profile
    Route::view('/profile', 'profile')->name('profile');

    // Rute resource untuk siswa
    Route::resource('/siswa', SiswaController::class);

    // Rute untuk data UID (but protected)
    Route::get('/data-uid', [UIDController::class, 'index'])->name('data-uid');
    Route::post('/uid/update-name', [UIDController::class, 'updateName'])->name('uid.update-name');
    Route::put('/data-uid/{id}/update-student', [UIDController::class, 'updateStudent'])->name('uid.update-student');
// });
