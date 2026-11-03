<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Medis Pasien - {{ $visit->manual_patient_code ?: ($patient->patient_code ?? 'PASIEN') }}</title>
    <style>
        @page {
            margin: 12mm 15mm 12mm 15mm;
            size: a4 portrait;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #0f172a;
            line-height: 1.3;
            font-size: 8pt;
            background: #ffffff;
        }

        .page-break {
            page-break-after: always;
            clear: both;
        }

        /* Kop Surat Resmi */
        .kop-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        .kop-logo {
            width: 45%;
            vertical-align: middle;
            text-align: left;
        }

        .kop-logo img {
            height: 48px;
            width: auto;
            display: inline-block;
        }

        .kop-clinic-details {
            width: 55%;
            text-align: right;
            vertical-align: middle;
            font-size: 7.5pt;
            color: #475569;
            line-height: 1.25;
        }

        .kop-clinic-title {
            font-size: 9pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }

        /* Center Header for General Consent / Informed Consent */
        .center-header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .center-header img {
            height: 44px;
            width: auto;
            margin: 0 auto 3px auto;
            display: block;
        }

        .center-header h1 {
            margin: 2px 0 0 0;
            font-size: 13pt;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .center-header h2 {
            margin: 0;
            font-size: 11pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
        }

        .center-header p {
            margin: 1px 0 0 0;
            font-size: 7.5pt;
            color: #475569;
        }

        /* Title Box for Registration Form */
        .title-box-table {
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 6px;
        }

        .title-box-left {
            width: 60%;
            vertical-align: middle;
        }

        .title-box-left h1 {
            margin: 0;
            font-size: 12pt;
            font-weight: 900;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 0.5px;
        }

        .title-box-left p {
            margin: 2px 0 0 0;
            font-size: 8pt;
            font-weight: bold;
            color: #64748b;
        }

        .title-box-right {
            width: 40%;
            vertical-align: middle;
        }

        .rm-meta-card {
            border: 1.5px solid #0f172a;
            border-radius: 4px;
            padding: 4px 6px;
            font-size: 7.5pt;
            background-color: #f8fafc;
        }

        .rm-meta-card table {
            width: 100%;
            border-collapse: collapse;
        }

        .rm-meta-card td {
            padding: 1.5px 3px;
        }

        .rm-label {
            font-weight: bold;
            color: #334155;
            width: 45%;
        }

        .rm-val {
            font-weight: 900;
            color: #0f172a;
            font-family: monospace;
            font-size: 8.5pt;
        }

        /* Section Headings */
        .section-bar {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 8pt;
            padding: 3px 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 6px;
            margin-bottom: 4px;
            border-radius: 2px;
        }

        .section-bar-light {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            font-size: 8pt;
            padding: 3px 6px;
            text-transform: uppercase;
            border-left: 3px solid #0f172a;
            margin-top: 6px;
            margin-bottom: 4px;
        }

        /* Form Tables */
        .form-grid-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            margin-bottom: 4px;
        }

        .form-grid-table td {
            padding: 2.5px 4px;
            vertical-align: top;
            border-bottom: 1px dotted #e2e8f0;
        }

        .field-label {
            color: #475569;
            font-weight: 600;
            width: 25%;
        }

        .field-val {
            color: #0f172a;
            font-weight: normal;
            width: 75%;
        }

        .field-val-bold {
            color: #0f172a;
            font-weight: bold;
        }

        /* Tables */
        .bordered-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            margin-top: 4px;
            margin-bottom: 6px;
        }

        .bordered-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7pt;
            padding: 4px 5px;
            border: 1px solid #0f172a;
            text-align: left;
        }

        .bordered-table td {
            padding: 3.5px 5px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }

        /* Signatures */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .signature-cell {
            text-align: center;
            vertical-align: top;
            font-size: 7.5pt;
            padding: 0 8px;
        }

        .sig-image {
            height: 48px;
            max-width: 130px;
            display: block;
            margin: 3px auto;
        }

        .sig-line {
            border-top: 1px solid #475569;
            margin-top: 2px;
            padding-top: 2px;
            font-weight: bold;
        }

        /* PHOTO 5 SIDES FULL A4 PAGE LAYOUT */
        .photo-page-header {
            width: 100%;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 5px;
            margin-bottom: 12px;
        }

        .photo-page-title {
            font-size: 11pt;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .photo-meta-info {
            font-size: 7.5pt;
            color: #475569;
            margin-top: 2px;
        }

        .photo-grid-row-1 {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
            margin-bottom: 10px;
        }

        .photo-grid-row-2 {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
        }

        .photo-card {
            border: 1.5px solid #cbd5e1;
            border-radius: 6px;
            background-color: #f8fafc;
            text-align: center;
            padding: 6px;
            vertical-align: top;
        }

        .photo-card-label {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            padding: 2px 4px 4px 4px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }

        .photo-frame-img {
            display: block;
            margin: 0 auto;
            border-radius: 4px;
            max-width: 100%;
            object-fit: contain;
        }

        .photo-empty-box {
            background-color: #ffffff;
            border: 1px dashed #cbd5e1;
            border-radius: 4px;
            color: #94a3b8;
            font-style: italic;
            font-size: 8pt;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

    <!-- =================================================================== -->
    <!-- HALAMAN 1: FORMULIR PENDAFTARAN PASIEN (Sesuai formatform.pdf Hal 1-2) -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Kop Surat -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic">
                    @else
                        <span style="font-size: 16pt; font-weight: 900; letter-spacing: 1px;">ONEW</span>
                    @endif
                </td>
                <td class="kop-clinic-details">
                    <div class="kop-clinic-title">ONEW Klinik Utama</div>
                    <div>Jl. Laksana I no. 6, Kebayoran Baru, Jakarta 12180, Indonesia</div>
                    <div>website: onewclinic.com &bull; email: onewclinic@gmail.com</div>
                    <div>mobile: +62 811 1660 9589 &bull; phone: +62 21382 506 94</div>
                </td>
            </tr>
        </table>

        <!-- Judul Form & Kotak No. Rekam Medis -->
        <table class="title-box-table">
            <tr>
                <td class="title-box-left">
                    <h1>Formulir Pendaftaran Pasien</h1>
                    <p>ONEW KLINIK UTAMA</p>
                </td>
                <td class="title-box-right">
                    <div class="rm-meta-card">
                        <table>
                            <tr>
                                <td class="rm-label">No. Rekam Medis</td>
                                <td style="width: 5%;">:</td>
                                <td class="rm-val">{{ $visit->manual_patient_code ?: $patient->patient_code }}</td>
                            </tr>
                            <tr>
                                <td class="rm-label">Tanggal Daftar</td>
                                <td>:</td>
                                <td style="font-weight: 600;">
                                    {{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->format('d/m/Y') : $visit->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="rm-label">NO. ID</td>
                                <td>:</td>
                                <td style="font-weight: bold; font-family: monospace;">{{ $patient->inbody_id ?: '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Bagian A: Data Identitas Pasien -->
        <div class="section-bar">A. DATA IDENTITAS PASIEN</div>
        <table class="form-grid-table">
            <tr>
                <td class="field-label">Nama Lengkap Pasien</td>
                <td class="field-val"><strong style="font-size: 8.5pt;">{{ $patient->name }}</strong></td>
                <td class="field-label" style="width: 20%;">Jenis Kelamin</td>
                <td class="field-val" style="width: 25%;">{{ $patient->gender === 'female' ? 'Perempuan' : ($patient->gender === 'male' ? 'Laki-laki' : '-') }}</td>
            </tr>
            <tr>
                <td class="field-label">NIK / No. Paspor</td>
                <td class="field-val" style="font-family: monospace; font-weight: bold;">{{ $patient->nik ?: ($patient->nik_passport ?: '-') }}</td>
                <td class="field-label">Status Pernikahan</td>
                <td class="field-val">{{ $patient->marital_status ?: '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Tempat, Tanggal Lahir</td>
                <td class="field-val">
                    {{ $patient->birth_place ?: '-' }},
                    {{ $patient->date_of_birth ? $patient->date_of_birth->format('d/m/Y') : '-' }}
                    ({{ $patient->date_of_birth ? $patient->date_of_birth->age . ' Tahun' : '-' }})
                </td>
                <td class="field-label">Pekerjaan</td>
                <td class="field-val">{{ $patient->occupation ?: '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Alamat Sesuai KTP</td>
                <td class="field-val" colspan="3">{{ $patient->id_address ?: ($patient->address ?: '-') }}</td>
            </tr>
            <tr>
                <td class="field-label">Alamat Domisili</td>
                <td class="field-val" colspan="3">{{ $patient->domicile_address ?: ($patient->address ?: '-') }}</td>
            </tr>
            <tr>
                <td class="field-label">No. Telepon / WhatsApp</td>
                <td class="field-val">{{ $patient->phone_number ?: '-' }}</td>
                <td class="field-label">Alamat Email</td>
                <td class="field-val">{{ $patient->email ?: '-' }}</td>
            </tr>
        </table>

        <!-- Bagian B: Data Keluarga / Kontak Darurat -->
        <div class="section-bar">B. DATA KELUARGA / KONTAK DARURAT (EMERGENCY CONTACT)</div>
        <table class="form-grid-table">
            <tr>
                <td class="field-label">Nama Kontak Darurat</td>
                <td class="field-val"><strong>{{ $patient->emergency_contact_name ?: '-' }}</strong></td>
                <td class="field-label" style="width: 20%;">Hubungan</td>
                <td class="field-val" style="width: 25%;">{{ $patient->emergency_contact_relation ?: '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Nomor Telepon Darurat</td>
                <td class="field-val" colspan="3">{{ $patient->emergency_contact_phone ?: '-' }}</td>
            </tr>
        </table>

        <!-- Bagian C: Riwayat Pasien -->
        <div class="section-bar">C. RIWAYAT PASIEN</div>
        <table class="form-grid-table">
            <tr>
                <td class="field-label">Keluhan Utama</td>
                <td class="field-val" colspan="3">
                    <strong>{{ $visit->chief_complaint ?: 'Pemeriksaan rutin estetika / konsultasi klinis' }}</strong>
                </td>
            </tr>
            <tr>
                <td class="field-label">Riwayat Penyakit / Perawatan</td>
                <td class="field-val" colspan="3">
                    {{ $visit->doctor_notes ?: ($visit->diagnosis_dd ?: 'Tidak ada riwayat kontraindikasi khusus yang dilaporkan.') }}
                </td>
            </tr>
        </table>

        <!-- Bagian D: Dokter / Poli Tujuan & Persetujuan Komunikasi -->
        <div class="section-bar">D. DOKTER / POLI TUJUAN & PERSETUJUAN KOMUNIKASI</div>
        <table class="form-grid-table">
            <tr>
                <td class="field-label">Tujuan Kunjungan</td>
                <td class="field-val"><strong>{{ $visit->visit_purpose ?: 'Estetika' }}</strong></td>
                <td class="field-label" style="width: 20%;">Dokter / Poli Tujuan</td>
                <td class="field-val" style="width: 25%;">{{ $visit->destination_doctor_poli ?: '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Persetujuan Komunikasi</td>
                <td class="field-val" colspan="3">
                    [ X ] Bersedia menerima informasi, pengingat janji, dan komunikasi administratif melalui Telepon atau WhatsApp.
                </td>
            </tr>
        </table>

        <!-- Tanda Tangan Formulir Pendaftaran -->
        <table class="signature-table" style="margin-top: 14px;">
            <tr>
                <td class="signature-cell" style="width: 50%;">
                    <div>Petugas Pendaftaran,</div>
                    @if($registrationStaffSigBase64)
                        <img src="{{ $registrationStaffSigBase64 }}" class="sig-image" alt="TTD Petugas">
                    @else
                        <div style="height: 48px; line-height: 48px; font-style: italic; color: #94a3b8;">[Tanda Tangan Petugas]</div>
                    @endif
                    <div class="sig-line">
                        {{ $visit->registration_staff_signer_name ?: 'Petugas Pendaftaran ONEW' }}
                    </div>
                </td>
                <td class="signature-cell" style="width: 50%;">
                    <div>Pasien / Penanggung Jawab,</div>
                    @if($registrationPatientSigBase64 || $signatureBase64)
                        <img src="{{ $registrationPatientSigBase64 ?: $signatureBase64 }}" class="sig-image" alt="TTD Pasien">
                    @else
                        <div style="height: 48px; line-height: 48px; font-style: italic; color: #94a3b8;">[Tanda Tangan Pasien]</div>
                    @endif
                    <div class="sig-line">
                        {{ $visit->registration_patient_signer_name ?: $patient->name }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 2: GENERAL CONSENT (Sesuai formatform.pdf Hal 3-4)           -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Header General Consent -->
        <div class="center-header">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="ONEW Clinic">
            @endif
            <p style="font-weight: bold; text-transform: uppercase; font-size: 7.5pt; color: #1e293b; margin-top: 2px;">
                Klinik Utama ONEW &bull; PT Permata Medikal Jaya
            </p>
            <p>Jl. Laksana I no 6, Kebayoran Baru, Jakarta Selatan 12180 &bull; Telp: +62 8119 6977 886 / +62 21382 506 94</p>
            <h1 style="margin-top: 4px;">GENERAL CONSENT</h1>
            <p style="font-size: 8pt; font-weight: bold; color: #334155;">FORMULIR PERSETUJUAN UMUM PASIEN</p>
        </div>

        <!-- Identitas Pasien Ringkas -->
        <table class="bordered-table" style="margin-bottom: 6px; background-color: #f8fafc;">
            <tr>
                <td style="width: 20%; font-weight: bold;">Nama Pasien</td>
                <td style="width: 30%;"><strong>{{ $patient->name }}</strong></td>
                <td style="width: 20%; font-weight: bold;">No. Rekam Medis</td>
                <td style="width: 30%; font-family: monospace; font-weight: bold;">{{ $visit->manual_patient_code ?: $patient->patient_code }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Tgl Lahir / Usia</td>
                <td>
                    {{ $patient->date_of_birth ? $patient->date_of_birth->format('d/m/Y') : '-' }}
                    ({{ $patient->date_of_birth ? $patient->date_of_birth->age . ' Thn' : '-' }}) &bull; {{ $patient->gender === 'female' ? 'Perempuan' : 'Laki-laki' }}
                </td>
                <td style="font-weight: bold;">Alamat</td>
                <td>{{ $patient->address ?: '-' }}</td>
            </tr>
        </table>

        <!-- 8 Klausul General Consent -->
        <div style="font-size: 7.2pt; text-align: justify; line-height: 1.32; color: #1e293b;">
            <div style="margin-bottom: 3.5px;">
                <strong>1. PERSETUJUAN PELAYANAN KESEHATAN:</strong> Saya menyetujui untuk mendapatkan pelayanan medis, pemeriksaan fisik, perawatan keperawatan, prosedur diagnostik, dan terapi standar di Klinik Utama ONEW sesuai kebutuhan medis dan indikasi dokter yang bertugas.
            </div>
            <div style="margin-bottom: 3.5px;">
                <strong>2. HAK DAN KEWAJIBAN PASIEN:</strong> Saya telah memahami hak saya untuk memperoleh informasi mengenai diagnosis, rencana perawatan, perkiraan biaya, dan tata tertib klinik, serta berkewajiban memberikan informasi kesehatan yang jujur dan lengkap demi keselamatan medis saya sendiri.
            </div>
            <div style="margin-bottom: 3.5px;">
                <strong>3. PRIVASI DAN KERAHASIAAN MEDIS:</strong> Saya memberikan izin kepada tenaga medis dan staf resmi klinik untuk membuka informasi medis saya apabila diperlukan untuk kepentingan rujukan, kelanjutan asuhan medis, penjaminan biaya, atau kepatuhan peraturan perundang-undangan kesehatan.
            </div>
            <div style="margin-bottom: 3.5px;">
                <strong>4. PELEPASAN INFORMASI MEDIS:</strong> Saya mengizinkan rekam medis dan data pendukung saya disimpan secara aman dan dapat diakses oleh dokter dan tenaga medis yang berwenang di Klinik Utama ONEW sesuai standar regulasi rekam medis elektronik.
            </div>
            <div style="margin-bottom: 3.5px;">
                <strong>5. PENGINGAT JADWAL & KOMUNIKASI ADMINISTRASI:</strong> Saya menyetujui Klinik Utama ONEW untuk menghubungi saya melalui telepon, SMS, atau WhatsApp guna keperluan pengingat jadwal konsultasi, kontrol berkala, maupun tindak lanjut administrasi perawatan.
            </div>
            <div style="margin-bottom: 3.5px;">
                <strong>6. BARANG BERHARGA:</strong> Saya memahami bahwa pihak Klinik Utama ONEW tidak bertanggung jawab atas kehilangan, kerusakan, atau tertukarnya barang berharga pribadi yang dibawa oleh pasien/keluarga ke area klinik.
            </div>
            <div style="margin-bottom: 3.5px;">
                <strong>7. PEMBIAYAAN DAN TATA TERTIB PEMBAYARAN:</strong> Saya bersedia melunasi seluruh biaya atas konsultasi, tindakan medis penunjang, obat-obatan, dan layanan klinis yang diberikan sesuai tagihan resmi sebelum meninggalkan klinik.
            </div>
            <div style="margin-bottom: 3.5px;">
                <strong>8. PERNYATAAN PENUTUP:</strong> Saya menyatakan bahwa saya telah membaca, memahami, dan menyetujui seluruh klausul dalam Formulir Persetujuan Umum ini tanpa paksaan dari pihak mana pun.
            </div>
        </div>

        <!-- Tanda Tangan General Consent -->
        <table class="signature-table" style="margin-top: 14px;">
            <tr>
                <td class="signature-cell" style="width: 50%;">
                    <div>Petugas Pendaftaran / Pemberi Penjelasan,</div>
                    @if($generalConsentStaffSigBase64 || $registrationStaffSigBase64)
                        <img src="{{ $generalConsentStaffSigBase64 ?: $registrationStaffSigBase64 }}" class="sig-image" alt="TTD Petugas">
                    @else
                        <div style="height: 48px; line-height: 48px; font-style: italic; color: #94a3b8;">[Tanda Tangan Petugas]</div>
                    @endif
                    <div class="sig-line">
                        {{ $visit->general_consent_staff_name ?: ($visit->registration_staff_signer_name ?: 'Petugas Pendaftaran ONEW') }}
                    </div>
                </td>
                <td class="signature-cell" style="width: 50%;">
                    <div>Pasien / Penanggung Jawab,</div>
                    @if($signatureBase64 || $registrationPatientSigBase64)
                        <img src="{{ $signatureBase64 ?: $registrationPatientSigBase64 }}" class="sig-image" alt="TTD Pasien">
                    @else
                        <div style="height: 48px; line-height: 48px; font-style: italic; color: #94a3b8;">[Tanda Tangan Pasien]</div>
                    @endif
                    <div class="sig-line">
                        {{ $visit->general_consent_patient_signer_name ?: ($visit->registration_patient_signer_name ?: $patient->name) }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 3: PERSETUJUAN TINDAKAN KEDOKTERAN (Sesuai formatform.pdf Hal 5) -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Header Informed Consent -->
        <div class="center-header">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="ONEW Clinic">
            @endif
            <h2>PERSETUJUAN TINDAKAN KEDOKTERAN</h2>
            <p style="font-weight: bold; font-size: 8pt; color: #334155;">INFORMED CONSENT RESMI KLINIS</p>
        </div>

        <!-- Metadata Dokter & Penerima Informasi -->
        <table class="form-grid-table" style="margin-bottom: 6px;">
            <tr>
                <td class="field-label" style="width: 25%;">Dokter Pelaksana Tindakan</td>
                <td class="field-val" style="width: 25%;">
                    <strong>{{ $visit->informedConsent?->doctor_name ?: ($visit->doctor_name_override ?: ($visit->custom_doctor_name ?: ($visit->doctor?->name ?? '-'))) }}</strong>
                </td>
                <td class="field-label" style="width: 25%;">Penerima Informasi / Wali</td>
                <td class="field-val" style="width: 25%;">
                    <strong>{{ $visit->informedConsent?->recipient_name ?: $patient->name }}</strong>
                    <span style="font-size: 6.5pt; color: #64748b;">({{ $visit->informedConsent?->recipient_relation ?: 'Pasien Sendiri' }})</span>
                </td>
            </tr>
            <tr>
                <td class="field-label">Pemberi Penjelasan Medis</td>
                <td class="field-val">{{ $visit->informedConsent?->information_provider ?: ($visit->doctor_name_override ?: ($visit->custom_doctor_name ?: ($visit->doctor?->name ?? '-'))) }}</td>
                <td class="field-label">Pasien Sasaran Tindakan</td>
                <td class="field-val">{{ $patient->name }} ({{ $patient->date_of_birth ? $patient->date_of_birth->age . ' Thn' : '-' }})</td>
            </tr>
        </table>

        <!-- Tabel 11 Poin Pemberian Informasi Medis -->
        <table class="bordered-table" style="margin-bottom: 5px;">
            <thead>
                <tr>
                    <th style="width: 4%; text-align: center;">NO</th>
                    <th style="width: 24%;">JENIS INFORMASI</th>
                    <th style="width: 64%;">ISI INFORMASI MEDIS</th>
                    <th style="width: 8%; text-align: center;">TANDA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td><strong>Diagnosis (WD & DD)</strong></td>
                    <td>{{ $visit->informedConsent?->diagnosis_wd_dd ?: ($visit->diagnosis_wd ?: '-') }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">2</td>
                    <td><strong>Dasar Diagnosis</strong></td>
                    <td>{{ $visit->informedConsent?->diagnosis_basis ?: 'Pemeriksaan Klinis dan Anamnesis Dokter' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">3</td>
                    <td><strong>Tindakan Kedokteran</strong></td>
                    <td><strong>{{ $visit->informedConsent?->medical_procedure ?: ($visit->informedConsent?->action_name ?: 'Tindakan Estetika Medis Terpadu') }}</strong></td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">4</td>
                    <td><strong>Indikasi Tindakan</strong></td>
                    <td>{{ $visit->informedConsent?->indication ?: 'Peremajaan jaringan kulit, koreksi kontur & perbaikan tekstur' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">5</td>
                    <td><strong>Tata Cara Prosedur</strong></td>
                    <td>{{ $visit->informedConsent?->procedure_steps ?: 'Asepsis, anestesi topikal bila diperlukan, pelaksanaan prosedur secara aseptik' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">6</td>
                    <td><strong>Tujuan Tindakan</strong></td>
                    <td>{{ $visit->informedConsent?->procedure_goal ?: 'Memperbaiki tekstur kulit, meningkatkan elastisitas, dan peremajaan' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">7</td>
                    <td><strong>Risiko yang Mungkin Terjadi</strong></td>
                    <td>{{ $visit->informedConsent?->risks ?: 'Kemerahan sementara (eritema), rasa hangat, atau pembengkakan ringan' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">8</td>
                    <td><strong>Komplikasi</strong></td>
                    <td>{{ $visit->informedConsent?->complications ?: 'Hematoma ringan, reaksi hipersensitivitas minimal' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">9</td>
                    <td><strong>Prognosis</strong></td>
                    <td>{{ $visit->informedConsent?->prognosis ?: 'Dubia ad Bonam' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">10</td>
                    <td><strong>Alternatif & Risiko</strong></td>
                    <td>{{ $visit->informedConsent?->alternatives_risks ?: 'Perawatan topikal harian atau observasi klinis berkala' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">11</td>
                    <td><strong>Lain-lain / Catatan Pasca</strong></td>
                    <td>{{ $visit->informedConsent?->other_notes ?: ($visit->doctor_notes ?: 'Hindari paparan sinar matahari langsung dan gunakan sunscreen.') }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
            </tbody>
        </table>

        <!-- Paragraf Pernyataan Persetujuan Tindakan -->
        <div style="font-size: 7.2pt; text-align: justify; line-height: 1.3; background-color: #f8fafc; border: 1px solid #cbd5e1; padding: 4px 6px; border-radius: 3px; margin-bottom: 6px;">
            <strong>PERNYATAAN PERSETUJUAN:</strong> Yang bertanda tangan di bawah ini, saya <strong>{{ $visit->informedConsent?->recipient_name ?: $patient->name }}</strong>, umur {{ $visit->informedConsent?->recipient_age ?: ($patient->date_of_birth ? $patient->date_of_birth->age : '-') }} tahun, menyatakan <strong>SETUJU</strong> untuk dilakukan tindakan <strong>{{ $visit->informedConsent?->medical_procedure ?: ($visit->informedConsent?->action_name ?: 'Tindakan Medis Estetika') }}</strong> terhadap {{ $visit->informedConsent?->recipient_relation ?: 'diri saya sendiri' }} bernama <strong>{{ $patient->name }}</strong>. Saya telah menerima penjelasan secara memadai mengenai diagnosis, tata cara, risiko, dan manfaat tindakan tersebut.
        </div>

        <!-- Tanda Tangan 3 Pihak -->
        <table class="signature-table" style="margin-top: 6px;">
            <tr>
                <td class="signature-cell" style="width: 33.3%;">
                    <div>Dokter yang Menerangkan,</div>
                    @if($doctorConsentSigBase64)
                        <img src="{{ $doctorConsentSigBase64 }}" class="sig-image" alt="TTD Dokter">
                    @else
                        <div style="height: 48px; line-height: 48px; font-style: italic; color: #0284c7; font-weight: bold;">[Signed Electronically]</div>
                    @endif
                    <div class="sig-line">
                        {{ $visit->informedConsent?->doctor_name ?: ($visit->doctor_name_override ?: ($visit->custom_doctor_name ?: ($visit->doctor?->name ?? 'Dokter Pelaksana'))) }}
                    </div>
                </td>
                <td class="signature-cell" style="width: 33.3%;">
                    <div>Yang Menyatakan Persetujuan,</div>
                    @if($procedureConsentSigBase64 || $signatureBase64)
                        <img src="{{ $procedureConsentSigBase64 ?: $signatureBase64 }}" class="sig-image" alt="TTD Pasien">
                    @else
                        <div style="height: 48px; line-height: 48px; font-style: italic; color: #94a3b8;">[Tanda Tangan Pasien]</div>
                    @endif
                    <div class="sig-line">
                        {{ $visit->informedConsent?->recipient_name ?: $patient->name }}
                    </div>
                </td>
                <td class="signature-cell" style="width: 33.3%;">
                    <div>Saksi Tindakan Medis,</div>
                    @if($witnessConsentSigBase64)
                        <img src="{{ $witnessConsentSigBase64 }}" class="sig-image" alt="TTD Saksi">
                    @else
                        <div style="height: 48px; line-height: 48px; font-style: italic; color: #94a3b8;">[Tanda Tangan Saksi]</div>
                    @endif
                    <div class="sig-line">
                        {{ $visit->informedConsent?->witness_name ?: 'Saksi Klinik ONEW' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 4: BHP TINDAKAN HARIAN ONEW (Sesuai formatform.pdf Hal 6)   -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Header BHP -->
        <div class="center-header">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="ONEW Clinic">
            @endif
            <h2>BHP TINDAKAN HARIAN</h2>
            <p style="font-weight: bold; font-size: 8pt; color: #334155;">KLINIK UTAMA ONEW</p>
        </div>

        <!-- Metadata Form BHP (4 Kotak) -->
        <table class="bordered-table" style="margin-bottom: 8px; background-color: #f8fafc;">
            <tr>
                <td style="width: 18%; font-weight: bold;">NAMA PASIEN</td>
                <td style="width: 32%; font-weight: bold; font-size: 8.5pt;">{{ $patient->name }}</td>
                <td style="width: 18%; font-weight: bold;">PERAWAT / TERAPIS</td>
                <td style="width: 32%;">{{ $visit->therapist_nurse_name ?: ($visit->custom_nurse_name ?: ($visit->assistant_name ?? ($visit->nurse?->name ?? '-'))) }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">DOKTER</td>
                <td>{{ $visit->doctor_name_override ?: ($visit->custom_doctor_name ?: ($visit->doctor?->name ?? '-')) }}</td>
                <td style="font-weight: bold;">TANGGAL</td>
                <td style="font-weight: bold; font-family: monospace;">
                    {{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->translatedFormat('d F Y') : $visit->created_at->translatedFormat('d F Y') }}
                </td>
            </tr>
        </table>

        <!-- Layout 2 Kolom Sejajar: Kolom Kiri TINDAKAN & Kolom Kanan BAHAN/BHP -->
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <!-- Kolom Kiri: Tindakan Medis -->
                <td style="width: 44%; vertical-align: top; padding-right: 6px;">
                    <table class="bordered-table" style="margin-top: 0;">
                        <thead>
                            <tr>
                                <th style="width: 12%; text-align: center;">NO</th>
                                <th style="width: 88%;">TINDAKAN MEDIS / DOSIS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($visit->custom_treatment_list && count($visit->custom_treatment_list) > 0)
                                @foreach($visit->custom_treatment_list as $tIdx => $tItem)
                                    <tr>
                                        <td style="text-align: center; font-weight: bold;">{{ $tIdx + 1 }}.</td>
                                        <td><strong>{{ $tItem['action'] ?? ($tItem['name'] ?? '-') }}</strong></td>
                                    </tr>
                                @endforeach
                            @elseif($visit->treatments && $visit->treatments->count() > 0)
                                @foreach($visit->treatments as $tIdx => $treatment)
                                    <tr>
                                        <td style="text-align: center; font-weight: bold;">{{ $tIdx + 1 }}.</td>
                                        <td>
                                            <strong>{{ $treatment->name }}</strong>
                                            @if($treatment->pivot->notes)
                                                <div style="font-size: 6.5pt; color: #475569;">{{ $treatment->pivot->notes }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td style="text-align: center;">1.</td>
                                    <td>{{ $visit->informedConsent?->action_name ?: 'Tindakan Estetika Klinis Terjadwal' }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">2.</td>
                                    <td style="color: #94a3b8; font-style: italic;">—</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">3.</td>
                                    <td style="color: #94a3b8; font-style: italic;">—</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </td>

                <!-- Kolom Kanan: Bahan Habis Pakai (BHP & QT) -->
                <td style="width: 56%; vertical-align: top; padding-left: 6px;">
                    <table class="bordered-table" style="margin-top: 0;">
                        <thead>
                            <tr>
                                <th style="width: 10%; text-align: center;">NO</th>
                                <th style="width: 60%;">NAMA BAHAN MEDIS (BHP)</th>
                                <th style="width: 30%; text-align: center;">QT (JUMLAH)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($visit->visitConsumables && $visit->visitConsumables->count() > 0)
                                @foreach($visit->visitConsumables as $bIdx => $vc)
                                    <tr>
                                        <td style="text-align: center;">{{ $bIdx + 1 }}</td>
                                        <td><strong>{{ $vc->consumable?->name ?? 'BHP Item' }}</strong></td>
                                        <td style="text-align: center; font-weight: bold; font-family: monospace;">
                                            {{ $vc->quantity }} {{ $vc->consumable?->unit ?? 'pcs' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @php
                                    $sampleConsumables = $consumables->take(12);
                                @endphp
                                @foreach($sampleConsumables as $bIdx => $cItem)
                                    <tr>
                                        <td style="text-align: center;">{{ $bIdx + 1 }}</td>
                                        <td>{{ $cItem->name }}</td>
                                        <td style="text-align: center; color: #94a3b8;">-</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Tanda Tangan Dokter & Perawat di Bagian Bawah BHP -->
        <table class="signature-table" style="margin-top: 14px;">
            <tr>
                <td class="signature-cell" style="width: 50%;">
                    <div>Dokter Pelaksana,</div>
                    @if($doctorConsentSigBase64)
                        <img src="{{ $doctorConsentSigBase64 }}" class="sig-image" alt="TTD Dokter">
                    @else
                        <div style="height: 48px; line-height: 48px; font-style: italic; color: #0284c7; font-weight: bold;">[Signed Electronically]</div>
                    @endif
                    <div class="sig-line">
                        {{ $visit->doctor_name_override ?: ($visit->custom_doctor_name ?: ($visit->doctor?->name ?? 'Dokter Pelaksana')) }}
                    </div>
                </td>
                <td class="signature-cell" style="width: 50%;">
                    <div>Perawat / Terapis Asisten,</div>
                    @if($generalConsentStaffSigBase64 || $registrationStaffSigBase64)
                        <img src="{{ $generalConsentStaffSigBase64 ?: $registrationStaffSigBase64 }}" class="sig-image" alt="TTD Perawat">
                    @else
                        <div style="height: 48px; line-height: 48px; font-style: italic; color: #94a3b8;">[Tanda Tangan Perawat]</div>
                    @endif
                    <div class="sig-line">
                        {{ $visit->therapist_nurse_name ?: ($visit->custom_nurse_name ?: ($visit->assistant_name ?? ($visit->nurse?->name ?? 'Perawat Pelaksana'))) }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 5: FULL SATU HALAMAN A4 DOKUMENTASI FOTO 5 SISI BEFORE      -->
    <!-- =================================================================== -->
    <div class="page-container" style="height: 100%;">
        <!-- Header Khusus Foto Before -->
        <table class="photo-page-header">
            <tr>
                <td style="width: 25%; vertical-align: middle;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic" style="height: 38px; width: auto;">
                    @else
                        <strong style="font-size: 14pt;">ONEW</strong>
                    @endif
                </td>
                <td style="width: 75%; text-align: right; vertical-align: middle;">
                    <div class="photo-page-title">DOKUMENTASI FOTO 5 SISI WAJAH — SEBELUM TINDAKAN (BEFORE)</div>
                    <div class="photo-meta-info">
                        <strong>{{ $patient->name }}</strong> &bull;
                        No. RM: <span style="font-family: monospace; font-weight: bold;">{{ $visit->manual_patient_code ?: $patient->patient_code }}</span> &bull;
                        Tgl: {{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->format('d/m/Y') : $visit->created_at->format('d/m/Y') }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Row 1: 3 Foto (Serong Kiri 45°, Tampak Depan / Center, Serong Kanan 45°) -->
        <table class="photo-grid-row-1">
            <tr>
                <!-- 1. Serong Kiri -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">1. Serong Kiri (45&deg;)</div>
                    @if($photoBeforeLeftTiltBase64)
                        <img src="{{ $photoBeforeLeftTiltBase64 }}" class="photo-frame-img" style="height: 310px;" alt="Before Serong Kiri">
                    @else
                        <div class="photo-empty-box" style="height: 310px; line-height: 310px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 2. Tampak Depan (Center Focus) -->
                <td class="photo-card" style="width: 33.3%; border-color: #0f172a; background-color: #ffffff;">
                    <div class="photo-card-label" style="color: #0f172a; font-weight: 900; background-color: #f1f5f9;">2. TAMPAK DEPAN (CENTER)</div>
                    @if($photoBeforeFrontBase64 || $photoBeforeBase64)
                        <img src="{{ $photoBeforeFrontBase64 ?: $photoBeforeBase64 }}" class="photo-frame-img" style="height: 310px;" alt="Before Tampak Depan">
                    @else
                        <div class="photo-empty-box" style="height: 310px; line-height: 310px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 3. Serong Kanan -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">3. Serong Kanan (45&deg;)</div>
                    @if($photoBeforeRightTiltBase64)
                        <img src="{{ $photoBeforeRightTiltBase64 }}" class="photo-frame-img" style="height: 310px;" alt="Before Serong Kanan">
                    @else
                        <div class="photo-empty-box" style="height: 310px; line-height: 310px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Row 2: 2 Foto Profil (Samping Kiri 90°, Samping Kanan 90°) -->
        <table class="photo-grid-row-2">
            <tr>
                <!-- 4. Samping Kiri -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">4. Samping Kiri (Profil 90&deg;)</div>
                    @if($photoBeforeLeftSideBase64)
                        <img src="{{ $photoBeforeLeftSideBase64 }}" class="photo-frame-img" style="height: 310px;" alt="Before Samping Kiri">
                    @else
                        <div class="photo-empty-box" style="height: 310px; line-height: 310px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 5. Samping Kanan -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">5. Samping Kanan (Profil 90&deg;)</div>
                    @if($photoBeforeRightSideBase64)
                        <img src="{{ $photoBeforeRightSideBase64 }}" class="photo-frame-img" style="height: 310px;" alt="Before Samping Kanan">
                    @else
                        <div class="photo-empty-box" style="height: 310px; line-height: 310px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 6: FULL SATU HALAMAN A4 DOKUMENTASI FOTO 5 SISI AFTER       -->
    <!-- =================================================================== -->
    <div class="page-container" style="height: 100%;">
        <!-- Header Khusus Foto After -->
        <table class="photo-page-header">
            <tr>
                <td style="width: 25%; vertical-align: middle;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic" style="height: 38px; width: auto;">
                    @else
                        <strong style="font-size: 14pt;">ONEW</strong>
                    @endif
                </td>
                <td style="width: 75%; text-align: right; vertical-align: middle;">
                    <div class="photo-page-title">DOKUMENTASI FOTO 5 SISI WAJAH — SESUDAH TINDAKAN (AFTER)</div>
                    <div class="photo-meta-info">
                        <strong>{{ $patient->name }}</strong> &bull;
                        No. RM: <span style="font-family: monospace; font-weight: bold;">{{ $visit->manual_patient_code ?: $patient->patient_code }}</span> &bull;
                        Tgl: {{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->format('d/m/Y') : $visit->created_at->format('d/m/Y') }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Row 1: 3 Foto (Serong Kiri 45°, Tampak Depan / Center, Serong Kanan 45°) -->
        <table class="photo-grid-row-1">
            <tr>
                <!-- 1. Serong Kiri -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">1. Serong Kiri (45&deg;)</div>
                    @if($photoAfterLeftTiltBase64)
                        <img src="{{ $photoAfterLeftTiltBase64 }}" class="photo-frame-img" style="height: 310px;" alt="After Serong Kiri">
                    @else
                        <div class="photo-empty-box" style="height: 310px; line-height: 310px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 2. Tampak Depan (Center Focus) -->
                <td class="photo-card" style="width: 33.3%; border-color: #0f172a; background-color: #ffffff;">
                    <div class="photo-card-label" style="color: #0f172a; font-weight: 900; background-color: #f1f5f9;">2. TAMPAK DEPAN (CENTER)</div>
                    @if($photoAfterFrontBase64 || $photoAfterBase64)
                        <img src="{{ $photoAfterFrontBase64 ?: $photoAfterBase64 }}" class="photo-frame-img" style="height: 310px;" alt="After Tampak Depan">
                    @else
                        <div class="photo-empty-box" style="height: 310px; line-height: 310px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 3. Serong Kanan -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">3. Serong Kanan (45&deg;)</div>
                    @if($photoAfterRightTiltBase64)
                        <img src="{{ $photoAfterRightTiltBase64 }}" class="photo-frame-img" style="height: 310px;" alt="After Serong Kanan">
                    @else
                        <div class="photo-empty-box" style="height: 310px; line-height: 310px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Row 2: 2 Foto Profil (Samping Kiri 90°, Samping Kanan 90°) -->
        <table class="photo-grid-row-2">
            <tr>
                <!-- 4. Samping Kiri -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">4. Samping Kiri (Profil 90&deg;)</div>
                    @if($photoAfterLeftSideBase64)
                        <img src="{{ $photoAfterLeftSideBase64 }}" class="photo-frame-img" style="height: 310px;" alt="After Samping Kiri">
                    @else
                        <div class="photo-empty-box" style="height: 310px; line-height: 310px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 5. Samping Kanan -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">5. Samping Kanan (Profil 90&deg;)</div>
                    @if($photoAfterRightSideBase64)
                        <img src="{{ $photoAfterRightSideBase64 }}" class="photo-frame-img" style="height: 310px;" alt="After Samping Kanan">
                    @else
                        <div class="photo-empty-box" style="height: 310px; line-height: 310px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
