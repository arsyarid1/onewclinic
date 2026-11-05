<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Medis Pasien - {{ $visit->manual_patient_code ?: ($patient->patient_code ?? 'PASIEN') }}</title>
    <style>
        @page {
            margin: 8mm 12mm 8mm 12mm;
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
            font-size: 7.5pt;
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
            padding-bottom: 4px;
            margin-bottom: 6px;
        }

        .kop-logo {
            width: 45%;
            vertical-align: middle;
            text-align: left;
        }

        .kop-logo img {
            height: 40px;
            width: auto;
            display: inline-block;
        }

        .kop-clinic-details {
            width: 55%;
            text-align: right;
            vertical-align: middle;
            font-size: 7pt;
            color: #475569;
            line-height: 1.2;
        }

        .kop-clinic-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }

        /* Center Header for General Consent / Informed Consent */
        .center-header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }

        .center-header img {
            height: 38px;
            width: auto;
            margin: 0 auto 2px auto;
            display: block;
        }

        .center-header h1 {
            margin: 1px 0 0 0;
            font-size: 11pt;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .center-header h2 {
            margin: 0;
            font-size: 10pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
        }

        .center-header p {
            margin: 1px 0 0 0;
            font-size: 7pt;
            color: #475569;
        }

        /* Title Box for Registration Form */
        .title-box-table {
            width: 100%;
            margin-bottom: 6px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
        }

        .title-box-left {
            width: 60%;
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
            margin: 1px 0 0 0;
            font-size: 7.5pt;
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
            padding: 3px 6px;
            font-size: 7pt;
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
            font-size: 8pt;
        }

        /* Section Headings */
        .section-bar {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 7.5pt;
            padding: 2.5px 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 5px;
            margin-bottom: 3px;
            border-radius: 2px;
        }

        /* Form Grid Table */
        .form-grid-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
            margin-bottom: 3px;
        }

        .form-grid-table td {
            padding: 2px 4px;
            vertical-align: top;
            border-bottom: 1px dotted #e2e8f0;
        }

        .field-label {
            color: #475569;
            font-weight: 600;
            width: 22%;
        }

        .field-val {
            color: #0f172a;
            font-weight: normal;
        }

        /* Bordered Tables */
        .bordered-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
            margin-top: 3px;
            margin-bottom: 4px;
        }

        .bordered-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 6.5pt;
            padding: 3px 4px;
            border: 1px solid #0f172a;
            text-align: left;
        }

        .bordered-table td {
            padding: 2.5px 4px;
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
            font-size: 7pt;
            padding: 0 8px;
        }

        .sig-image {
            height: 38px;
            max-width: 120px;
            display: block;
            margin: 2px auto;
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

        .photo-grid-row-1 {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
            margin-bottom: 6px;
        }

        .photo-grid-row-2 {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
        }

        .photo-card {
            border: 1.5px solid #cbd5e1;
            border-radius: 6px;
            background-color: #f8fafc;
            text-align: center;
            padding: 4px;
            vertical-align: top;
        }

        .photo-card-label {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            padding: 3px 4px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 4px;
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
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- =================================================================== -->
    <!-- HALAMAN 1: FORMULIR PENDAFTARAN PASIEN (TEPAT 1 HALAMAN LENGKAP TTD)  -->
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
                <td class="field-val" style="width: 38%;"><strong>{{ $patient->name }}</strong></td>
                <td class="field-label" style="width: 18%;">Jenis Kelamin</td>
                <td class="field-val" style="width: 22%;">{{ $patient->gender === 'female' ? 'Perempuan' : ($patient->gender === 'male' ? 'Laki-laki' : '-') }}</td>
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
                <td class="field-val" style="width: 38%;"><strong>{{ $patient->emergency_contact_name ?: '-' }}</strong></td>
                <td class="field-label" style="width: 18%;">Hubungan</td>
                <td class="field-val" style="width: 22%;">{{ $patient->emergency_contact_relation ?: '-' }}</td>
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
                <td class="field-val" style="width: 38%;"><strong>{{ $visit->visit_purpose ?: 'Estetika' }}</strong></td>
                <td class="field-label" style="width: 18%;">Dokter / Poli</td>
                <td class="field-val" style="width: 22%;">{{ $visit->destination_doctor_poli ?: '-' }}</td>
            </tr>
            <tr>
                <td class="field-label">Persetujuan Komunikasi</td>
                <td class="field-val" colspan="3">
                    [ X ] Bersedia menerima informasi, pengingat janji, dan komunikasi administratif melalui Telepon atau WhatsApp.
                </td>
            </tr>
        </table>

        <!-- Tanda Tangan Formulir Pendaftaran (Muat di Page 1) -->
        <table class="signature-table" style="margin-top: 10px;">
            <tr>
                <td class="signature-cell" style="width: 50%;">
                    <div>Petugas Pendaftaran,</div>
                    @if($registrationStaffSigBase64)
                        <img src="{{ $registrationStaffSigBase64 }}" class="sig-image" alt="TTD Petugas">
                    @else
                        <div style="height: 38px; line-height: 38px; font-style: italic; color: #94a3b8;">[Tanda Tangan Petugas]</div>
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
                        <div style="height: 38px; line-height: 38px; font-style: italic; color: #94a3b8;">[Tanda Tangan Pasien]</div>
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
    <!-- HALAMAN 2: GENERAL CONSENT (TEPAT 1 HALAMAN LENGKAP SAMPAI TTD)     -->
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
            <h1 style="margin-top: 2px;">GENERAL CONSENT</h1>
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
        <div style="font-size: 6.8pt; text-align: justify; line-height: 1.25; color: #1e293b;">
            <div style="margin-bottom: 3px;">
                <strong>1. PERSETUJUAN PELAYANAN KESEHATAN:</strong> Saya menyetujui untuk mendapatkan pelayanan medis, pemeriksaan fisik, asuhan keperawatan, prosedur diagnostik, dan terapi standar di Klinik Utama ONEW sesuai indikasi klinis dan pertimbangan dokter yang bertugas.
            </div>
            <div style="margin-bottom: 3px;">
                <strong>2. HAK DAN KEWAJIBAN PASIEN:</strong> Saya memahami hak saya untuk memperoleh informasi mengenai diagnosis, perkiraan biaya, rencana perawatan, serta kewajiban saya memberikan riwayat kesehatan yang jujur demi keselamatan medis saya sendiri.
            </div>
            <div style="margin-bottom: 3px;">
                <strong>3. PRIVASI DAN KERAHASIAAN MEDIS:</strong> Saya mengizinkan tenaga medis dan staf resmi klinik membuka informasi medis saya apabila diperlukan untuk kepentingan rujukan, penjaminan biaya asuransi, atau kepatuhan peraturan perundang-undangan kesehatan.
            </div>
            <div style="margin-bottom: 3px;">
                <strong>4. PELEPASAN INFORMASI MEDIS:</strong> Saya mengizinkan rekam medis elektronik saya disimpan secara aman dan diakses oleh dokter dan tim medis yang berwenang di Klinik Utama ONEW sesuai regulasi rekam medis yang berlaku.
            </div>
            <div style="margin-bottom: 3px;">
                <strong>5. PENGINGAT JADWAL & KOMUNIKASI ADMINISTRASI:</strong> Saya menyetujui Klinik Utama ONEW menghubungi saya melalui telepon, SMS, atau WhatsApp guna keperluan pengingat janji konsultasi, kontrol berkala, maupun tindak lanjut administrasi.
            </div>
            <div style="margin-bottom: 3px;">
                <strong>6. BARANG BERHARGA:</strong> Saya memahami bahwa pihak Klinik Utama ONEW tidak bertanggung jawab atas kehilangan atau kerusakan barang berharga milik pribadi yang dibawa ke area klinik.
            </div>
            <div style="margin-bottom: 3px;">
                <strong>7. PEMBIAYAAN DAN TATA TERTIB PEMBAYARAN:</strong> Saya bersedia melunasi seluruh tagihan atas konsultasi, tindakan medis penunjang, obat-obatan, dan layanan klinis yang diberikan sebelum meninggalkan area klinik.
            </div>
            <div style="margin-bottom: 3px;">
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
                        <div style="height: 38px; line-height: 38px; font-style: italic; color: #94a3b8;">[Tanda Tangan Petugas]</div>
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
                        <div style="height: 38px; line-height: 38px; font-style: italic; color: #94a3b8;">[Tanda Tangan Pasien]</div>
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
            <p style="font-weight: bold; font-size: 7.5pt; color: #334155;">INFORMED CONSENT RESMI KLINIS</p>
        </div>

        <!-- Metadata Dokter & Penerima Informasi (Dokter Kosong Jika Tidak Ada) -->
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

        <table class="form-grid-table" style="margin-bottom: 5px;">
            <tr>
                <td class="field-label" style="width: 25%;">Dokter Pelaksana Tindakan</td>
                <td class="field-val" style="width: 25%;">
                    <strong>{{ $doctorNameDisplay ?: '-' }}</strong>
                </td>
                <td class="field-label" style="width: 25%;">Penerima Informasi / Wali</td>
                <td class="field-val" style="width: 25%;">
                    <strong>{{ $recipientNameDisplay }}</strong>
                    <span style="font-size: 6.5pt; color: #64748b;">({{ $recipientRelationDisplay }})</span>
                </td>
            </tr>
            <tr>
                <td class="field-label">Pemberi Penjelasan Medis</td>
                <td class="field-val">{{ $infoProviderDisplay ?: '-' }}</td>
                <td class="field-label">Pasien Sasaran Tindakan</td>
                <td class="field-val">{{ $patient->name }} ({{ $patient->date_of_birth ? $patient->date_of_birth->age . ' Thn' : '-' }})</td>
            </tr>
        </table>

        <!-- Tabel 11 Poin Pemberian Informasi Medis -->
        <table class="bordered-table" style="margin-bottom: 5px;">
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

        <!-- Paragraf Pernyataan Persetujuan (Terisi Lengkap Tanpa Titik-titik Sesuai Attachment) -->
        <div style="font-size: 7.2pt; text-align: justify; line-height: 1.3; background-color: #f8fafc; border: 1.5px solid #0f172a; padding: 5px 7px; border-radius: 3px; margin-bottom: 6px;">
            <div style="font-weight: 900; text-align: center; text-transform: uppercase; margin-bottom: 3px; font-size: 7.5pt;">PERSETUJUAN TINDAKAN KEDOKTERAN</div>
            Yang bertandatangan di bawah ini, saya, nama: <strong>{{ $recipientNameDisplay }}</strong>, umur: <strong>{{ $recipientAgeDisplay ?: '-' }}</strong> tahun, alamat: <strong>{{ $recipientAddressDisplay ?: '-' }}</strong>, dengan ini menyatakan persetujuan untuk dilakukannya tindakan: <strong style="text-decoration: underline;">{{ $resolvedProcedure }}</strong> terhadap {{ $recipientRelationDisplay ?: 'diri saya sendiri' }} bernama <strong>{{ $patient->name }}</strong>.<br>
            Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan. Saya juga menyadari bahwa ilmu kedokteran bukanlah ilmu pasti, melainkan sangat bergantung kepada izin Tuhan Yang Maha Esa.
        </div>

        <!-- Tanda Tangan 3 Pihak (Dokter Nama Kosong Jika Tidak Ada) -->
        <table class="signature-table" style="margin-top: 6px;">
            <tr>
                <td class="signature-cell" style="width: 33.3%;">
                    <div>Dokter yang Menerangkan,</div>
                    @if($doctorConsentSigBase64)
                        <img src="{{ $doctorConsentSigBase64 }}" class="sig-image" alt="TTD Dokter">
                    @elseif($doctorNameDisplay)
                        <div style="height: 38px; line-height: 38px; font-style: italic; color: #0284c7; font-weight: bold;">[Signed Electronically]</div>
                    @else
                        <div style="height: 38px; line-height: 38px; color: #94a3b8; font-style: italic;">[Tanda Tangan Dokter]</div>
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
                        <div style="height: 38px; line-height: 38px; font-style: italic; color: #94a3b8;">[Tanda Tangan Pasien]</div>
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
                        <div style="height: 38px; line-height: 38px; font-style: italic; color: #94a3b8;">[Tanda Tangan Saksi]</div>
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
            <p style="font-weight: bold; font-size: 7.5pt; color: #334155;">KLINIK UTAMA ONEW</p>
        </div>

        <!-- Metadata Form BHP (4 Kotak, Dokter Kosong Jika Tidak Ada) -->
        <table class="bordered-table" style="margin-bottom: 8px; background-color: #f8fafc;">
            <tr>
                <td style="width: 18%; font-weight: bold;">NAMA PASIEN</td>
                <td style="width: 32%; font-weight: bold; font-size: 8pt;">{{ $patient->name }}</td>
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
                                    <td>{{ $resolvedProcedure }}</td>
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

        <!-- CATATAN: Poin 3 User: BHP Tindakan Harian tidak perlu tanda tangan sama sekali -->
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 5: FULL SATU HALAMAN A4 FOTO 5 SISI BEFORE                  -->
    <!-- URUTAN: 1. Depan, 2. Serong Kanan, 3. Serong Kiri, 4. Tampak Kanan, 5. Tampak Kiri -->
    <!-- =================================================================== -->
    <div class="page-container" style="height: 100%;">
        <!-- Header Khusus Foto Before -->
        <table class="photo-page-header">
            <tr>
                <td style="width: 25%; vertical-align: middle;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic" style="height: 36px; width: auto;">
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

        <!-- Row 1: 3 Foto (1. Tampak Depan, 2. Serong Kanan, 3. Serong Kiri) -->
        <table class="photo-grid-row-1">
            <tr>
                <!-- 1. Tampak Depan (Center Focus) -->
                <td class="photo-card" style="width: 33.3%; border-color: #0f172a; background-color: #ffffff;">
                    <div class="photo-card-label" style="color: #0f172a; font-weight: 900; background-color: #f1f5f9;">1. TAMPAK DEPAN (0&deg;)</div>
                    @if($photoBeforeFrontBase64 || $photoBeforeBase64)
                        <img src="{{ $photoBeforeFrontBase64 ?: $photoBeforeBase64 }}" class="photo-frame-img" style="height: 425px;" alt="Before Tampak Depan">
                    @else
                        <div class="photo-empty-box" style="height: 425px; line-height: 425px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 2. Serong Kanan -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">2. SERONG KANAN (45&deg;)</div>
                    @if($photoBeforeRightTiltBase64)
                        <img src="{{ $photoBeforeRightTiltBase64 }}" class="photo-frame-img" style="height: 425px;" alt="Before Serong Kanan">
                    @else
                        <div class="photo-empty-box" style="height: 425px; line-height: 425px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 3. Serong Kiri -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">3. SERONG KIRI (45&deg;)</div>
                    @if($photoBeforeLeftTiltBase64)
                        <img src="{{ $photoBeforeLeftTiltBase64 }}" class="photo-frame-img" style="height: 425px;" alt="Before Serong Kiri">
                    @else
                        <div class="photo-empty-box" style="height: 425px; line-height: 425px;">
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
                        <img src="{{ $photoBeforeRightSideBase64 }}" class="photo-frame-img" style="height: 425px;" alt="Before Tampak Kanan">
                    @else
                        <div class="photo-empty-box" style="height: 425px; line-height: 425px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 5. Tampak Kiri (Profil 90°) -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">5. TAMPAK KIRI (PROFIL 90&deg;)</div>
                    @if($photoBeforeLeftSideBase64)
                        <img src="{{ $photoBeforeLeftSideBase64 }}" class="photo-frame-img" style="height: 425px;" alt="Before Tampak Kiri">
                    @else
                        <div class="photo-empty-box" style="height: 425px; line-height: 425px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- =================================================================== -->
    <!-- HALAMAN 6: FULL SATU HALAMAN A4 FOTO 5 SISI AFTER                   -->
    <!-- URUTAN: 1. Depan, 2. Serong Kanan, 3. Serong Kiri, 4. Tampak Kanan, 5. Tampak Kiri -->
    <!-- =================================================================== -->
    <div class="page-container" style="height: 100%;">
        <!-- Header Khusus Foto After -->
        <table class="photo-page-header">
            <tr>
                <td style="width: 25%; vertical-align: middle;">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="ONEW Clinic" style="height: 36px; width: auto;">
                    @else
                        <strong style="font-size: 13pt;">ONEW</strong>
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
                        <img src="{{ $photoAfterFrontBase64 ?: $photoAfterBase64 }}" class="photo-frame-img" style="height: 425px;" alt="After Tampak Depan">
                    @else
                        <div class="photo-empty-box" style="height: 425px; line-height: 425px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 2. Serong Kanan -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">2. SERONG KANAN (45&deg;)</div>
                    @if($photoAfterRightTiltBase64)
                        <img src="{{ $photoAfterRightTiltBase64 }}" class="photo-frame-img" style="height: 425px;" alt="After Serong Kanan">
                    @else
                        <div class="photo-empty-box" style="height: 425px; line-height: 425px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 3. Serong Kiri -->
                <td class="photo-card" style="width: 33.3%;">
                    <div class="photo-card-label">3. SERONG KIRI (45&deg;)</div>
                    @if($photoAfterLeftTiltBase64)
                        <img src="{{ $photoAfterLeftTiltBase64 }}" class="photo-frame-img" style="height: 425px;" alt="After Serong Kiri">
                    @else
                        <div class="photo-empty-box" style="height: 425px; line-height: 425px;">
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
                        <img src="{{ $photoAfterRightSideBase64 }}" class="photo-frame-img" style="height: 425px;" alt="After Tampak Kanan">
                    @else
                        <div class="photo-empty-box" style="height: 425px; line-height: 425px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>

                <!-- 5. Tampak Kiri (Profil 90°) -->
                <td class="photo-card" style="width: 50%;">
                    <div class="photo-card-label">5. TAMPAK KIRI (PROFIL 90&deg;)</div>
                    @if($photoAfterLeftSideBase64)
                        <img src="{{ $photoAfterLeftSideBase64 }}" class="photo-frame-img" style="height: 425px;" alt="After Tampak Kiri">
                    @else
                        <div class="photo-empty-box" style="height: 425px; line-height: 425px;">
                            Tidak Ada Foto
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
