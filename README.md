# ONEW Clinic - MVP Sistem Manajemen Alur Rekam Medis & Kunjungan Pasien

Sistem manajemen alur rekam medis & kunjungan pasien klinik estetika berbasis **Laravel 12**, **MySQL**, **Livewire 3**, **Alpine.js**, dan **Tailwind CSS**. Dilengkapi konsep **White-Label CMS** dinamis dan **Mock Integration ke Trustmedis EHR Gateway**.

---

## 🛠️ Stack & Spesifikasi Teknologi
- **Framework:** Laravel 12 (PHP 8.4)
- **Frontend Reactive:** Livewire 3 + Alpine.js
- **Styling:** Tailwind CSS (Modern Theme Custom Properties)
- **Signature Canvas:** `signature_pad` (Digital touch signature pad untuk tablet pasien)
- **PDF Engine:** `barryvdh/laravel-dompdf` (Header & Kop dinamis + side-by-side foto klinis)
- **Database:** MySQL 8.4 (Support lockForUpdate DB Transaction)
- **Storage:** Local public storage (`storage/app/public` via `php artisan storage:link`)

---

## 🏛️ Arsitektur Database (Migrations & Relations)

1. **`clinics` (White Label Multi-tenant Config):**
   - `id`, `name`, `clinic_code` (char 4, e.g. `ONEW`), `logo_path`, `primary_color` (default: `#0284C7`), `secondary_color`, `address`, `phone`, `general_consent_text`, `timestamps`.
2. **`users`:**
   - `id`, `clinic_id` (FK), `name`, `email`, `password`, `role` (enum: `superadmin`, `receptionist`, `nurse`, `doctor`), `timestamps`.
3. **`patients`:**
   - `id`, `clinic_id` (FK), `patient_code` (unique, format: `[CODE][YY]-[000001]`), `name`, `phone_number`, `gender`, `date_of_birth`, `address`, `timestamps`.
4. **`visits`:**
   - `id`, `clinic_id` (FK), `patient_id` (FK), `doctor_id` (FK, nullable), `nurse_id` (FK, nullable), `assistant_name`, `status` (`registered`, `consent_signed`, `waiting_doctor`, `in_treatment`, `completed`).
   - `consent_signed`, `consent_signature_path`, `consent_signed_at`.
   - `photo_before_path`, `photo_after_path`.
   - `trustmedis_sync_status` (`pending`, `synced`, `failed`), `trustmedis_payload` (json), `trustmedis_synced_at`, `timestamps`.
5. **`treatments`:**
   - `id`, `clinic_id` (FK), `name`, `category`, `price`, `timestamps`.
6. **`visit_treatments`:**
   - `id`, `visit_id` (FK), `treatment_id` (FK), `notes`, `timestamps`.

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 1. Kloning & Persiapan Lingkungan
```bash
# Pastikan Composer dan Node.js sudah terpasang
composer install
npm install
npm run build
```

### 2. Konfigurasi Database `.env`
Sesuaikan kredensial MySQL pada file `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=onew_clinic
DB_USERNAME=root
DB_PASSWORD=
FILESYSTEM_DISK=public
```

### 3. Migrasi, Seeder, & Symbolic Link Storage
```bash
# Hubungkan storage public
php artisan storage:link

# Jalankan migrasi dan seeder awal
php artisan migrate:fresh --seed
```

### 4. Menjalankan di Laravel Valet (Lokal)
```bash
valet park
# atau
valet link onew-clinic
# Buka di browser: http://onew-clinic.test
```

### 5. Menjalankan di VPS Linux (Nginx + PHP-FPM)
Aplikasi siap untuk direktori `public/` dengan Nginx:
```nginx
server {
    listen 80;
    server_name klinik.domainanda.com;
    root /var/www/onew-clinic/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 👥 Akun Default Hasil Seeding
| Role | Email | Password | Keterangan |
|---|---|---|---|
| Superadmin | `admin@onewclinic.com` | `password` | Akses penuh & Pengaturan White-Label |
| Resepsionis | `receptionist@onewclinic.com` | `password` | Pendaftaran Pasien & Buka Kiosk Tablet |
| Perawat | `nurse@onewclinic.com` | `password` | Pemeriksaan Awal, Dokter Jaga & Foto Before |
| Dokter | `doctor@onewclinic.com` | `password` | Tindakan, Catatan Klinis, Foto After, Sync Trustmedis |

---

## 🔄 Alur Kerja Sistem (5-Step Workflow)

1. **Step 1: Registrasi Pasien (Resepsionis) - `/patients/register`**
   - Cari pasien lama berdasarkan nama/telepon/kode RM, atau input kredensial pasien baru.
   - Menggunakan `PatientCodeGenerator` dengan `lockForUpdate()` di dalam transaksi DB untuk menghasilkan kode urut (`ONEW26-000001`).
   - Tombol **"Buka Mode Pasien (General Consent)"** langsung mengarahkan ke tablet kiosk.

2. **Step 2: Kiosk Pasien (Mode Tablet Pasien) - `/kiosk/{visit}`**
   - Tampilan bersih tanpa navigasi admin.
   - Ringkasan identitas pasien & teks General Consent dinamis dari database klinik.
   - Canvas Signature Pad interaktif (`signature_pad` + Alpine.js).
   - Simpan tanda tangan digital (PNG) ke storage, update status ke `consent_signed`.
   - Layar selesai dengan proteksi **PIN Staf (Default: `1234`)** untuk kembali ke dashboard.

3. **Step 3: Staf / Perawat (Pemeriksaan Awal) - `/nurse/examination`**
   - Memilih dokter jaga dan input nama asisten perawat.
   - Modul Kamera HTML5 (`capture="environment"`) dengan preview instan untuk **Foto Before**.
   - Simpan data dan update status ke `waiting_doctor`.

4. **Step 4: Dokter (Tindakan & Selesai) - `/doctor/treatment`**
   - Tinjauan identitas & Foto Before pasien.
   - Checklist multi-select tindakan medis estetika (`treatments`) beserta input catatan klinis per tindakan.
   - Modul Kamera HTML5 untuk **Foto After**.
   - Tombol **"Selesaikan Kunjungan & Sync Trustmedis"** mengubah status ke `completed` dan mengeksekusi payload sinkronisasi.

5. **Step 5: Laporan Rekap Kunjungan (Medical Summary Report PDF) - `/reports/visits/{visit}/pdf`**
   - Kop & Logo klinik dinamis sesuai identitas white-label klinik.
   - Detail pasien, nomor rekam medis, dokter pemeriksa, dan tim perawat.
   - Tanda tangan digital pasien tersemat rapi.
   - Tabel tindakan perawatan medis yang telah dilakukan.
   - Komparasi side-by-side **Foto Before & Foto After**.
   - Legalitas verifikasi Trustmedis EHR Gateway & cap tanda tangan elektronik dokter.

---

## 🧪 Eksekusi Testing Otomatis
Seluruh alur telah diverifikasi menggunakan test suite PHPUnit:
```bash
php artisan test
```
Hasil: **8 tests passed, 32 assertions** (termasuk verifikasi isolasi `lockForUpdate()`, integrasi Livewire flow, dan output DomPDF).
