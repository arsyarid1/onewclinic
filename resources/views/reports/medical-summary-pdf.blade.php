<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Formulir Medis Pasien - {{ $visit->manual_patient_code ?: ($patient->patient_code ?? 'PASIEN') }}</title>
    <style>
        @page {
            margin: 10mm 14mm 10mm 14mm;
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
            line-height: 1.28;
            font-size: 8pt;
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
            border-bottom: 2px solid #0f172a;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        .kop-logo {
            width: 45%;
            vertical-align: middle;
            text-align: left;
        }

        .kop-logo img {
            height: 42px;
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

        /* Title Box for Registration Form */
        .title-box-table {
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 6px;
        }

        .title-box-left {
            width: 55%;
            vertical-align: middle;
        }

        .title-box-left h1 {
            margin: 0;
            font-size: 11pt;
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
            width: 45%;
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
            width: 42%;
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

        /* Form Tables */
        .form-grid-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            margin-bottom: 5px;
        }

        .form-grid-table td {
            padding: 3px 5px;
            vertical-align: top;
            border-bottom: 1px dotted #cbd5e1;
        }

        .field-label {
            color: #475569;
            font-weight: 600;
            width: 28%;
        }

        .field-val {
            color: #0f172a;
            font-weight: normal;
            width: 72%;
        }

        /* Bordered Box Table */
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
            padding: 3px 5px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }

        /* Signatures */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .signature-cell {
            text-align: center;
            vertical-align: top;
            font-size: 7.5pt;
            padding: 0 8px;
        }

        .sig-image {
            height: 52px;
            max-width: 150px;
            display: block;
            margin: 3px auto;
        }

        .sig-line {
            border-top: 1px solid #475569;
            margin-top: 3px;
            padding-top: 3px;
            font-weight: bold;
        }

        /* Center Header for General Consent / Informed Consent */
        .center-header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        .center-header img {
            height: 38px;
            width: auto;
            margin: 0 auto 2px auto;
            display: block;
        }

        .center-header h1 {
            margin: 2px 0 0 0;
            font-size: 11pt;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .center-header h2 {
            margin: 0;
            font-size: 10.5pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
        }

        .center-header p {
            margin: 1px 0 0 0;
            font-size: 7pt;
            color: #475569;
        }

        /* Checkbox Box Styling */
        .chk-box {
            display: inline-block;
            font-family: monospace;
            font-weight: bold;
            color: #0f172a;
        }

        /* Photo 5-Sides Grid Layout */
        .photo-page-header {
            width: 100%;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }

        .photo-page-title {
            font-size: 10pt;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .photo-meta-info {
            font-size: 7pt;
            color: #475569;
            margin-top: 1px;
        }

        .photo-grid-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
            table-layout: fixed;
        }

        .photo-card {
            border: 1.5px solid #cbd5e1;
            border-radius: 5px;
            background-color: #f8fafc;
            text-align: center;
            padding: 4px;
            vertical-align: top;
        }

        .photo-card-label {
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            padding: 2px 4px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 4px;
            letter-spacing: 0.3px;
        }

        .photo-img-box {
            height: 195px;
            text-align: center;
        }

        .photo-frame-img {
            max-height: 195px;
            max-width: 95%;
            height: auto;
            display: inline-block;
            margin: 0 auto;
            border-radius: 3px;
        }

        .photo-empty-box {
            height: 195px;
            line-height: 195px;
            text-align: center;
            background-color: #ffffff;
            border: 1px dashed #cbd5e1;
            border-radius: 3px;
            color: #94a3b8;
            font-style: italic;
            font-size: 7.5pt;
        }

        .footer-note {
            font-size: 6.5pt;
            color: #64748b;
            text-align: center;
            margin-top: 6px;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    <!-- =================================================================== -->
    <!-- HALAMAN 1: FORMULIR PENDAFTARAN PASIEN (BAGIAN A, B, C)             -->
    <!-- Persis seperti FORM/page_1.jpg di formatform.pdf                   -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Kop Surat -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic">
                    @else
                        <span style="font-size: 15pt; font-weight: 900; letter-spacing: 1px;">ONEW</span>
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
                    <h1>FORMULIR PENDAFTARAN PASIEN</h1>
                    <p>ONEW Klinik Utama</p>
                </td>
                <td class="title-box-right">
                    <div class="rm-meta-card">
                        <table>
                            <tr>
                                <td class="rm-label">No. Rekam Medis</td>
                                <td style="width: 4%;">:</td>
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

        <!-- Bagian A: IDENTITAS PASIEN -->
        <div class="section-bar">A. IDENTITAS PASIEN</div>
        <table class="form-grid-table">
            <tr>
                <td class="field-label">Nama Lengkap sesuai Identitas</td>
                <td class="field-val"><strong style="font-size: 8.5pt;">{{ $patient->name }}</strong></td>
            </tr>
            <tr>
                <td class="field-label">NIK / No. Paspor</td>
                <td class="field-val" style="font-family: monospace; font-weight: bold;">{{ $patient->nik ?: ($patient->nik_passport ?: '-') }}</td>
            </tr>
            <tr>
                <td class="field-label">Tempat, Tanggal Lahir</td>
                <td class="field-val">
                    {{ $patient->birth_place ?: '-' }},
                    {{ $patient->date_of_birth ? $patient->date_of_birth->format('d/m/Y') : '-' }}
                    ({{ $patient->date_of_birth ? $patient->date_of_birth->age . ' Tahun' : '-' }})
                </td>
            </tr>
            <tr>
                <td class="field-label">Jenis Kelamin</td>
                <td class="field-val">
                    <span class="chk-box">[{{ $patient->gender === 'male' ? 'X' : ' ' }}]</span> Laki-laki
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $patient->gender === 'female' ? 'X' : ' ' }}]</span> Perempuan
                </td>
            </tr>
            <tr>
                <td class="field-label">Status Perkawinan</td>
                <td class="field-val">
                    <span class="chk-box">[{{ ($patient->marital_status === 'Belum Menikah' || !$patient->marital_status) ? 'X' : ' ' }}]</span> Belum Menikah
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $patient->marital_status === 'Menikah' ? 'X' : ' ' }}]</span> Menikah
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $patient->marital_status === 'Cerai' ? 'X' : ' ' }}]</span> Cerai
                </td>
            </tr>
            <tr>
                <td class="field-label">Alamat sesuai Identitas</td>
                <td class="field-val">{{ $patient->id_address ?: ($patient->address ?: '-') }}</td>
            </tr>
            <tr>
                <td class="field-label">Alamat Domisili</td>
                <td class="field-val">
                    <span class="chk-box">[{{ $patient->is_domicile_same ? 'X' : ' ' }}]</span> Sama dengan identitas
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ !$patient->is_domicile_same ? 'X' : ' ' }}]</span> Lainnya: {{ !$patient->is_domicile_same ? $patient->domicile_address : '-' }}
                </td>
            </tr>
            <tr>
                <td class="field-label">No. HP / WhatsApp</td>
                <td class="field-val" style="font-weight: bold; font-family: monospace;">{{ $patient->phone_number ?: '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Email</td>
                <td class="field-val">{{ $patient->email ?: '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Pekerjaan</td>
                <td class="field-val">{{ $patient->occupation ?: '-' }}</td>
            </tr>
        </table>

        <!-- Bagian B: PENANGGUNG JAWAB / KONTAK DARURAT -->
        <div class="section-bar">B. PENANGGUNG JAWAB / KONTAK DARURAT</div>
        <table class="form-grid-table">
            <tr>
                <td class="field-label">Nama</td>
                <td class="field-val"><strong>{{ $patient->emergency_contact_name ?: '-' }}</strong></td>
            </tr>
            <tr>
                <td class="field-label">Hubungan dengan Pasien</td>
                <td class="field-val">{{ $patient->emergency_contact_relation ?: '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">No. HP / WhatsApp</td>
                <td class="field-val" style="font-family: monospace;">{{ $patient->emergency_contact_phone ?: '-' }}</td>
            </tr>
        </table>

        <!-- Bagian C: DATA PENJAMIN / PEMBAYARAN -->
        <div class="section-bar">C. DATA PENJAMIN / PEMBAYARAN</div>
        <table class="form-grid-table">
            <tr>
                <td class="field-label">Jenis Pembayaran</td>
                <td class="field-val">
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
                <td class="field-label">Nama Asuransi / Perusahaan</td>
                <td class="field-val">{{ $patient->insurance_company_name ?: '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">No. Peserta / No. Polis</td>
                <td class="field-val" style="font-family: monospace;">{{ $patient->insurance_card_number ?: '-' }}</td>
            </tr>
        </table>

        <div class="footer-note">
            Formulir Pendaftaran Pasien | Dokumen Administrasi Klinik &bull; Halaman 1
        </div>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 2: FORMULIR PENDAFTARAN PASIEN (BAGIAN D, E, F & TTD)       -->
    <!-- Persis seperti FORM/page_2.jpg di formatform.pdf                   -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Kop Surat Hal 2 -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic">
                    @else
                        <span style="font-size: 15pt; font-weight: 900; letter-spacing: 1px;">ONEW</span>
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

        <!-- Bagian D: INFORMASI PELAYANAN -->
        <div class="section-bar">D. INFORMASI PELAYANAN</div>
        <table class="form-grid-table">
            <tr>
                <td class="field-label">Status Pasien</td>
                <td class="field-val">
                    <span class="chk-box">[{{ $visit->patient_service_status === 'Pasien Baru' ? 'X' : ' ' }}]</span> Pasien Baru
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $visit->patient_service_status === 'Pasien Lama' ? 'X' : ' ' }}]</span> Pasien Lama
                </td>
            </tr>
            <tr>
                <td class="field-label">Tujuan Kunjungan</td>
                <td class="field-val">
                    <span class="chk-box">[{{ $visit->visit_purpose === 'Rawat Jalan / Dokter Umum' ? 'X' : ' ' }}]</span> Rawat Jalan / Dokter Umum
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ ($visit->visit_purpose === 'Estetika' || !$visit->visit_purpose) ? 'X' : ' ' }}]</span> Estetika
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $visit->visit_purpose === 'Gigi' ? 'X' : ' ' }}]</span> Gigi
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $visit->visit_purpose === 'Spesialis' ? 'X' : ' ' }}]</span> Spesialis
                </td>
            </tr>
            <tr>
                <td class="field-label">Dokter / Poli Tujuan</td>
                <td class="field-val"><strong>{{ $visit->destination_doctor_poli ?: ($visit->doctor?->name ?? 'Poli Estetika Medis') }}</strong></td>
            </tr>
            <tr>
                <td class="field-label">Keluhan Singkat / Keperluan</td>
                <td class="field-val">{{ $visit->chief_complaint ?: 'Konsultasi dan perawatan estetika klinis berkala.' }}</td>
            </tr>
        </table>

        <!-- Bagian E: INFORMASI TAMBAHAN -->
        <div class="section-bar">E. INFORMASI TAMBAHAN</div>
        <table class="form-grid-table">
            <tr>
                <td class="field-label">Mengetahui Klinik dari</td>
                <td class="field-val">
                    <span class="chk-box">[{{ $patient->referral_source === 'Keluarga / Teman' ? 'X' : ' ' }}]</span> Keluarga / Teman
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ ($patient->referral_source === 'Instagram / TikTok' || !$patient->referral_source) ? 'X' : ' ' }}]</span> Instagram / TikTok
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $patient->referral_source === 'Google / Internet' ? 'X' : ' ' }}]</span> Google / Internet
                    <br>
                    <span class="chk-box">[{{ $patient->referral_source === 'Iklan' ? 'X' : ' ' }}]</span> Iklan
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ $patient->referral_source === 'Rujukan Dokter' ? 'X' : ' ' }}]</span> Rujukan Dokter
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ ($patient->referral_source && !in_array($patient->referral_source, ['Keluarga / Teman', 'Instagram / TikTok', 'Google / Internet', 'Iklan', 'Rujukan Dokter'])) ? 'X' : ' ' }}]</span> Lainnya: {{ $patient->referral_source_other ?: '' }}
                </td>
            </tr>
        </table>

        <!-- Bagian F: PERNYATAAN DAN PERSETUJUAN PASIEN -->
        <div class="section-bar">F. PERNYATAAN DAN PERSETUJUAN PASIEN</div>
        <div style="font-size: 7.2pt; text-align: justify; line-height: 1.35; padding: 4px 6px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 3px; margin-bottom: 8px;">
            <p style="margin: 0 0 3px 0;">1. Saya menyatakan bahwa seluruh data yang saya berikan dalam formulir ini adalah benar dan dapat dipertanggungjawabkan.</p>
            <p style="margin: 0 0 3px 0;">2. Saya bersedia memberikan informasi yang diperlukan untuk proses pendaftaran, pelayanan kesehatan, administrasi, penagihan, dan pencatatan rekam medis sesuai ketentuan yang berlaku.</p>
            <p style="margin: 0 0 3px 0;">3. Saya memahami bahwa informasi medis yang lebih rinci akan diperoleh melalui anamnesis, pemeriksaan, dan/atau formulir persetujuan tindakan medis sesuai jenis pelayanan yang saya terima.</p>
            <p style="margin: 0 0 4px 0;">4. Saya bersedia mematuhi ketentuan pelayanan dan tata tertib klinik yang berlaku.</p>
            <div style="margin-top: 5px; font-weight: bold; border-top: 1px dashed #cbd5e1; padding-top: 4px;">
                Persetujuan menerima informasi/komunikasi administratif melalui telepon atau WhatsApp:
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="chk-box">[{{ $visit->whatsapp_comm_consent ? 'X' : ' ' }}]</span> Saya SETUJU
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="chk-box">[{{ !$visit->whatsapp_comm_consent ? 'X' : ' ' }}]</span> Saya TIDAK SETUJU
            </div>
        </div>

        <!-- Tabel Tanda Tangan Formulir Pendaftaran Hal 2 -->
        <table class="bordered-table" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th style="width: 50%; text-align: center;">Pasien / Penanggung Jawab</th>
                    <th style="width: 50%; text-align: center;">Petugas Pendaftaran</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; padding: 6px; vertical-align: top;">
                        <div style="font-size: 7pt; color: #475569; margin-bottom: 4px;">
                            Tanggal: {{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->format('d/m/Y') : $visit->created_at->format('d/m/Y') }}
                        </div>
                        @if($registrationPatientSigBase64 || $signatureBase64)
                            <img src="{{ $registrationPatientSigBase64 ?: $signatureBase64 }}" class="sig-image" alt="TTD Pasien">
                        @else
                            <div style="height: 52px; line-height: 52px; font-style: italic; color: #94a3b8;">[Tanda tangan Pasien]</div>
                        @endif
                        <div class="sig-line">
                            Nama: {{ $visit->registration_patient_signer_name ?: $patient->name }}
                        </div>
                    </td>
                    <td style="text-align: center; padding: 6px; vertical-align: top;">
                        <div style="font-size: 7pt; color: #475569; margin-bottom: 4px;">
                            Tanggal: {{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->format('d/m/Y') : $visit->created_at->format('d/m/Y') }}
                        </div>
                        @if($registrationStaffSigBase64)
                            <img src="{{ $registrationStaffSigBase64 }}" class="sig-image" alt="TTD Petugas">
                        @else
                            <div style="height: 52px; line-height: 52px; font-style: italic; color: #94a3b8;">[Tanda tangan / Paraf Petugas]</div>
                        @endif
                        <div class="sig-line">
                            Nama: {{ $visit->registration_staff_signer_name ?: 'Petugas Pendaftaran ONEW' }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 6.5pt; color: #64748b; font-style: italic; margin-top: 4px; text-align: center;">
            Catatan: Formulir ini merupakan formulir pendaftaran pasien dan tidak menggantikan informed consent atau persetujuan tindakan medis yang diperlukan untuk tindakan tertentu.
        </p>

        <div class="footer-note">
            Formulir Pendaftaran Pasien | Dokumen Administrasi Klinik &bull; Halaman 2
        </div>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 3: GENERAL CONSENT (BAGIAN 1, 2, 3)                         -->
    <!-- Persis seperti FORM/page_3.jpg di formatform.pdf                   -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Kop Surat General Consent -->
        <div class="center-header">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="ONEW Clinic">
            @endif
            <p style="font-weight: bold; text-transform: uppercase; font-size: 7.5pt; color: #1e293b; margin-top: 1px;">
                Klinik Utama ONEW &bull; PT Permata Medikal Jaya
            </p>
            <p>Jl. Laksana I no 6, Kebayoran Baru, Jakarta Selatan 12180 &bull; Telp: +62 8119 6977 886 / +62 21382 506 94</p>
            <h1 style="margin-top: 4px;">GENERAL CONSENT</h1>
            <p style="font-size: 8pt; font-weight: bold; color: #334155;">FORMULIR PERSETUJUAN UMUM PASIEN</p>
        </div>

        <!-- Identitas Pasien Grid (Sesuai Scan Hal 3) -->
        <table class="bordered-table" style="margin-bottom: 6px; background-color: #f8fafc;">
            <tr>
                <td style="width: 18%; font-weight: bold;">Nama lengkap</td>
                <td style="width: 32%;"><strong>{{ $patient->name }}</strong></td>
                <td style="width: 20%; font-weight: bold;">No. rekam medis</td>
                <td style="width: 30%; font-family: monospace; font-weight: bold;">{{ $visit->manual_patient_code ?: $patient->patient_code }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">NIK / Identitas</td>
                <td style="font-family: monospace;">{{ $patient->nik ?: ($patient->nik_passport ?: '-') }}</td>
                <td style="font-weight: bold;">Tanggal lahir</td>
                <td>{{ $patient->date_of_birth ? $patient->date_of_birth->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">No. HP / WhatsApp</td>
                <td style="font-family: monospace;">{{ $patient->phone_number ?: '-' }}</td>
                <td style="font-weight: bold;">Jenis kelamin</td>
                <td><span class="chk-box">[{{ $patient->gender === 'male' ? 'X' : ' ' }}]</span> Laki-laki &nbsp; <span class="chk-box">[{{ $patient->gender === 'female' ? 'X' : ' ' }}]</span> Perempuan</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Alamat</td>
                <td>{{ $patient->id_address ?: ($patient->address ?: '-') }}</td>
                <td style="font-weight: bold;">Email (opsional)</td>
                <td>{{ $patient->email ?: '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Kontak darurat (nama)</td>
                <td>{{ $patient->emergency_contact_name ?: '-' }}</td>
                <td style="font-weight: bold;">Hubungan & no. telepon</td>
                <td>{{ $patient->emergency_contact_relation ?: '-' }} &bull; {{ $patient->emergency_contact_phone ?: '-' }}</td>
            </tr>
        </table>

        <!-- Klausul 1, 2, 3 Sesuai Scan Fisik Hal 3 -->
        <div style="font-size: 7.2pt; text-align: justify; line-height: 1.35; color: #1e293b;">
            <div style="margin-bottom: 6px;">
                <strong>1. PERSETUJUAN PENDAFTARAN DAN PELAYANAN UMUM</strong><br>
                Saya bersedia didaftarkan sebagai pasien dan menerima penjelasan mengenai alur layanan, ketentuan administrasi, serta pelayanan awal yang sesuai kebutuhan, seperti wawancara riwayat kesehatan, pemeriksaan tanda vital, pemeriksaan fisik dasar, konsultasi, dan observasi noninvasif sesuai penilaian tenaga medis.<br>
                Saya memahami bahwa setiap pemeriksaan, pemberian obat, dan rencana terapi akan dijelaskan sesuai kebutuhan. Tindakan yang memerlukan persetujuan khusus, termasuk tindakan invasif atau berisiko, injeksi, penggunaan laser, anestesi, dan pembedahan, tidak otomatis disetujui melalui formulir ini dan memerlukan <em>informed consent</em> tersendiri sesuai ketentuan.
            </div>

            <div style="margin-bottom: 6px;">
                <strong>2. HAK DAN KEWAJIBAN PASIEN</strong><br>
                Saya telah memperoleh kesempatan untuk mengetahui hak saya, termasuk mendapatkan informasi yang dapat dipahami tentang kondisi dan rencana pelayanan, menanyakan alternatif dan biaya, memperoleh privasi serta kerahasiaan rekam medis, menyampaikan keluhan, dan menerima atau menolak tindakan sesuai ketentuan yang berlaku.<br>
                Saya berkewajiban memberikan data identitas dan riwayat kesehatan secara benar, menginformasikan alergi/obat yang digunakan, mengikuti aturan keselamatan klinik, menghormati petugas dan pasien lain, serta memenuhi kewajiban pembayaran untuk layanan yang telah disetujui.
            </div>

            <div style="margin-bottom: 6px;">
                <strong>3. REKAM MEDIS DAN PELINDUNGAN DATA PRIBADI</strong><br>
                Saya memahami bahwa klinik mengumpulkan dan mengolah data identitas, kontak, riwayat kesehatan, hasil pemeriksaan, diagnosis, terapi, dan informasi pembayaran untuk pendaftaran, pelayanan, pencatatan rekam medis elektronik, administrasi, penagihan, serta pemenuhan kewajiban hukum. Akses dibatasi kepada pihak berwenang sesuai kebutuhan dan kerahasiaan tetap dijaga.<br>
                Data dapat disampaikan kepada tenaga kesehatan atau fasilitas penerima rujukan dan sistem pelaporan/integrasi kesehatan yang diwajibkan peraturan, sebatas diperlukan. Pengungkapan kepada pihak lain, termasuk penjamin/asuransi, dilaksanakan sesuai dasar hukum atau persetujuan yang berlaku. Foto atau rekaman untuk publikasi/promosi tidak termasuk dalam persetujuan ini.
            </div>
        </div>

        <div class="footer-note">
            General Consent / Formulir Persetujuan Umum Pasien &bull; Halaman 1
        </div>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 4: GENERAL CONSENT / LANJUTAN (BAGIAN 4, 5, 6, 7, 8 & TTD)  -->
    <!-- Persis seperti FORM/page_4.jpg di formatform.pdf                   -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Kop Surat General Consent Hal 2 -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic">
                    @else
                        <span style="font-size: 15pt; font-weight: 900; letter-spacing: 1px;">ONEW</span>
                    @endif
                </td>
                <td class="kop-clinic-details">
                    <div class="kop-clinic-title">Klinik Utama ONEW &bull; PT Permata Medikal Jaya</div>
                    <div>Jl. Laksana I no 6, Kebayoran Baru, Jaksel 12180</div>
                    <div>website: onewclinic.com &bull; email: onewclinic@gmail.com</div>
                    <div>mobile: +62 8119 6977 886 &bull; phone: +62 21382 506 94</div>
                </td>
            </tr>
        </table>

        <div style="font-size: 7.2pt; text-align: justify; line-height: 1.35; color: #1e293b;">
            <div style="margin-bottom: 5px;">
                <strong>4. BIAYA DAN ADMINISTRASI</strong><br>
                Saya memahami bahwa biaya konsultasi, pemeriksaan penunjang, obat, dan tindakan tambahan dapat berbeda. Klinik akan menyampaikan informasi biaya sebelum layanan diberikan, sepanjang keadaan memungkinkan. Saya berhak mengajukan pertanyaan dan menyetujui atau menolak layanan tambahan sesuai ketentuan medis dan hukum.
            </div>

            <div style="margin-bottom: 5px;">
                <strong>5. KEADAAN DARURAT DAN RUJUKAN</strong><br>
                Apabila terjadi kondisi darurat yang mengancam nyawa atau keselamatan, saya memahami bahwa tenaga medis dapat memberikan pertolongan segera sesuai kewenangan dan ketentuan yang berlaku. Bila pelayanan yang dibutuhkan tidak tersedia, klinik dapat menyarankan atau mengatur rujukan ke fasilitas yang sesuai, dengan pemberitahuan kepada saya/keluarga sejauh memungkinkan.
            </div>

            <div style="margin-bottom: 5px;">
                <strong>6. KOMUNIKASI DAN DOKUMENTASI MEDIS</strong><br>
                Klinik dapat menghubungi nomor kontak saya untuk keperluan pelayanan dan administrasi. Penggunaan WhatsApp dan pengiriman promosi mengikuti pilihan saya di bawah. Informasi medis sensitif hanya disampaikan dengan memperhatikan verifikasi identitas dan kerahasiaan. Dokumentasi foto/video klinis, jika diperlukan, akan dijelaskan dan dimintakan persetujuan sesuai tujuan penggunaannya; penggunaan untuk promosi memerlukan persetujuan terpisah.
            </div>
        </div>

        <!-- 7. PILIHAN PASIEN (Checklist Sesuai Scan Hal 4) -->
        <div class="section-bar">7. PILIHAN PASIEN (DIISI SESUAI KEBUTUHAN)</div>
        <table class="bordered-table" style="background-color: #f8fafc;">
            <tr>
                <td style="width: 70%;">Pengingat jadwal dan komunikasi administrasi melalui WhatsApp:</td>
                <td style="width: 30%;">
                    <span class="chk-box">[{{ $visit->general_consent_whatsapp_reminder ? 'X' : ' ' }}]</span> Ya
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ !$visit->general_consent_whatsapp_reminder ? 'X' : ' ' }}]</span> Tidak
                </td>
            </tr>
            <tr>
                <td>Informasi promosi layanan klinik (opsional; tidak memengaruhi pelayanan):</td>
                <td>
                    <span class="chk-box">[{{ $visit->general_consent_promo_info ? 'X' : ' ' }}]</span> Ya
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ !$visit->general_consent_promo_info ? 'X' : ' ' }}]</span> Tidak
                </td>
            </tr>
            <tr>
                <td>Pengungkapan data yang diperlukan untuk verifikasi manfaat/klaim kepada penjamin:</td>
                <td>
                    <span class="chk-box">[{{ $visit->general_consent_insurance_disclosure === 'Ya' ? 'X' : ' ' }}]</span> Ya
                    &nbsp;&nbsp;
                    <span class="chk-box">[{{ $visit->general_consent_insurance_disclosure === 'Tidak' ? 'X' : ' ' }}]</span> Tidak
                    &nbsp;&nbsp;
                    <span class="chk-box">[{{ ($visit->general_consent_insurance_disclosure === 'N/A' || !$visit->general_consent_insurance_disclosure) ? 'X' : ' ' }}]</span> N/A
                </td>
            </tr>
            <tr>
                <td>Izin pemberian informasi kesehatan kepada orang yang saya tunjuk:</td>
                <td>
                    <span class="chk-box">[{{ $visit->general_consent_third_party_info ? 'X' : ' ' }}]</span> Ya
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="chk-box">[{{ !$visit->general_consent_third_party_info ? 'X' : ' ' }}]</span> Tidak
                </td>
            </tr>
            @if($visit->general_consent_third_party_info)
                <tr>
                    <td colspan="2" style="font-size: 7pt; background-color: #ffffff;">
                        Jika Ya, nama / hubungan: <strong>{{ $visit->general_consent_third_party_name ?: '-' }}</strong> &bull; No. telepon: <strong>{{ $visit->general_consent_third_party_phone ?: '-' }}</strong>
                    </td>
                </tr>
            @endif
        </table>

        <!-- 8. PERNYATAAN DAN PERSETUJUAN -->
        <div class="section-bar">8. PERNYATAAN DAN PERSETUJUAN</div>
        <p style="font-size: 7.2pt; text-align: justify; line-height: 1.35; margin: 3px 0 6px 0;">
            Saya menyatakan telah membaca atau mendapatkan penjelasan formulir ini dengan bahasa yang saya pahami, memiliki kesempatan bertanya, dan memahami bahwa persetujuan umum ini tidak menggantikan persetujuan tindakan kedokteran khusus. Saya memberikan persetujuan untuk pendaftaran dan pelayanan umum sebagaimana dijelaskan di atas. Saya dapat menyampaikan keberatan atau menarik persetujuan yang didasarkan pada persetujuan, dengan memperhatikan kewajiban penyimpanan rekam medis dan dasar hukum lain yang berlaku.
        </p>

        <div style="font-size: 7.5pt; margin-bottom: 4px;">
            Tempat: <strong>Jakarta</strong> &nbsp;&bull;&nbsp;
            Tanggal: <strong>{{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->format('d/m/Y') : $visit->created_at->format('d/m/Y') }}</strong> &nbsp;&bull;&nbsp;
            Pukul: <strong>{{ $visit->created_at->format('H:i') }} WIB</strong>
        </div>

        <!-- Tabel Tanda Tangan General Consent Hal 4 -->
        <table class="bordered-table">
            <thead>
                <tr>
                    <th style="width: 50%; text-align: center;">PETUGAS YANG MEMBERIKAN PENJELASAN</th>
                    <th style="width: 50%; text-align: center;">PASIEN / ORANG TUA / WALI / PERWAKILAN SAH</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; padding: 6px; vertical-align: top;">
                        <div style="font-size: 7pt; color: #475569; margin-bottom: 2px;">
                            Jabatan: {{ $visit->general_consent_staff_title ?: 'Petugas Pendaftaran / Perawat' }}
                        </div>
                        @if($generalConsentStaffSigBase64 || $registrationStaffSigBase64)
                            <img src="{{ $generalConsentStaffSigBase64 ?: $registrationStaffSigBase64 }}" class="sig-image" alt="TTD Petugas">
                        @else
                            <div style="height: 52px; line-height: 52px; font-style: italic; color: #94a3b8;">[Tanda tangan Petugas]</div>
                        @endif
                        <div class="sig-line">
                            {{ $visit->general_consent_staff_name ?: ($visit->registration_staff_signer_name ?: 'Petugas Pendaftaran ONEW') }}
                        </div>
                    </td>
                    <td style="text-align: center; padding: 6px; vertical-align: top;">
                        <div style="font-size: 7pt; color: #475569; margin-bottom: 2px;">
                            Hubungan dengan pasien: {{ $visit->general_consent_patient_relation ?: 'Diri Sendiri' }}
                        </div>
                        @if($signatureBase64 || $registrationPatientSigBase64)
                            <img src="{{ $signatureBase64 ?: $registrationPatientSigBase64 }}" class="sig-image" alt="TTD Pasien">
                        @else
                            <div style="height: 52px; line-height: 52px; font-style: italic; color: #94a3b8;">[Tanda tangan Pasien]</div>
                        @endif
                        <div class="sig-line">
                            {{ $visit->general_consent_patient_signer_name ?: ($visit->registration_patient_signer_name ?: $patient->name) }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer-note">
            General Consent / Formulir Persetujuan Umum Pasien &bull; Halaman 2
        </div>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 5: PERSETUJUAN TINDAKAN KEDOKTERAN (INFORMED CONSENT)       -->
    <!-- Persis seperti FORM/page_5.jpg di formatform.pdf                   -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Header Informed Consent -->
        <table class="kop-table" style="margin-bottom: 4px;">
            <tr>
                <td style="width: 25%; vertical-align: middle;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic" style="height: 36px; width: auto;">
                    @else
                        <span style="font-size: 14pt; font-weight: 900;">ONEW</span>
                    @endif
                </td>
                <td style="width: 75%; text-align: right; vertical-align: middle;">
                    <div style="font-size: 11pt; font-weight: 900; color: #0f172a; text-transform: uppercase;">PERSETUJUAN TINDAKAN KEDOKTERAN</div>
                    <div style="font-size: 7pt; color: #475569;">PEMBERIAN INFORMASI &bull; ONEW Klinik Utama</div>
                </td>
            </tr>
        </table>

        <!-- Metadata Dokter & Penerima Informasi -->
        <table class="bordered-table" style="margin-bottom: 4px; background-color: #f8fafc;">
            <tr>
                <td style="width: 28%; font-weight: bold;">Dokter Pelaksana Tindakan</td>
                <td style="width: 72%;">: <strong>{{ $visit->informedConsent?->doctor_name ?: ($visit->doctor_name_override ?: ($visit->doctor?->name ?? 'dr. Alana Wijaya, Sp.D.V.E')) }}</strong></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Pemberi Informasi</td>
                <td>: {{ $visit->informedConsent?->information_provider ?: ($visit->informedConsent?->doctor_name ?: ($visit->doctor?->name ?? 'dr. Alana Wijaya, Sp.D.V.E')) }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Penerima Informasi / Persetujuan*</td>
                <td>: <strong>{{ $visit->informedConsent?->recipient_name ?: $patient->name }}</strong> ({{ $visit->informedConsent?->recipient_relation ?: 'Pasien Sendiri' }})</td>
            </tr>
        </table>

        <!-- Tabel 11 Poin Pemberian Informasi Medis (Sesuai Scan Fisik Hal 5) -->
        <table class="bordered-table" style="margin-bottom: 4px;">
            <thead>
                <tr>
                    <th style="width: 4%; text-align: center;">No</th>
                    <th style="width: 22%;">Jenis Informasi</th>
                    <th style="width: 66%;">Isi Informasi</th>
                    <th style="width: 8%; text-align: center;">Tanda (v)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td><strong>Diagnosis (WD & DD)</strong></td>
                    <td>{{ $visit->informedConsent?->diagnosis_wd_dd ?: ($visit->diagnosis_wd ?: 'Pemeriksaan Estetika Klinis Kulit & Wajah') }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">2</td>
                    <td><strong>Dasar Diagnosis</strong></td>
                    <td>{{ $visit->informedConsent?->diagnosis_basis ?: 'Anamnesis dan Pemeriksaan Fisik Klinis Kulit' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">3</td>
                    <td><strong>Tindakan Kedokteran</strong></td>
                    <td><strong>{{ $visit->informedConsent?->medical_procedure ?: ($visit->informedConsent?->action_name ?: 'Messo + Botox + Laser CO2 + Multivitamin Infuse') }}</strong></td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">4</td>
                    <td><strong>Indikasi Tindakan</strong></td>
                    <td>{{ $visit->informedConsent?->indication ?: 'Peremajaan jaringan kulit, stimulasi kolagen, dan perbaikan kontur' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">5</td>
                    <td><strong>Tata Cara</strong></td>
                    <td>{{ $visit->informedConsent?->procedure_steps ?: 'Asepsis, anestesi topikal bila diperlukan, pengerjaan prosedur aseptik sesuai protokol' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">6</td>
                    <td><strong>Tujuan</strong></td>
                    <td>{{ $visit->informedConsent?->procedure_goal ?: 'Meningkatkan elastisitas, kecerahan, dan tekstur kulit yang optimal' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">7</td>
                    <td><strong>Risiko</strong></td>
                    <td>{{ $visit->informedConsent?->risks ?: 'Eritema sementara, rasa hangat, atau pembengkakan ringan pasca tindakan' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">8</td>
                    <td><strong>Komplikasi</strong></td>
                    <td>{{ $visit->informedConsent?->complications ?: 'Hematoma ringan, reaksi hipersensitivitas individual' }}</td>
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
                    <td>{{ $visit->informedConsent?->alternatives_risks ?: 'Perawatan topikal harian mandiri atau observasi berkala' }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
                <tr>
                    <td style="text-align: center;">11</td>
                    <td><strong>Lain-lain</strong></td>
                    <td>{{ $visit->informedConsent?->other_notes ?: ($visit->doctor_notes ?: 'Hindari paparan sinar matahari langsung dan gunakan tabir surya teratur.') }}</td>
                    <td style="text-align: center; font-weight: bold;">[ &radic; ]</td>
                </tr>
            </tbody>
        </table>

        <!-- 2 Tanda Tangan di Bawah Tabel Informasi (Sesuai Scan Hal 5) -->
        <table class="bordered-table" style="margin-bottom: 4px;">
            <tr>
                <td style="width: 50%; font-size: 6.8pt; vertical-align: top; padding: 4px;">
                    Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas secara benar dan jelas dan memberikan kesempatan untuk bertanya dan/atau berdiskusi:
                    <div style="text-align: center; margin-top: 3px;">
                        @if($doctorConsentSigBase64)
                            <img src="{{ $doctorConsentSigBase64 }}" style="height: 38px; max-width: 130px; display: block; margin: 0 auto;" alt="TTD Dokter">
                        @else
                            <div style="height: 38px; line-height: 38px; font-style: italic; color: #0284c7; font-weight: bold;">[Signed Electronically]</div>
                        @endif
                        <div style="font-weight: bold; font-size: 7pt; border-top: 1px solid #475569; padding-top: 1px; margin-top: 2px;">
                            {{ $visit->informedConsent?->doctor_name ?: ($visit->doctor_name_override ?: ($visit->doctor?->name ?? 'Dokter Pelaksana')) }}
                        </div>
                    </div>
                </td>
                <td style="width: 50%; font-size: 6.8pt; vertical-align: top; padding: 4px;">
                    Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana di atas yang saya beri tanda / paraf di kolom kanannya, dan telah memahaminya:
                    <div style="text-align: center; margin-top: 3px;">
                        @if($procedureConsentSigBase64 || $signatureBase64)
                            <img src="{{ $procedureConsentSigBase64 ?: $signatureBase64 }}" style="height: 38px; max-width: 130px; display: block; margin: 0 auto;" alt="TTD Pasien">
                        @else
                            <div style="height: 38px; line-height: 38px; font-style: italic; color: #94a3b8;">[Tanda tangan Pasien]</div>
                        @endif
                        <div style="font-weight: bold; font-size: 7pt; border-top: 1px solid #475569; padding-top: 1px; margin-top: 2px;">
                            {{ $visit->informedConsent?->recipient_name ?: $patient->name }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <div style="font-size: 6.5pt; color: #64748b; font-style: italic; margin-bottom: 6px;">
            * Bila pasien tidak kompeten atau tidak mau menerima informasi, maka penerima informasi adalah wali atau keluarga terdekat.
        </div>

        <!-- Paragraf Persetujuan Tindakan Kedokteran (Sesuai Scan Hal 5) -->
        <div style="font-size: 7pt; text-align: justify; line-height: 1.3; background-color: #f8fafc; border: 1.5px solid #0f172a; padding: 5px 7px; border-radius: 3px; margin-bottom: 6px;">
            <div style="font-weight: 900; text-align: center; text-transform: uppercase; margin-bottom: 3px; font-size: 7.5pt;">PERSETUJUAN TINDAKAN KEDOKTERAN</div>
            Yang bertandatangan di bawah ini, saya, nama: <strong>{{ $visit->informedConsent?->recipient_name ?: $patient->name }}</strong>, umur: <strong>{{ $visit->informedConsent?->recipient_age ?: ($patient->date_of_birth ? $patient->date_of_birth->age : '28') }}</strong> tahun, alamat: <strong>{{ $patient->id_address ?: ($patient->address ?: '-') }}</strong>, dengan ini menyatakan persetujuan untuk dilakukannya tindakan: <strong style="text-decoration: underline;">{{ $visit->informedConsent?->medical_procedure ?: ($visit->informedConsent?->action_name ?: 'Messo + Botox + Laser CO2 + Multivitamin Infuse') }}</strong> terhadap {{ $visit->informedConsent?->recipient_relation ?: 'diri saya sendiri' }} bernama <strong>{{ $patient->name }}</strong>.<br>
            Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di atas kepada saya, termasuk risiko dan komplikasi yang mungkin timbul. Saya juga menyadari bahwa oleh karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan tindakan kedokteran bukanlah keniscayaan, melainkan sangat bergantung kepada izin Tuhan Yang Maha Esa.
        </div>

        <!-- Tanda Tangan Pasien & Saksi di Bagian Bawah Form -->
        <table class="bordered-table">
            <thead>
                <tr>
                    <th style="width: 50%; text-align: center;">Yang Menyatakan (Pasien / Wali)</th>
                    <th style="width: 50%; text-align: center;">Saksi Tindakan Medis</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; padding: 4px; vertical-align: top;">
                        <div style="font-size: 6.8pt; color: #475569; margin-bottom: 2px;">
                            Tanggal: {{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->format('d/m/Y') : $visit->created_at->format('d/m/Y') }} &bull; Pukul: {{ $visit->created_at->format('H:i') }} WIB
                        </div>
                        @if($procedureConsentSigBase64 || $signatureBase64)
                            <img src="{{ $procedureConsentSigBase64 ?: $signatureBase64 }}" style="height: 38px; max-width: 130px; display: block; margin: 0 auto;" alt="TTD Pasien">
                        @else
                            <div style="height: 38px; line-height: 38px; font-style: italic; color: #94a3b8;">[Tanda tangan]</div>
                        @endif
                        <div class="sig-line">
                            {{ $visit->informedConsent?->recipient_name ?: $patient->name }}
                        </div>
                    </td>
                    <td style="text-align: center; padding: 4px; vertical-align: top;">
                        <div style="font-size: 6.8pt; color: #475569; margin-bottom: 2px;">
                            Tanggal: {{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->format('d/m/Y') : $visit->created_at->format('d/m/Y') }}
                        </div>
                        @if($witnessConsentSigBase64)
                            <img src="{{ $witnessConsentSigBase64 }}" style="height: 38px; max-width: 130px; display: block; margin: 0 auto;" alt="TTD Saksi">
                        @else
                            <div style="height: 38px; line-height: 38px; font-style: italic; color: #94a3b8;">[Tanda tangan Saksi]</div>
                        @endif
                        <div class="sig-line">
                            {{ $visit->informedConsent?->witness_name ?: 'Saksi Klinik ONEW' }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer-note">
            Persetujuan Tindakan Kedokteran | ONEW Klinik Utama &bull; Halaman 5
        </div>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 6: BHP TINDAKAN HARIAN ONEW                                 -->
    <!-- Persis seperti FORM/page_6.jpg di formatform.pdf                   -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Header Form BHP -->
        <div class="center-header">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="ONEW Clinic">
            @endif
            <h2>BHP TINDAKAN HARIAN</h2>
            <p style="font-weight: bold; font-size: 8pt; color: #334155;">KLINIK UTAMA ONEW</p>
        </div>

        <!-- Metadata Form BHP (4 Kotak Sesuai Scan Hal 6) -->
        <table class="bordered-table" style="margin-bottom: 6px; background-color: #f8fafc;">
            <tr>
                <td style="width: 18%; font-weight: bold;">NAMA PASIEN</td>
                <td style="width: 32%; font-weight: bold; font-size: 8.5pt;">: {{ $patient->name }}</td>
                <td style="width: 20%; font-weight: bold;">PERAWAT / TERAPIS</td>
                <td style="width: 30%;">: {{ $visit->therapist_nurse_name ?: ($visit->assistant_name ?? ($visit->nurse?->name ?? 'Ns. Ratna Dewi, S.Kep')) }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">DOKTER</td>
                <td>: <strong>{{ $visit->doctor_name_override ?: ($visit->doctor?->name ?? 'dr. Alana Wijaya, Sp.D.V.E') }}</strong></td>
                <td style="font-weight: bold;">TANGGAL</td>
                <td style="font-weight: bold; font-family: monospace;">: {{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->translatedFormat('d F Y') : $visit->created_at->translatedFormat('d F Y') }}</td>
            </tr>
        </table>

        <!-- Layout 2 Kolom Sejajar: TINDAKAN di Kiri & BAHAN/BHP di Kanan -->
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <!-- Kolom Kiri: Tabel Tindakan Medis -->
                <td style="width: 44%; vertical-align: top; padding-right: 5px;">
                    <table class="bordered-table" style="margin-top: 0;">
                        <thead>
                            <tr>
                                <th style="width: 15%; text-align: center;">NO</th>
                                <th style="width: 85%;">TINDAKAN MEDIS / DOSIS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($visit->custom_treatment_list && count($visit->custom_treatment_list) > 0)
                                @foreach($visit->custom_treatment_list as $tIdx => $tItem)
                                    <tr>
                                        <td style="text-align: center; font-weight: bold;">{{ $tIdx + 1 }}</td>
                                        <td><strong>{{ $tItem['action'] ?? ($tItem['name'] ?? '-') }}</strong></td>
                                    </tr>
                                @endforeach
                            @elseif($visit->treatments && $visit->treatments->count() > 0)
                                @foreach($visit->treatments as $tIdx => $treatment)
                                    <tr>
                                        <td style="text-align: center; font-weight: bold;">{{ $tIdx + 1 }}</td>
                                        <td><strong>{{ $treatment->name }}</strong></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td style="text-align: center;">1</td>
                                    <td>{{ $visit->informedConsent?->action_name ?: 'Messo + Botox 50 unit + Laser CO2' }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">2</td>
                                    <td>Multivitamin Infuse</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">3</td>
                                    <td>Secretome 1 Vial</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </td>

                <!-- Kolom Kanan: Tabel Bahan Habis Pakai (BHP & QT) -->
                <td style="width: 56%; vertical-align: top; padding-left: 5px;">
                    <table class="bordered-table" style="margin-top: 0;">
                        <thead>
                            <tr>
                                <th style="width: 10%; text-align: center;">NO</th>
                                <th style="width: 62%;">NAMA BAHAN MEDIS (BHP)</th>
                                <th style="width: 28%; text-align: center;">QT</th>
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
                                    $sampleConsumables = $consumables->take(14);
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
        <table class="bordered-table" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th style="width: 50%; text-align: center;">DOKTER PELAKSANA</th>
                    <th style="width: 50%; text-align: center;">PERAWAT / TERAPIS ASISTEN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center; padding: 6px; vertical-align: top;">
                        @if($doctorConsentSigBase64)
                            <img src="{{ $doctorConsentSigBase64 }}" class="sig-image" alt="TTD Dokter">
                        @else
                            <div style="height: 48px; line-height: 48px; font-style: italic; color: #0284c7; font-weight: bold;">[Signed Electronically]</div>
                        @endif
                        <div class="sig-line">
                            {{ $visit->doctor_name_override ?: ($visit->doctor?->name ?? 'dr. Alana Wijaya, Sp.D.V.E') }}
                        </div>
                    </td>
                    <td style="text-align: center; padding: 6px; vertical-align: top;">
                        @if($generalConsentStaffSigBase64 || $registrationStaffSigBase64)
                            <img src="{{ $generalConsentStaffSigBase64 ?: $registrationStaffSigBase64 }}" class="sig-image" alt="TTD Perawat">
                        @else
                            <div style="height: 48px; line-height: 48px; font-style: italic; color: #94a3b8;">[Tanda tangan Perawat]</div>
                        @endif
                        <div class="sig-line">
                            {{ $visit->therapist_nurse_name ?: ($visit->assistant_name ?? ($visit->nurse?->name ?? 'Perawat Pelaksana')) }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer-note">
            BHP Tindakan Harian &bull; ONEW Klinik Utama &bull; Halaman 6
        </div>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 7: DOKUMENTASI FOTO 5 SISI SEBELUM TINDAKAN (BEFORE)        -->
    <!-- 1 Halaman Penuh Bersih Tanpa Overflow (3 Foto Row 1, 2 Foto Row 2)  -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Header Khusus Foto Before -->
        <table class="photo-page-header">
            <tr>
                <td style="width: 25%; vertical-align: middle;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic" style="height: 34px; width: auto;">
                    @else
                        <strong style="font-size: 13pt;">ONEW</strong>
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
        <table class="photo-grid-table" style="margin-bottom: 6px;">
            <tr>
                <!-- 1. Serong Kiri -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">1. Serong Kiri (45&deg;)</div>
                    <div class="photo-img-box">
                        @if($photoBeforeLeftTiltBase64)
                            <img src="{{ $photoBeforeLeftTiltBase64 }}" class="photo-frame-img" alt="Before Serong Kiri">
                        @else
                            <div class="photo-empty-box">Tidak Ada Foto</div>
                        @endif
                    </div>
                </td>

                <!-- 2. Tampak Depan (Center Focus) -->
                <td class="photo-card" style="width: 33.3%; border-color: #0f172a; background-color: #ffffff;">
                    <div class="photo-card-label" style="color: #0f172a; font-weight: 900; background-color: #f1f5f9;">2. TAMPAK DEPAN (CENTER)</div>
                    <div class="photo-img-box">
                        @if($photoBeforeFrontBase64 || $photoBeforeBase64)
                            <img src="{{ $photoBeforeFrontBase64 ?: $photoBeforeBase64 }}" class="photo-frame-img" alt="Before Tampak Depan">
                        @else
                            <div class="photo-empty-box">Tidak Ada Foto</div>
                        @endif
                    </div>
                </td>

                <!-- 3. Serong Kanan -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">3. Serong Kanan (45&deg;)</div>
                    <div class="photo-img-box">
                        @if($photoBeforeRightTiltBase64)
                            <img src="{{ $photoBeforeRightTiltBase64 }}" class="photo-frame-img" alt="Before Serong Kanan">
                        @else
                            <div class="photo-empty-box">Tidak Ada Foto</div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Row 2: 2 Foto Profil (Samping Kiri 90°, Samping Kanan 90°) -->
        <table class="photo-grid-table">
            <tr>
                <!-- 4. Samping Kiri -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">4. Samping Kiri (Profil 90&deg;)</div>
                    <div class="photo-img-box">
                        @if($photoBeforeLeftSideBase64)
                            <img src="{{ $photoBeforeLeftSideBase64 }}" class="photo-frame-img" alt="Before Samping Kiri">
                        @else
                            <div class="photo-empty-box">Tidak Ada Foto</div>
                        @endif
                    </div>
                </td>

                <!-- 5. Samping Kanan -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">5. Samping Kanan (Profil 90&deg;)</div>
                    <div class="photo-img-box">
                        @if($photoBeforeRightSideBase64)
                            <img src="{{ $photoBeforeRightSideBase64 }}" class="photo-frame-img" alt="Before Samping Kanan">
                        @else
                            <div class="photo-empty-box">Tidak Ada Foto</div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer-note">
            Dokumentasi Foto Klinis 5 Sisi (Sebelum Tindakan / Before) &bull; ONEW Klinik Utama &bull; Halaman 7
        </div>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 8: DOKUMENTASI FOTO 5 SISI SETELAH TINDAKAN (AFTER)         -->
    <!-- 1 Halaman Penuh Bersih Tanpa Overflow (3 Foto Row 1, 2 Foto Row 2)  -->
    <!-- =================================================================== -->
    <div class="page-container">
        <!-- Header Khusus Foto After -->
        <table class="photo-page-header">
            <tr>
                <td style="width: 25%; vertical-align: middle;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic" style="height: 34px; width: auto;">
                    @else
                        <strong style="font-size: 13pt;">ONEW</strong>
                    @endif
                </td>
                <td style="width: 75%; text-align: right; vertical-align: middle;">
                    <div class="photo-page-title">DOKUMENTASI FOTO 5 SISI WAJAH — SETELAH TINDAKAN (AFTER)</div>
                    <div class="photo-meta-info">
                        <strong>{{ $patient->name }}</strong> &bull;
                        No. RM: <span style="font-family: monospace; font-weight: bold;">{{ $visit->manual_patient_code ?: $patient->patient_code }}</span> &bull;
                        Tgl: {{ $visit->registration_date ? \Carbon\Carbon::parse($visit->registration_date)->format('d/m/Y') : $visit->created_at->format('d/m/Y') }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Row 1: 3 Foto (Serong Kiri 45°, Tampak Depan / Center, Serong Kanan 45°) -->
        <table class="photo-grid-table" style="margin-bottom: 6px;">
            <tr>
                <!-- 1. Serong Kiri -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">1. Serong Kiri (45&deg;)</div>
                    <div class="photo-img-box">
                        @if($photoAfterLeftTiltBase64)
                            <img src="{{ $photoAfterLeftTiltBase64 }}" class="photo-frame-img" alt="After Serong Kiri">
                        @else
                            <div class="photo-empty-box">Tidak Ada Foto</div>
                        @endif
                    </div>
                </td>

                <!-- 2. Tampak Depan (Center Focus) -->
                <td class="photo-card" style="width: 33.3%; border-color: #0f172a; background-color: #ffffff;">
                    <div class="photo-card-label" style="color: #0f172a; font-weight: 900; background-color: #f1f5f9;">2. TAMPAK DEPAN (CENTER)</div>
                    <div class="photo-img-box">
                        @if($photoAfterFrontBase64 || $photoAfterBase64)
                            <img src="{{ $photoAfterFrontBase64 ?: $photoAfterBase64 }}" class="photo-frame-img" alt="After Tampak Depan">
                        @else
                            <div class="photo-empty-box">Tidak Ada Foto</div>
                        @endif
                    </div>
                </td>

                <!-- 3. Serong Kanan -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">3. Serong Kanan (45&deg;)</div>
                    <div class="photo-img-box">
                        @if($photoAfterRightTiltBase64)
                            <img src="{{ $photoAfterRightTiltBase64 }}" class="photo-frame-img" alt="After Serong Kanan">
                        @else
                            <div class="photo-empty-box">Tidak Ada Foto</div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Row 2: 2 Foto Profil (Samping Kiri 90°, Samping Kanan 90°) -->
        <table class="photo-grid-table">
            <tr>
                <!-- 4. Samping Kiri -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">4. Samping Kiri (Profil 90&deg;)</div>
                    <div class="photo-img-box">
                        @if($photoAfterLeftSideBase64)
                            <img src="{{ $photoAfterLeftSideBase64 }}" class="photo-frame-img" alt="After Samping Kiri">
                        @else
                            <div class="photo-empty-box">Tidak Ada Foto</div>
                        @endif
                    </div>
                </td>

                <!-- 5. Samping Kanan -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">5. Samping Kanan (Profil 90&deg;)</div>
                    <div class="photo-img-box">
                        @if($photoAfterRightSideBase64)
                            <img src="{{ $photoAfterRightSideBase64 }}" class="photo-frame-img" alt="After Samping Kanan">
                        @else
                            <div class="photo-empty-box">Tidak Ada Foto</div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer-note">
            Dokumentasi Foto Klinis 5 Sisi (Setelah Tindakan / After) &bull; ONEW Klinik Utama &bull; Halaman 8
        </div>
    </div>

</body>
</html>
