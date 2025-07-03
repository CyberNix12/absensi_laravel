<?php

use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PengajuanIzinController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ManagerMiddleware;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware(['auth'])->group(function () {
    // Dashboard (Volt)
    Volt::route('dashboard', 'dashboard')->name('dashboard');

    // Presensi (was: data-absensi)
    Route::get('data-presensi', [PresensiController::class, 'index'])->name('data-presensi');
    Route::get('data-presensi/rekap', [PresensiController::class, 'rekap'])->name('data-presensi.rekap')->middleware(AdminMiddleware::class);
    Route::get('data-presensi/in', [PresensiController::class, 'in'])->name('data-presensi.in');
    Route::post('data-presensi/in', [PresensiController::class, 'in_store'])->name('data-presensi.in.store');
    Route::get('data-presensi/out', [PresensiController::class, 'out'])->name('data-presensi.out');
    Route::post('data-presensi/out', [PresensiController::class, 'out_store'])->name('data-presensi.out.store');

    // Pengajuan Izin/Sakit/Cuti
    Route::get('pengajuan-izin', [PengajuanIzinController::class, 'index'])->name('pengajuan-izin.index');
    Route::get('pengajuan-izin/create', [PengajuanIzinController::class, 'create'])->name('pengajuan-izin.create');
    Route::post('pengajuan-izin/store', [PengajuanIzinController::class, 'store'])->name('pengajuan-izin.store');
    Route::get('/pengajuan-izin/{pengajuan_izin}', [PengajuanIzinController::class, 'show'])->name('pengajuan-izin.show');
    Route::delete('pengajuan-izin/{pengajuan_izin}', [PengajuanIzinController::class, 'destroy'])->name('pengajuan-izin.destroy');
   // PATCH untuk ADMIN

    Route::match(['put', 'patch'], 'pengajuan-izin/{pengajuan_izin}', [PengajuanIzinController::class, 'update'])
    ->middleware(AdminMiddleware::class);

    // Untuk MANAGER
    Route::match(['put', 'patch'], 'pengajuan-izin/{pengajuan_izin}', [PengajuanIzinController::class, 'update'])
    ->middleware(ManagerMiddleware::class);
    Route::get('pengajuan-izin/rekap', [PengajuanIzinController::class, 'rekap'])->name('pengajuan-izin.rekap')->middleware([AdminMiddleware::class . ',' . ManagerMiddleware::class]);

    // Karyawan (admin only)
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('karyawan', [KaryawanController::class, 'index'])->name('karyawan');
        Route::get('karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::get('karyawan/edit/{user}', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::put('karyawan/update/{user}', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::get('karyawan/reset-password/{user}', [KaryawanController::class, 'reset_password'])->name('karyawan.reset-password');
        Route::patch('karyawan/reset-password/{user}', [KaryawanController::class, 'reset_password_process'])->name('karyawan.reset-password-proses');
        Route::delete('karyawan/destroy/{user}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    });

    // Settings (Volt)
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';