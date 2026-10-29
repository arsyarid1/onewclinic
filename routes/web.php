<?php

use App\Http\Controllers\MedicalReportController;
use App\Livewire\Auth\Login;
use App\Livewire\ClinicSettings;
use App\Livewire\DoctorTreatment;
use App\Livewire\NurseExamination;
use App\Livewire\PatientKiosk;
use App\Livewire\PatientRegistration;
use App\Livewire\VisitDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - ONEW Clinic Flow & Medical Records System
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

// Root redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Protected Medical & Clinic Workflow Routes
Route::middleware('auth')->group(function () {
    // Tahap 1: Kunjungan Hari Ini (Dashboard Antrian Pasien)
    Route::get('/dashboard', VisitDashboard::class)->name('dashboard');

    // Tahap 2: Registrasi Pasien & General Consent (Bisa diakses Resepsionis, Perawat, Superadmin)
    Route::get('/patients/register', PatientRegistration::class)->name('patients.register');

    // Tahap 3: Pemeriksaan & Tindakan Medis (Dokter & Perawat) - mendukung param visit_id opsional
    Route::get('/doctor/treatment/{visit_id?}', DoctorTreatment::class)->name('doctor.treatment');

    // Mode Tablet Kiosk Pasien (General Consent)
    Route::get('/kiosk/{visit}', PatientKiosk::class)->name('kiosk.show');

    // Pemeriksaan Perawat (bila dipisahkan)
    Route::get('/nurse/examination', NurseExamination::class)->name('nurse.examination');

    // Laporan Rekap Kunjungan (Medical Summary Report PDF)
    Route::get('/reports/visits/{visit}/pdf', [MedicalReportController::class, 'downloadPdf'])->name('reports.visit.pdf');

    // Pengaturan White-Label Clinic CMS (Super Admin)
    Route::get('/clinic/settings', ClinicSettings::class)->name('clinic.settings');
});
