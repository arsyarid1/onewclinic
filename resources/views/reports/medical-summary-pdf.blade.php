<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Medis Pasien - {{ $visit->manual_patient_code ?: ($patient->patient_code ?? 'PASIEN') }}</title>
    <style>
        @page {
            margin: 7mm 11mm 7mm 11mm;
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
            line-height: 1.25;
            font-size: 7.2pt;
            background: #ffffff;
        }

        .page-break {
            page-break-after: always;
            clear: both;
        }

        .page-container {
            width: 100%;
        }

        /* Kop Surat Resmi ONEW */
        .kop-table {
            width: 100%;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        .kop-logo {
            width: 40%;
            vertical-align: middle;
            text-align: left;
        }

        .kop-logo img {
            height: 36px;
            width: auto;
            display: inline-block;
        }

        .kop-clinic-details {
            width: 60%;
            text-align: right;
            vertical-align: middle;
            font-size: 6.5pt;
            color: #475569;
            line-height: 1.2;
        }

        .kop-clinic-title {
            font-size: 8pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Center Header for General Consent / Informed Consent / BHP */
        .center-header {
            text-align: center;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 3px;
            margin-bottom: 6px;
        }

        .center-header img {
            height: 34px;
            width: auto;
            margin: 0 auto 2px auto;
            display: block;
        }

        .center-header h1 {
            margin: 1px 0 0 0;
            font-size: 10pt;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .center-header h2 {
            margin: 0;
            font-size: 9.5pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
        }

        .center-header p {
            margin: 1px 0 0 0;
            font-size: 6.8pt;
            color: #475569;
        }

        /* Title Box for Registration Form */
        .title-box-table {
            width: 100%;
            margin-bottom: 5px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
        }

        .title-box-left {
            width: 58%;
            vertical-align: middle;
        }

        .title-box-left h1 {
            margin: 0;
            font-size: 9.5pt;
            font-weight: 900;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 0.3px;
        }

        .title-box-left p {
            margin: 1px 0 0 0;
            font-size: 7pt;
            font-weight: bold;
            color: #64748b;
        }

        .title-box-right {
            width: 42%;
            vertical-align: middle;
        }

        .rm-meta-card {
            border: 1px solid #0f172a;
            border-radius: 3px;
            padding: 3px 5px;
            font-size: 6.8pt;
            background-color: #f8fafc;
        }

        .rm-meta-card table {
            width: 100%;
            border-collapse: collapse;
        }

        .rm-meta-card td {
            padding: 1px 2px;
        }

        .rm-label {
            font-weight: bold;
            color: #334155;
            width: 42%;
        }

        .rm-val {
            font-weight: 900;
            color: #0f172a;
            font-family: monospace;
            font-size: 7.5pt;
        }

        /* Section Headings */
        .section-bar {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 6.8pt;
            padding: 2px 6px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 4px;
            margin-bottom: 2px;
            border-radius: 2px;
        }

        /* Form Box Table (Authentic Clinical Form Styling) */
        .form-box-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.8pt;
            margin-bottom: 3px;
            border: 1px solid #cbd5e1;
        }

        .form-box-table td {
            border: 1px solid #cbd5e1;
            padding: 2.2px 5px;
            vertical-align: middle;
        }

        .form-box-table .lbl {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #334155;
            width: 22%;
        }

        .form-box-table .val {
            background-color: #ffffff;
            color: #0f172a;
        }

        .chk-box {
            font-family: monospace;
            font-size: 6.8pt;
            font-weight: bold;
        }

        /* Bordered Tables for General Consent & Informed Consent */
        .bordered-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.8pt;
            margin-top: 3px;
            margin-bottom: 4px;
        }

        .bordered-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 6.5pt;
            padding: 2.5px 4px;
            border: 1px solid #0f172a;
            text-align: left;
        }

        .bordered-table td {
            padding: 2px 4px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }

        /* Signatures */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .signature-cell {
            text-align: center;
            vertical-align: top;
            font-size: 6.8pt;
            padding: 0 8px;
        }

        .sig-image {
            height: 32px;
            max-width: 110px;
            display: block;
            margin: 1px auto;
        }

        .sig-line {
            border-top: 1px solid #475569;
            margin-top: 2px;
            padding-top: 1.5px;
            font-weight: bold;
            font-size: 6.8pt;
        }

        /* PHOTO 5 SIDES FULL A4 PAGE LAYOUT (UN-STRETCHED, ASPECT-RATIO PRESERVED) */
        .photo-page-header {
            width: 100%;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 2px;
            margin-bottom: 4px;
        }

        .photo-page-title {
            font-size: 8.5pt;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.3px;
        }

        .photo-meta-info {
            font-size: 6.5pt;
            color: #475569;
            margin-top: 0.5px;
        }

        .photo-grid-row-1 {
            width: 100%;
            border-collapse: separate;
            border-spacing: 4px;
            margin-bottom: 4px;
        }

        .photo-grid-row-2 {
            width: 100%;
            border-collapse: separate;
            border-spacing: 4px;
        }

        .photo-card {
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            background-color: #f8fafc;
            text-align: center;
            vertical-align: middle;
            padding: 3px;
            height: 420px;
        }

        .photo-card-label {
            font-size: 7.2pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            padding: 2px 4px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 4px;
            letter-spacing: 0.3px;
        }

        /* Crucial: max-height and max-width with width/height auto PREVENTS STRETCHING in DomPDF */
        .photo-img-contain {
            display: inline-block;
            margin: 0 auto;
            border-radius: 3px;
            max-width: 95%;
            max-height: 385px;
            width: auto;
            height: auto;
            vertical-align: middle;
        }

        .photo-empty-box {
            background-color: #ffffff;
            border: 1px dashed #cbd5e1;
            border-radius: 3px;
            color: #94a3b8;
            font-style: italic;
            font-size: 7.2pt;
            text-align: center;
            height: 385px;
            line-height: 385px;
        }
    </style>
</head>
<body>

    <!-- =================================================================== -->
    <!-- HALAMAN 1: FORMULIR PENDAFTARAN PASIEN (LENGKAP TEPAT 1 HALAMAN A4)  -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Kop Surat Resmi -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic">
                    @else
                        <span style="font-size: 14pt; font-weight: 900; letter-spacing: 1px;">ONEW</span>
                    @endif
                </td>
                <td class="kop-clinic-details">
                    <div class="kop-clinic-title">ONEW KLINIK UTAMA</div>
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
        <table class="form-box-table">
            <tr>
                <td class="lbl">Nama Lengkap Pasien</td>
                <td class="val" style="width: 36%;"><strong>{{ $patient->name }}</strong></td>
                <td class="lbl" style="width: 18%;">Jenis Kelamin</td>
                <td class="val" style="width: 24%;">{{ $patient->gender === 'female' ? 'Perempuan' : ($patient->gender === 'male' ? 'Laki-laki' : '-') }}</td>
            </tr>
            <tr>
                <td class="lbl">NIK / No. Paspor</td>
                <td class="val" style="font-family: monospace; font-weight: bold;">{{ $patient->nik ?: ($patient->nik_passport ?: '-') }}</td>
                <td class="lbl">Status Pernikahan</td>
                <td class="val">{{ $patient->marital_status ?: '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Tempat, Tanggal Lahir</td>
                <td class="val">
                    {{ $patient->birth_place ?: '-' }},
                    {{ $patient->date_of_birth ? $patient->date_of_birth->format('d/m/Y') : '-' }}
                    ({{ $patient->date_of_birth ? $patient->date_of_birth->age . ' Thn' : '-' }})
                </td>
                <td class="lbl">Pekerjaan</td>
                <td class="val">{{ $patient->occupation ?: '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Alamat Sesuai KTP</td>
                <td class="val" colspan="3">{{ $patient->id_address ?: ($patient->address ?: '-') }}</td>
            </tr>
            <tr>
                <td class="lbl">Alamat Domisili</td>
                <td class="val" colspan="3">{{ $patient->domicile_address ?: ($patient->address ?: '-') }}</td>
            </tr>
            <tr>
                <td class="lbl">No. Telepon / WhatsApp</td>
                <td class="val">{{ $patient->phone_number ?: '-' }}</td>
                <td class="lbl">Alamat Email</td>
                <td class="val">{{ $patient->email ?: '-' }}</td>
            </tr>
        </table>

        <!-- Bagian B: Data Keluarga / Kontak Darurat -->
        <div class="section-bar">B. DATA KELUARGA / KONTAK DARURAT (EMERGENCY CONTACT)</div>
        <table class="form-box-table">
            <tr>
                <td class="lbl">Nama Kontak Darurat</td>
                <td class="val" style="width: 36%;"><strong>{{ $patient->emergency_contact_name ?: '-' }}</strong></td>
                <td class="lbl" style="width: 18%;">Hubungan</td>
                <td class="val" style="width: 24%;">{{ $patient->emergency_contact_relation ?: '-' }}</td>
            </tr>
            <tr>
                <td class="lbl">Nomor Telepon Darurat</td>
                <td class="val" colspan="3">{{ $patient->emergency_contact_phone ?: '-' }}</td>
            </tr>
        </table>

        <!-- Bagian C: Data Penjamin / Pembayaran -->
        <div class="section-bar">C. DATA PENJAMIN / PEMBAYARAN</div>
        <table class="form-box-table">
            <tr>
                <td class="lbl">Jenis Pembayaran</td>
                <td class="val" colspan="3">
                    <span class="chk-box">[{{ ($patient->payment_type === 'Pribadi / Umum' || !$patient->payment_type) ? 'X' : ' ' }}]</span> Pribadi / Umum
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $patient->payment_type === 'Asuransi' ? 'X' : ' ' }}]</span> Asuransi
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $patient->payment_type === 'Perusahaan' ? 'X' : ' ' }}]</span> Perusahaan
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $patient->payment_type === 'Lainnya' ? 'X' : ' ' }}]</span> Lainnya
                </td>
            </tr>
            <tr>
                <td class="lbl">Nama Asuransi / PT</td>
                <td class="val" style="width: 36%;">{{ $patient->insurance_company_name ?: '-' }}</td>
                <td class="lbl" style="width: 18%;">No. Polis / Kartu</td>
                <td class="val" style="width: 24%;">{{ $patient->insurance_card_number ?: '-' }}</td>
            </tr>
        </table>

        <!-- Bagian D: Informasi Pelayanan -->
        <div class="section-bar">D. INFORMASI PELAYANAN</div>
        <table class="form-box-table">
            <tr>
                <td class="lbl">Status Pasien</td>
                <td class="val" colspan="3">
                    <span class="chk-box">[{{ ($visit->patient_service_status === 'Pasien Baru' || !$visit->patient_service_status) ? 'X' : ' ' }}]</span> Pasien Baru
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $visit->patient_service_status === 'Pasien Lama' ? 'X' : ' ' }}]</span> Pasien Lama
                </td>
            </tr>
            <tr>
                <td class="lbl">Tujuan Kunjungan</td>
                <td class="val" colspan="3">
                    <span class="chk-box">[{{ $visit->visit_purpose === 'Rawat Jalan' ? 'X' : ' ' }}]</span> Rawat Jalan / Dokter Umum
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ ($visit->visit_purpose === 'Estetika' || !$visit->visit_purpose) ? 'X' : ' ' }}]</span> Estetika
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $visit->visit_purpose === 'Gigi' ? 'X' : ' ' }}]</span> Gigi
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $visit->visit_purpose === 'Spesialis' ? 'X' : ' ' }}]</span> Spesialis
                </td>
            </tr>
            <tr>
                <td class="lbl">Dokter / Poli Tujuan</td>
                <td class="val" style="width: 36%;"><strong>{{ $visit->destination_doctor_poli ?: '-' }}</strong></td>
                <td class="lbl" style="width: 18%;">Keluhan / Keperluan</td>
                <td class="val" style="width: 24%;">{{ $visit->chief_complaint ?: 'Konsultasi dan perawatan estetika klinis berkala.' }}</td>
            </tr>
        </table>

        <!-- Bagian E: Informasi Tambahan -->
        <div class="section-bar">E. INFORMASI TAMBAHAN</div>
        <table class="form-box-table">
            <tr>
                <td class="lbl">Mengetahui Klinik dari</td>
                <td class="val" colspan="3">
                    <span class="chk-box">[{{ $patient->referral_source === 'Keluarga / Teman' ? 'X' : ' ' }}]</span> Keluarga / Teman
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ ($patient->referral_source === 'Instagram / TikTok' || !$patient->referral_source) ? 'X' : ' ' }}]</span> Instagram / TikTok
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $patient->referral_source === 'Google / Internet' ? 'X' : ' ' }}]</span> Google / Internet
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $patient->referral_source === 'Iklan' ? 'X' : ' ' }}]</span> Iklan
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $patient->referral_source === 'Rujukan Dokter' ? 'X' : ' ' }}]</span> Rujukan
                </td>
            </tr>
        </table>

        <!-- Bagian F: Pernyataan dan Persetujuan Pasien -->
        <div class="section-bar">F. PERNYATAAN DAN PERSETUJUAN PASIEN</div>
        <div style="font-size: 6.2pt; text-align: justify; line-height: 1.25; padding: 3px 6px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 2px; margin-bottom: 3px;">
            <div style="margin-bottom: 1px;">1. Saya menyatakan bahwa seluruh data yang saya berikan dalam formulir ini adalah benar dan dapat dipertanggungjawabkan.</div>
            <div style="margin-bottom: 1px;">2. Saya bersedia memberikan informasi yang diperlukan untuk proses pendaftaran, pelayanan kesehatan, administrasi, penagihan, dan pencatatan rekam medis sesuai ketentuan yang berlaku.</div>
            <div style="margin-bottom: 1px;">3. Saya memahami bahwa informasi medis yang lebih rinci akan diperoleh melalui anamnesis, pemeriksaan, dan/atau formulir persetujuan tindakan medis sesuai jenis pelayanan yang saya terima.</div>
            <div>4. Saya bersedia mematuhi ketentuan pelayanan dan tata tertib klinik yang berlaku.</div>
            <div style="margin-top: 2px; border-top: 0.5px dashed #cbd5e1; padding-top: 1.5px; font-weight: bold;">
                Persetujuan menerima informasi/komunikasi administratif melalui telepon atau WhatsApp:
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="chk-box">[{{ ($visit->whatsapp_comm_consent ?? true) ? 'X' : ' ' }}]</span> Saya SETUJU
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="chk-box">[{{ !($visit->whatsapp_comm_consent ?? true) ? 'X' : ' ' }}]</span> Saya TIDAK SETUJU
            </div>
        </div>

        <!-- Tanda Tangan Formulir Pendaftaran -->
        <table class="signature-table" style="margin-top: 4px;">
            <tr>
                <td class="signature-cell" style="width: 50%;">
                    <div style="font-weight: bold; font-size: 6.8pt;">PASIEN / PENANGGUNG JAWAB</div>
                    <div style="font-size: 6pt; color: #475569; margin-top: 0.5px;">
                        Tanggal: {{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->format('d/m/Y') : $visit->created_at->format('d/m/Y') }}
                    </div>
                    @if($registrationPatientSigBase64 || $signatureBase64)
                        <img src="{{ $registrationPatientSigBase64 ?: $signatureBase64 }}" class="sig-image" alt="TTD Pasien">
                    @else
                        <div style="height: 32px; line-height: 32px; font-style: italic; color: #94a3b8; font-size: 6.5pt;">[Tanda Tangan Pasien]</div>
                    @endif
                    <div class="sig-line">
                        Nama: {{ $visit->registration_patient_signer_name ?: $patient->name }}
                    </div>
                </td>
                <td class="signature-cell" style="width: 50%;">
                    <div style="font-weight: bold; font-size: 6.8pt;">PETUGAS PENDAFTARAN</div>
                    <div style="font-size: 6pt; color: #475569; margin-top: 0.5px;">
                        Tanggal: {{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->format('d/m/Y') : $visit->created_at->format('d/m/Y') }}
                    </div>
                    @if($registrationStaffSigBase64)
                        <img src="{{ $registrationStaffSigBase64 }}" class="sig-image" alt="TTD Petugas">
                    @else
                        <div style="height: 32px; line-height: 32px; font-style: italic; color: #94a3b8; font-size: 6.5pt;">[Tanda Tangan Petugas]</div>
                    @endif
                    <div class="sig-line">
                        Nama: {{ $visit->registration_staff_signer_name ?: 'Petugas Pendaftaran ONEW' }}
                    </div>
                </td>
            </tr>
        </table>
        <div style="font-size: 5.5pt; color: #94a3b8; text-align: center; margin-top: 3px;">
            Catatan: Formulir ini merupakan formulir pendaftaran pasien dan tidak menggantikan informed consent atau persetujuan tindakan medis yang diperlukan untuk tindakan tertentu.
        </div>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 2: GENERAL CONSENT (TEPAT 1 HALAMAN LENGKAP SAMPAI TTD)     -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Header General Consent -->
        <div class="center-header">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="ONEW Clinic">
            @endif
            <p style="font-weight: bold; text-transform: uppercase; font-size: 7.5pt; color: #1e293b; margin-top: 1px;">
                Klinik Utama ONEW &bull; PT Permata Medikal Jaya
            </p>
            <p>Jl. Laksana I no 6, Kebayoran Baru, Jakarta Selatan 12180 &bull; Telp: +62 8119 6977 886 / +62 21382 506 94</p>
            <h1>GENERAL CONSENT</h1>
            <p style="font-size: 7.5pt; font-weight: bold; color: #334155;">FORMULIR PERSETUJUAN UMUM PASIEN</p>
        </div>

        <!-- Identitas Pasien Ringkas -->
        <table class="bordered-table" style="margin-bottom: 5px; background-color: #f8fafc;">
            <tr>
                <td style="width: 18%; font-weight: bold;">Nama Pasien</td>
                <td style="width: 32%;"><strong>{{ $patient->name }}</strong></td>
                <td style="width: 18%; font-weight: bold;">No. Rekam Medis</td>
                <td style="width: 32%; font-family: monospace; font-weight: bold;">{{ $visit->manual_patient_code ?: $patient->patient_code }}</td>
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

        <!-- 8 Klausul General Consent Ringkas & Padat -->
        <div style="font-size: 7.2pt; text-align: justify; line-height: 1.35; color: #1e293b;">
            <div style="margin-bottom: 4px;">
                <strong>1. PERSETUJUAN PELAYANAN KESEHATAN:</strong> Saya menyetujui untuk mendapatkan pelayanan medis, pemeriksaan fisik, asuhan keperawatan, prosedur diagnostik, dan terapi standar di Klinik Utama ONEW sesuai indikasi klinis dan pertimbangan dokter yang bertugas.
            </div>
            <div style="margin-bottom: 4px;">
                <strong>2. HAK DAN KEWAJIBAN PASIEN:</strong> Saya memahami hak saya untuk memperoleh informasi mengenai diagnosis, perkiraan biaya, rencana perawatan, serta kewajiban saya memberikan riwayat kesehatan yang jujur demi keselamatan medis saya sendiri.
            </div>
            <div style="margin-bottom: 4px;">
                <strong>3. PRIVASI DAN KERAHASIAAN MEDIS:</strong> Saya mengizinkan tenaga medis dan staf resmi klinik membuka informasi medis saya apabila diperlukan untuk kepentingan rujukan, penjaminan biaya asuransi, atau kepatuhan peraturan perundang-undangan kesehatan.
            </div>
            <div style="margin-bottom: 4px;">
                <strong>4. PELEPASAN INFORMASI MEDIS:</strong> Saya mengizinkan rekam medis elektronik saya disimpan secara aman dan diakses oleh dokter dan tim medis yang berwenang di Klinik Utama ONEW sesuai regulasi rekam medis yang berlaku.
            </div>
            <div style="margin-bottom: 4px;">
                <strong>5. PENGINGAT JADWAL & KOMUNIKASI ADMINISTRASI:</strong> Saya menyetujui Klinik Utama ONEW menghubungi saya melalui telepon, SMS, atau WhatsApp guna keperluan pengingat janji konsultasi, kontrol berkala, maupun tindak lanjut administrasi.
            </div>
            <div style="margin-bottom: 4px;">
                <strong>6. BARANG BERHARGA:</strong> Saya memahami bahwa pihak Klinik Utama ONEW tidak bertanggung jawab atas kehilangan atau kerusakan barang berharga milik pribadi yang dibawa ke area klinik.
            </div>
            <div style="margin-bottom: 4px;">
                <strong>7. PEMBIAYAAN DAN TATA TERTIB PEMBAYARAN:</strong> Saya bersedia melunasi seluruh tagihan atas konsultasi, tindakan medis penunjang, obat-obatan, dan layanan klinis yang diberikan sebelum meninggalkan area klinik.
            </div>
            <div style="margin-bottom: 4px;">
                <strong>8. PERNYATAAN PENUTUP:</strong> Saya menyatakan bahwa saya telah membaca, memahami, dan menyetujui seluruh klausul dalam Formulir Persetujuan Umum ini secara sadar dan tanpa paksaan dari pihak mana pun.
            </div>
        </div>

        <!-- Tanda Tangan General Consent (Muat di Page 2) -->
        <table class="signature-table" style="margin-top: 10px;">
            <tr>
                <td class="signature-cell" style="width: 50%;">
                    <div>Petugas Pendaftaran / Pemberi Penjelasan,</div>
                    @if($generalConsentStaffSigBase64 || $registrationStaffSigBase64)
                        <img src="{{ $generalConsentStaffSigBase64 ?: $registrationStaffSigBase64 }}" class="sig-image" alt="TTD Petugas">
                    @else
                        <div style="height: 34px; line-height: 34px; font-style: italic; color: #94a3b8;">[Tanda Tangan Petugas]</div>
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
                        <div style="height: 34px; line-height: 34px; font-style: italic; color: #94a3b8;">[Tanda Tangan Pasien]</div>
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
    <!-- HALAMAN 3: PERSETUJUAN TINDAKAN KEDOKTERAN (INFORMED CONSENT)       -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Header Informed Consent -->
        <div class="center-header">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="ONEW Clinic">
            @endif
            <h2>PERSETUJUAN TINDAKAN KEDOKTERAN</h2>
            <p style="font-weight: bold; font-size: 7.2pt; color: #334155;">INFORMED CONSENT RESMI KLINIS</p>
        </div>

        <!-- Metadata Dokter & Penerima Informasi -->
        @php
            $doctorNameDisplay = $visit->informedConsent?->doctor_name ?: ($visit->doctor_name_override ?: ($visit->doctor?->name ?? ''));
            $infoProviderDisplay = $visit->informedConsent?->information_provider ?: $doctorNameDisplay;
            $recipientNameDisplay = $visit->informedConsent?->recipient_name ?: $patient->name;
            $recipientRelationDisplay = $visit->informedConsent?->recipient_relation ?: 'Diri Sendiri';
            $recipientAgeDisplay = $visit->informedConsent?->recipient_age ?: ($patient->date_of_birth ? (string) $patient->date_of_birth->age : '');
            $recipientAddressDisplay = $visit->informedConsent?->recipient_address ?: ($patient->id_address ?: ($patient->domicile_address ?: ($patient->address ?? '')));
            
            // Resolve doctor actions: IC procedure OR treatment list
            $doctorActions = [];
            if (!empty($visit->custom_treatment_list)) {
                $doctorActions = array_filter(array_column($visit->custom_treatment_list, 'action'));
            } elseif ($visit->treatments && $visit->treatments->count() > 0) {
                $doctorActions = $visit->treatments->pluck('name')->toArray();
            }
            $doctorActionsSummary = !empty($doctorActions) ? implode(' + ', $doctorActions) : '';
            $resolvedProcedure = $visit->informedConsent?->medical_procedure ?: ($visit->informedConsent?->action_name ?: ($doctorActionsSummary ?: 'Tindakan Medis Estetika'));
        @endphp

        <table class="form-box-table" style="margin-bottom: 4px;">
            <tr>
                <td class="lbl" style="width: 25%;">Dokter Pelaksana Tindakan</td>
                <td class="val" style="width: 25%;">
                    <strong>{{ $doctorNameDisplay ?: '-' }}</strong>
                </td>
                <td class="lbl" style="width: 25%;">Penerima Informasi / Wali</td>
                <td class="val" style="width: 25%;">
                    <strong>{{ $recipientNameDisplay }}</strong>
                    <span style="font-size: 6.5pt; color: #64748b;">({{ $recipientRelationDisplay }})</span>
                </td>
            </tr>
            <tr>
                <td class="lbl">Pemberi Penjelasan Medis</td>
                <td class="val">{{ $infoProviderDisplay ?: '-' }}</td>
                <td class="lbl">Pasien Sasaran Tindakan</td>
                <td class="val">{{ $patient->name }} ({{ $patient->date_of_birth ? $patient->date_of_birth->age . ' Thn' : '-' }})</td>
            </tr>
        </table>

        <!-- Tabel 11 Poin Pemberian Informasi Medis -->
        <table class="bordered-table" style="margin-bottom: 4px;">
            <thead>
                <tr>
                    <th style="width: 4%; text-align: center;">NO</th>
                    <th style="width: 23%;">JENIS INFORMASI</th>
                    <th style="width: 65%;">ISI INFORMASI MEDIS</th>
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
                    <td><strong>{{ $resolvedProcedure }}</strong></td>
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

        <!-- Paragraf Pernyataan Persetujuan -->
        <div style="font-size: 7.2pt; text-align: justify; line-height: 1.35; background-color: #f8fafc; border: 1.5px solid #0f172a; padding: 4px 6px; border-radius: 3px; margin-bottom: 5px;">
            <div style="font-weight: 900; text-align: center; text-transform: uppercase; margin-bottom: 2px; font-size: 7.5pt;">PERSETUJUAN TINDAKAN KEDOKTERAN</div>
            Yang bertandatangan di bawah ini, saya, nama: <strong>{{ $recipientNameDisplay }}</strong>, umur: <strong>{{ $recipientAgeDisplay ?: '-' }}</strong> tahun, alamat: <strong>{{ $recipientAddressDisplay ?: '-' }}</strong>, dengan ini menyatakan persetujuan untuk dilakukannya tindakan: <strong style="text-decoration: underline;">{{ $resolvedProcedure }}</strong> terhadap {{ $recipientRelationDisplay ?: 'diri saya sendiri' }} bernama <strong>{{ $patient->name }}</strong>.<br>
            Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan. Saya juga menyadari bahwa ilmu kedokteran bukanlah ilmu pasti, melainkan sangat bergantung kepada izin Tuhan Yang Maha Esa.
        </div>

        <!-- Tanda Tangan 3 Pihak -->
        <table class="signature-table" style="margin-top: 5px;">
            <tr>
                <td class="signature-cell" style="width: 33.3%;">
                    <div>Dokter yang Menerangkan,</div>
                    @if($doctorConsentSigBase64)
                        <img src="{{ $doctorConsentSigBase64 }}" class="sig-image" alt="TTD Dokter">
                    @elseif($doctorNameDisplay)
                        <div style="height: 32px; line-height: 32px; font-style: italic; color: #0284c7; font-weight: bold;">[Signed Electronically]</div>
                    @else
                        <div style="height: 32px; line-height: 32px; color: #94a3b8; font-style: italic;">[Tanda Tangan Dokter]</div>
                    @endif
                    <div class="sig-line">
                        {{ $doctorNameDisplay ?: '' }}
                    </div>
                </td>
                <td class="signature-cell" style="width: 33.3%;">
                    <div>Yang Menyatakan Persetujuan,</div>
                    @if($procedureConsentSigBase64 || $signatureBase64)
                        <img src="{{ $procedureConsentSigBase64 ?: $signatureBase64 }}" class="sig-image" alt="TTD Pasien">
                    @else
                        <div style="height: 32px; line-height: 32px; font-style: italic; color: #94a3b8;">[Tanda Tangan Pasien]</div>
                    @endif
                    <div class="sig-line">
                        {{ $recipientNameDisplay }}
                    </div>
                </td>
                <td class="signature-cell" style="width: 33.3%;">
                    <div>Saksi Tindakan Medis,</div>
                    @if($witnessConsentSigBase64)
                        <img src="{{ $witnessConsentSigBase64 }}" class="sig-image" alt="TTD Saksi">
                    @else
                        <div style="height: 32px; line-height: 32px; font-style: italic; color: #94a3b8;">[Tanda Tangan Saksi]</div>
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
    <!-- HALAMAN 4: BHP TINDAKAN HARIAN ONEW (TANPA TANDA TANGAN)             -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Header BHP -->
        <div class="center-header">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="ONEW Clinic">
            @endif
            <h2>BHP TINDAKAN HARIAN</h2>
            <p style="font-weight: bold; font-size: 7.2pt; color: #334155;">KLINIK UTAMA ONEW</p>
        </div>

        <!-- Metadata Form BHP -->
        <table class="bordered-table" style="margin-bottom: 6px; background-color: #f8fafc;">
            <tr>
                <td style="width: 18%; font-weight: bold;">NAMA PASIEN</td>
                <td style="width: 32%; font-weight: bold; font-size: 7.8pt;">{{ $patient->name }}</td>
                <td style="width: 18%; font-weight: bold;">PERAWAT / TERAPIS</td>
                <td style="width: 32%;">{{ $visit->therapist_nurse_name ?: ($visit->assistant_name ?? ($visit->nurse?->name ?? '')) }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">DOKTER</td>
                <td>{{ $visit->doctor_name_override ?: ($visit->doctor?->name ?? '') }}</td>
                <td style="font-weight: bold;">TANGGAL</td>
                <td style="font-weight: bold; font-family: monospace;">
                    {{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->translatedFormat('d F Y') : $visit->created_at->translatedFormat('d F Y') }}
                </td>
            </tr>
        </table>

        <!-- Layout 2 Kolom Sejajar: Kolom Kiri TINDAKAN & Kolom Kanan BAHAN/BHP -->
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <!-- Kolom Kiri: Tabel TINDAKAN MEDIS -->
                <td style="width: 48%; vertical-align: top; padding-right: 6px;">
                    <div class="section-bar" style="margin-top: 0;">TINDAKAN DOKTER</div>
                    <table class="bordered-table">
                        <thead>
                            <tr>
                                <th style="width: 12%; text-align: center;">NO</th>
                                <th style="width: 88%;">NAMA TINDAKAN MEDIS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $treatmentsList = [];
                                if (!empty($visit->custom_treatment_list)) {
                                    $treatmentsList = $visit->custom_treatment_list;
                                } elseif ($visit->visitTreatments && $visit->visitTreatments->count() > 0) {
                                    foreach ($visit->visitTreatments as $vt) {
                                        $treatmentsList[] = ['action' => $vt->treatment?->name ?? 'Tindakan'];
                                    }
                                } elseif ($visit->treatments && $visit->treatments->count() > 0) {
                                    foreach ($visit->treatments as $t) {
                                        $treatmentsList[] = ['action' => $t->name];
                                    }
                                }
                            @endphp

                            @if(count($treatmentsList) > 0)
                                @foreach($treatmentsList as $tIdx => $tRow)
                                    <tr>
                                        <td style="text-align: center;">{{ $tIdx + 1 }}</td>
                                        <td><strong>{{ $tRow['action'] ?? ($tRow['name'] ?? '-') }}</strong></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td style="text-align: center;">1</td>
                                    <td style="color: #94a3b8; font-style: italic;">Konsultasi & Pemeriksaan Klinis Kulit</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </td>

                <!-- Kolom Kanan: Tabel BAHAN / BHP MEDIS -->
                <td style="width: 52%; vertical-align: top; padding-left: 6px;">
                    <div class="section-bar" style="margin-top: 0;">BAHAN HABIS PAKAI (BHP)</div>
                    <table class="bordered-table">
                        <thead>
                            <tr>
                                <th style="width: 10%; text-align: center;">NO</th>
                                <th style="width: 65%;">NAMA BARANG BHP</th>
                                <th style="width: 25%; text-align: center;">QTY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($visit->visitConsumables && $visit->visitConsumables->count() > 0)
                                @foreach($visit->visitConsumables as $cIdx => $vc)
                                    <tr>
                                        <td style="text-align: center;">{{ $cIdx + 1 }}</td>
                                        <td>{{ $vc->consumable?->name ?? '-' }}</td>
                                        <td style="text-align: center; font-weight: bold;">
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
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 5: FULL SATU HALAMAN A4 FOTO 5 SISI BEFORE (PROPORTIONAL)   -->
    <!-- URUTAN: 1. Depan, 2. Serong Kanan, 3. Serong Kiri, 4. Tampak Kanan, 5. Tampak Kiri -->
    <!-- =================================================================== -->
    <div class="page-container" style="height: 100%;">
        <!-- Header Khusus Foto Before -->
        <table class="photo-page-header">
            <tr>
                <td style="width: 25%; vertical-align: middle;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic" style="height: 28px; width: auto;">
                    @else
                        <strong style="font-size: 11pt;">ONEW</strong>
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

        <!-- Row 1: 3 Foto (1. Tampak Depan, 2. Serong Kanan, 3. Serong Kiri) -->
        <table class="photo-grid-row-1">
            <tr>
                <!-- 1. Tampak Depan (Center Focus) -->
                <td class="photo-card" style="width: 33.3%; border-color: #0f172a; background-color: #ffffff;">
                    <div class="photo-card-label" style="color: #0f172a; font-weight: 900; background-color: #f1f5f9;">1. TAMPAK DEPAN (0&deg;)</div>
                    @if($photoBeforeFrontBase64 || $photoBeforeBase64)
                        <img src="{{ $photoBeforeFrontBase64 ?: $photoBeforeBase64 }}" class="photo-img-contain" alt="Before Tampak Depan">
                    @else
                        <div class="photo-empty-box">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 2. Serong Kanan -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">2. SERONG KANAN (45&deg;)</div>
                    @if($photoBeforeRightTiltBase64)
                        <img src="{{ $photoBeforeRightTiltBase64 }}" class="photo-img-contain" alt="Before Serong Kanan">
                    @else
                        <div class="photo-empty-box">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 3. Serong Kiri -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">3. SERONG KIRI (45&deg;)</div>
                    @if($photoBeforeLeftTiltBase64)
                        <img src="{{ $photoBeforeLeftTiltBase64 }}" class="photo-img-contain" alt="Before Serong Kiri">
                    @else
                        <div class="photo-empty-box">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Row 2: 2 Foto Profil (4. Tampak Kanan 90°, 5. Tampak Kiri 90°) -->
        <table class="photo-grid-row-2">
            <tr>
                <!-- 4. Tampak Kanan (Profil 90°) -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">4. TAMPAK KANAN (PROFIL 90&deg;)</div>
                    @if($photoBeforeRightSideBase64)
                        <img src="{{ $photoBeforeRightSideBase64 }}" class="photo-img-contain" alt="Before Tampak Kanan">
                    @else
                        <div class="photo-empty-box">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 5. Tampak Kiri (Profil 90°) -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">5. TAMPAK KIRI (PROFIL 90&deg;)</div>
                    @if($photoBeforeLeftSideBase64)
                        <img src="{{ $photoBeforeLeftSideBase64 }}" class="photo-img-contain" alt="Before Tampak Kiri">
                    @else
                        <div class="photo-empty-box">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 6: FULL SATU HALAMAN A4 FOTO 5 SISI AFTER (PROPORTIONAL)    -->
    <!-- URUTAN: 1. Depan, 2. Serong Kanan, 3. Serong Kiri, 4. Tampak Kanan, 5. Tampak Kiri -->
    <!-- =================================================================== -->
    <div class="page-container" style="height: 100%;">
        <!-- Header Khusus Foto After -->
        <table class="photo-page-header">
            <tr>
                <td style="width: 25%; vertical-align: middle;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic" style="height: 28px; width: auto;">
                    @else
                        <strong style="font-size: 11pt;">ONEW</strong>
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

        <!-- Row 1: 3 Foto (1. Tampak Depan, 2. Serong Kanan, 3. Serong Kiri) -->
        <table class="photo-grid-row-1">
            <tr>
                <!-- 1. Tampak Depan (Center Focus) -->
                <td class="photo-card" style="width: 33.3%; border-color: #0f172a; background-color: #ffffff;">
                    <div class="photo-card-label" style="color: #0f172a; font-weight: 900; background-color: #f1f5f9;">1. TAMPAK DEPAN (0&deg;)</div>
                    @if($photoAfterFrontBase64 || $photoAfterBase64)
                        <img src="{{ $photoAfterFrontBase64 ?: $photoAfterBase64 }}" class="photo-img-contain" alt="After Tampak Depan">
                    @else
                        <div class="photo-empty-box">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 2. Serong Kanan -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">2. SERONG KANAN (45&deg;)</div>
                    @if($photoAfterRightTiltBase64)
                        <img src="{{ $photoAfterRightTiltBase64 }}" class="photo-img-contain" alt="After Serong Kanan">
                    @else
                        <div class="photo-empty-box">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 3. Serong Kiri -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">3. SERONG KIRI (45&deg;)</div>
                    @if($photoAfterLeftTiltBase64)
                        <img src="{{ $photoAfterLeftTiltBase64 }}" class="photo-img-contain" alt="After Serong Kiri">
                    @else
                        <div class="photo-empty-box">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Row 2: 2 Foto Profil (4. Tampak Kanan 90°, 5. Tampak Kiri 90°) -->
        <table class="photo-grid-row-2">
            <tr>
                <!-- 4. Tampak Kanan (Profil 90°) -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">4. TAMPAK KANAN (PROFIL 90&deg;)</div>
                    @if($photoAfterRightSideBase64)
                        <img src="{{ $photoAfterRightSideBase64 }}" class="photo-img-contain" alt="After Tampak Kanan">
                    @else
                        <div class="photo-empty-box">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 5. Tampak Kiri (Profil 90°) -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">5. TAMPAK KIRI (PROFIL 90&deg;)</div>
                    @if($photoAfterLeftSideBase64)
                        <img src="{{ $photoAfterLeftSideBase64 }}" class="photo-img-contain" alt="After Tampak Kiri">
                    @else
                        <div class="photo-empty-box">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
