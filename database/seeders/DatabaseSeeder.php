<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\User;
use App\Models\Visit;
use App\Services\PatientCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Clinic Default
        $clinic = Clinic::updateOrCreate(
            ['clinic_code' => 'ONEW'],
            [
                'name' => 'Onew Clinic',
                'clinic_code' => 'ONEW',
                'logo_path' => null,
                'primary_color' => '#0284C7', // Sky-600
                'secondary_color' => '#0EA5E9',
                'address' => 'Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan 12190',
                'phone' => '0812-9988-7766',
                'general_consent_text' => "PERSETUJUAN UMUM (GENERAL CONSENT) PELAYANAN KESEHATAN & ESTETIKA\n\n"
                    . "1. PERSETUJUAN PERAWATAN DAN PENGOBATAN\n"
                    . "Saya secara sadar dan sukarela menyetujui untuk mendapatkan perawatan, pemeriksaan klinis, tindakan estetika, serta pengobatan yang diberikan oleh dokter, perawat, dan tenaga medis profesional di Onew Clinic sesuai standar operasional yang berlaku.\n\n"
                    . "2. DOKUMENTASI MEDIS DAN FOTO KLINIS\n"
                    . "Saya memberikan persetujuan kepada pihak klinik untuk mengambil foto klinis sebelum (before) dan sesudah (after) tindakan untuk keperluan rekam medis elektronik, pemantauan efektivitas terapi klinis, serta sinkronisasi rekam medis Trustmedis.\n\n"
                    . "3. HAK DAN KEWAJIBAN PASIEN\n"
                    . "Saya telah memahami manfaat, risiko umum tindakan estetika, serta bersedia mematuhi anjuran pasca tindakan (post-treatment instructions). Saya telah menyampaikan riwayat kesehatan dan alergi dengan jujur.\n\n"
                    . "4. KERAHASIAAN INFORMASI KESEHATAN\n"
                    . "Pihak klinik menjamin kerahasiaan data rekam medis saya sesuai standar perlindungan data pribadi dan kerahasiaan medis.",
            ]
        );

        // 2. Users (Superadmin, Receptionist, Nurse, Doctor)
        $defaultPassword = Hash::make('password');

        $superadmin = User::updateOrCreate(
            ['email' => 'admin@onewclinic.com'],
            [
                'clinic_id' => $clinic->id,
                'name' => 'Superadmin Onew',
                'email' => 'admin@onewclinic.com',
                'password' => $defaultPassword,
                'role' => 'superadmin',
            ]
        );

        $receptionist = User::updateOrCreate(
            ['email' => 'receptionist@onewclinic.com'],
            [
                'clinic_id' => $clinic->id,
                'name' => 'Siti Resepsionis',
                'email' => 'receptionist@onewclinic.com',
                'password' => $defaultPassword,
                'role' => 'receptionist',
            ]
        );

        $nurse = User::updateOrCreate(
            ['email' => 'nurse@onewclinic.com'],
            [
                'clinic_id' => $clinic->id,
                'name' => 'Ns. Ratna Dewi, S.Kep',
                'email' => 'nurse@onewclinic.com',
                'password' => $defaultPassword,
                'role' => 'nurse',
            ]
        );

        $doctor = User::updateOrCreate(
            ['email' => 'doctor@onewclinic.com'],
            [
                'clinic_id' => $clinic->id,
                'name' => 'dr. Alana Wijaya, Sp.D.V.E',
                'email' => 'doctor@onewclinic.com',
                'password' => $defaultPassword,
                'role' => 'doctor',
            ]
        );

        // Additional Doctor for variety
        $doctor2 = User::updateOrCreate(
            ['email' => 'doctor2@onewclinic.com'],
            [
                'clinic_id' => $clinic->id,
                'name' => 'dr. Kevin Pratama, Dipl. CIBTAC',
                'email' => 'doctor2@onewclinic.com',
                'password' => $defaultPassword,
                'role' => 'doctor',
            ]
        );

        // 3. Treatments (5 sample treatments)
        $treatmentsData = [
            [
                'name' => 'Facial Glowing',
                'category' => 'Facial Treatment',
                'price' => 350000,
            ],
            [
                'name' => 'Laser Rejuvenation',
                'category' => 'Laser & Light Therapy',
                'price' => 1250000,
            ],
            [
                'name' => 'Botox 10 Units',
                'category' => 'Injectables & Anti-Aging',
                'price' => 950000,
            ],
            [
                'name' => 'Acne Peeling',
                'category' => 'Chemical Peel',
                'price' => 450000,
            ],
            [
                'name' => 'Infus Whitening',
                'category' => 'IV Therapy & Wellness',
                'price' => 650000,
            ],
        ];

        $treatments = [];
        foreach ($treatmentsData as $item) {
            $treatments[] = Treatment::updateOrCreate(
                [
                    'clinic_id' => $clinic->id,
                    'name' => $item['name'],
                ],
                [
                    'category' => $item['category'],
                    'price' => $item['price'],
                ]
            );
        }

        // 4. Sample Patients & Visits for demonstration
        $codeGenerator = new PatientCodeGenerator();
        $code1 = $codeGenerator->generate($clinic);

        $patient1 = Patient::updateOrCreate(
            ['clinic_id' => $clinic->id, 'patient_code' => $code1],
            [
                'name' => 'Aurelia Clarissa',
                'phone_number' => '081234567890',
                'gender' => 'female',
                'date_of_birth' => '1998-05-14',
                'address' => 'Apartemen Senopati Suites Tower 2, Jakarta Selatan',
            ]
        );

        $code2 = $codeGenerator->generate($clinic);
        $patient2 = Patient::updateOrCreate(
            ['clinic_id' => $clinic->id, 'patient_code' => $code2],
            [
                'name' => 'Bima Sakti',
                'phone_number' => '081398765432',
                'gender' => 'male',
                'date_of_birth' => '1994-11-20',
                'address' => 'Jl. Gandaria I No. 12, Kebayoran Baru, Jakarta Selatan',
            ]
        );

        // Seed 1 Completed Visit with full Mock Trustmedis payload
        $dummyPng = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAJYAAAA8CAYAAACEU0VNAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAOxAAADsQBlSsOGwAAAEZJREFUeJztwTEBAAAAwqD1T20ND6AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAeDCkPAABN9gXnAAAAABJRU5ErkJggg==');
        $sigPath = 'signatures/sample_signature.png';
        $beforePath = 'photos/before/sample_before.png';
        $afterPath = 'photos/after/sample_after.png';

        \Illuminate\Support\Facades\Storage::disk('public')->put($sigPath, $dummyPng);
        \Illuminate\Support\Facades\Storage::disk('public')->put($beforePath, $dummyPng);
        \Illuminate\Support\Facades\Storage::disk('public')->put($afterPath, $dummyPng);

        $completedVisit = Visit::updateOrCreate(
            [
                'clinic_id' => $clinic->id,
                'patient_id' => $patient1->id,
                'status' => 'completed',
            ],
            [
                'doctor_id' => $doctor->id,
                'nurse_id' => $nurse->id,
                'assistant_name' => 'Ns. Ratna Dewi, S.Kep',
                'consent_signed' => true,
                'consent_signature_path' => $sigPath,
                'consent_signed_at' => Carbon::now()->subHours(2),
                'photo_before_path' => $beforePath,
                'photo_after_path' => $afterPath,
                'trustmedis_sync_status' => 'synced',
                'trustmedis_synced_at' => Carbon::now()->subHours(1),
            ]
        );

        $completedVisit->treatments()->sync([
            $treatments[0]->id => ['notes' => 'Facial glowing deep cleanse, ekstraksi komedo minimal, masker calming.'],
            $treatments[1]->id => ['notes' => 'Laser Nd:YAG 1064nm rejuvenation pass 2x, fluens 2.2 J/cm2.'],
        ]);

        $mockService = new \App\Services\MockTrustmedisService();
        $mockService->syncVisit($completedVisit);

        // Seed 1 Active Visit waiting for nurse checkup
        Visit::updateOrCreate(
            [
                'clinic_id' => $clinic->id,
                'patient_id' => $patient2->id,
                'status' => 'consent_signed',
            ],
            [
                'doctor_id' => $doctor->id,
                'nurse_id' => $nurse->id,
                'assistant_name' => 'Ns. Ratna Dewi, S.Kep',
                'consent_signed' => true,
                'consent_signature_path' => $sigPath,
                'consent_signed_at' => Carbon::now()->subMinutes(15),
                'trustmedis_sync_status' => 'pending',
            ]
        );
    }
}
