<?php

use App\Http\Controllers\MedicalReportController;
use App\Livewire\ClinicSettings;
use App\Livewire\DoctorTreatment;
use App\Livewire\NurseExamination;
use App\Livewire\PatientKiosk;
use App\Livewire\PatientRegistration;
use App\Livewire\VisitDashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - ONEW Clinic Flow & Medical Records System
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard Monitoring Alur Pasien
Route::get('/dashboard', VisitDashboard::class)->name('dashboard');

// Step 1: Registrasi Pasien (Resepsionis)
Route::get('/patients/register', PatientRegistration::class)->name('patients.register');

// Step 2: Kiosk Pasien (Mode Tablet Pasien)
Route::get('/kiosk/{visit}', PatientKiosk::class)->name('kiosk.show');

// Step 3: Staf / Perawat (Pemeriksaan Awal & Foto Before)
Route::get('/nurse/examination', NurseExamination::class)->name('nurse.examination');

// Step 4: Dokter (Tindakan, Foto After & Trustmedis Sync)
Route::get('/doctor/treatment', DoctorTreatment::class)->name('doctor.treatment');

// Step 5: Laporan Rekap Kunjungan (Medical Summary Report PDF)
Route::get('/reports/visits/{visit}/pdf', [MedicalReportController::class, 'downloadPdf'])->name('reports.visit.pdf');

// White-Label Clinic CMS Settings
Route::get('/clinic/settings', ClinicSettings::class)->name('clinic.settings');
