<?php

use App\Http\Controllers\MedicalExportController;
use App\Http\Controllers\MedicalReportController;
use App\Livewire\Auth\Login;
use App\Livewire\ClinicalFormFlow;
use App\Livewire\ClinicSettings;
use App\Livewire\PatientKiosk;
use App\Livewire\PatientRegistration;
use App\Livewire\VisitDashboard;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

// Public Medical & Clinical Form Flow Routes (No login required - Tablet / Desktop PWA)
Route::get('/form-flow/{visit_id?}', ClinicalFormFlow::class)->name('clinical.form.flow');
Route::get('/registrasi/{visit_id?}', ClinicalFormFlow::class);

// Direct Storage Asset Route (ensures public photos and signatures always resolve across local/nginx/windows)
Route::get('/storage/{path}', function (string $path) {
    $cleanPath = ltrim(preg_replace('#^/?storage/#', '', $path), '/');
    if (! Storage::disk('public')->exists($cleanPath)) {
        abort(404);
    }
    $fullPath = Storage::disk('public')->path($cleanPath);

    return response()->file($fullPath);
})->where('path', '.*');

// Protected Medical & Clinic Workflow Routes
Route::middleware('auth')->group(function () {
    // Tahap 1: Kunjungan Hari Ini (Dashboard Antrian Pasien)
    Route::get('/dashboard', VisitDashboard::class)->name('dashboard');
    Route::get('/dashboard/export', [MedicalExportController::class, 'exportCsv'])->name('dashboard.export');

    // Tahap 2: Registrasi Pasien & General Consent (Bisa diakses Resepsionis, Perawat, Superadmin)
    Route::get('/patients/register', PatientRegistration::class)->name('patients.register');

    // Tahap 3: Pemeriksaan & Tindakan Medis (Dokter & Perawat) - Disamakan dengan Alur Form Flow (Langkah 3)
    Route::get('/doctor/treatment/{visit_id?}', function (?int $visit_id = null) {
        if ($visit_id) {
            return redirect()->route('clinical.form.flow', ['visit_id' => $visit_id, 'step' => 3]);
        }
        $activeVisit = Visit::whereIn('status', ['waiting_doctor', 'in_treatment', 'registered'])->latest()->first();
        if ($activeVisit) {
            return redirect()->route('clinical.form.flow', ['visit_id' => $activeVisit->id, 'step' => 3]);
        }

        return redirect()->route('clinical.form.flow', ['step' => 3]);
    })->name('doctor.treatment');

    // Mode Tablet Kiosk Pasien (General Consent)
    Route::get('/kiosk/{visit}', PatientKiosk::class)->name('kiosk.show');

    // Pemeriksaan Perawat (Disamakan dengan Form Flow)
    Route::get('/nurse/examination', function () {
        return redirect()->route('clinical.form.flow');
    })->name('nurse.examination');

    // Laporan Rekap Kunjungan (Medical Summary Report PDF)
    Route::get('/reports/visits/{visit}/pdf', [MedicalReportController::class, 'downloadPdf'])->name('reports.visit.pdf');

    // Pengaturan White-Label Clinic CMS (Super Admin)
    Route::get('/clinic/settings', ClinicSettings::class)->name('clinic.settings');
});
