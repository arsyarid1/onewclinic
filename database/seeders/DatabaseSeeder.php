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
                'primary_color' => '#0284C7',
                'secondary_color' => '#0EA5E9',
                'address' => 'Jl. Laksana I no 6, Kebayoran Baru, Jaksel 12180',
                'phone' => '+62 8119 6977 886 / +62 21382 506 94',
                'general_consent_text' => "GENERAL CONSENT\nFORMULIR PERSETUJUAN UMUM PASIEN\n\n"
                    . "1. PERSETUJUAN PENDAFTARAN DAN PELAYANAN UMUM\n"
                    . "Saya bersedia didaftarkan sebagai pasien dan menerima penjelasan mengenai alur layanan, ketentuan administrasi, serta pelayanan awal yang sesuai kebutuhan, seperti wawancara riwayat kesehatan, pemeriksaan tanda vital, pemeriksaan fisik dasar, konsultasi, dan observasi noninvasif sesuai penilaian tenaga medis.\n"
                    . "Saya memahami bahwa setiap pemeriksaan, pemberian obat, dan rencana terapi akan dijelaskan sesuai kebutuhan. Tindakan yang memerlukan persetujuan khusus, termasuk tindakan invasif atau berisiko, injeksi, penggunaan laser, anestesi, dan pembedahan, tidak otomatis disetujui melalui formulir ini dan memerlukan informed consent tersendiri sesuai ketentuan.\n\n"
                    . "2. HAK DAN KEWAJIBAN PASIEN\n"
                    . "Saya telah memperoleh kesempatan untuk mengetahui hak saya, termasuk mendapatkan informasi yang dapat dipahami tentang kondisi dan rencana pelayanan, menanyakan alternatif dan biaya, memperoleh privasi serta kerahasiaan rekam medis, menyampaikan keluhan, dan menerima atau menolak tindakan sesuai ketentuan yang berlaku.\n"
                    . "Saya berkewajiban memberikan data identitas dan riwayat kesehatan secara benar, menginformasikan alergi/obat yang digunakan, mengikuti aturan keselamatan klinik, menghormati petugas dan pasien lain, serta memenuhi kewajiban pembayaran untuk layanan yang telah disetujui.\n\n"
                    . "3. REKAM MEDIS DAN PELINDUNGAN DATA PRIBADI\n"
                    . "Saya memahami bahwa klinik mengumpulkan dan mengolah data identitas, kontak, riwayat kesehatan, hasil pemeriksaan, diagnosis, terapi, dan informasi pembayaran untuk pendaftaran, pelayanan, pencatatan rekam medis elektronik, administrasi, penagihan, serta pemenuhan kewajiban hukum. Akses dibatasi kepada pihak berwenang sesuai kebutuhan dan kerahasiaan tetap dijaga.\n"
                    . "Data dapat disampaikan kepada tenaga kesehatan atau fasilitas penerima rujukan dan sistem pelaporan/integrasi kesehatan yang diwajibkan peraturan, sebatas diperlukan. Pengungkapan kepada pihak lain, termasuk penjamin/asuransi, dilaksanakan sesuai dasar hukum atau persetujuan yang berlaku. Foto atau rekaman untuk publikasi/promosi tidak termasuk dalam persetujuan ini.\n\n"
                    . "4. BIAYA DAN ADMINISTRASI\n"
                    . "Saya memahami bahwa biaya konsultasi, pemeriksaan penunjang, obat, dan tindakan tambahan dapat berbeda. Klinik akan menyampaikan informasi biaya sebelum layanan diberikan, sepanjang keadaan memungkinkan. Saya berhak mengajukan pertanyaan dan menyetujui atau menolak layanan tambahan sesuai ketentuan medis dan hukum.\n\n"
                    . "5. KEADAAN DARURAT DAN RUJUKAN\n"
                    . "Apabila terjadi kondisi darurat yang mengancam nyawa atau keselamatan, saya memahami bahwa tenaga medis dapat memberikan pertolongan segera sesuai kewenangan dan ketentuan yang berlaku. Bila pelayanan yang dibutuhkan tidak tersedia, klinik dapat menyarankan atau mengatur rujukan ke fasilitas yang sesuai, dengan pemberitahuan kepada saya/keluarga sejauh memungkinkan.\n\n"
                    . "6. KOMUNIKASI DAN DOKUMENTASI MEDIS\n"
                    . "Klinik dapat menghubungi nomor kontak saya untuk keperluan pelayanan dan administrasi. Penggunaan WhatsApp dan pengiriman promosi mengikuti pilihan saya di bawah. Informasi medis sensitif hanya disampaikan dengan memperhatikan verifikasi identitas dan kerahasiaan. Dokumentasi foto/video klinis, jika diperlukan, akan dijelaskan dan dimintakan persetujuan sesuai tujuan penggunaannya; penggunaan untuk promosi memerlukan persetujuan terpisah.\n\n"
                    . "7. PILIHAN PASIEN\n"
                    . "Pengingat jadwal dan komunikasi administrasi melalui WhatsApp, promosi layanan klinik (opsional), verifikasi klaim penjamin, dan penunjukan penerima informasi medis.\n\n"
                    . "8. PERNYATAAN DAN PERSETUJUAN\n"
                    . "Saya menyatakan telah membaca atau mendapatkan penjelasan formulir ini dengan bahasa yang saya pahami, memiliki kesempatan bertanya, dan memahami bahwa persetujuan umum ini tidak menggantikan persetujuan tindakan kedokteran khusus. Saya memberikan persetujuan untuk pendaftaran dan pelayanan umum sebagaimana dijelaskan di atas.",
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
