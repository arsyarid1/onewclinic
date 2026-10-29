<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Medis Pasien - {{ $patient->patient_code ?? 'PASIEN' }}</title>
    <style>
        @page {
            margin: 1.0cm 1.2cm 1.0cm 1.2cm;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9pt;
            color: #1e293b;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            line-height: 1.35;
        }

        /* Kop Surat Klinik */
        .kop-table {
            width: 100%;
            border-bottom: 2.5px solid {{ $clinic->primary_color ?? '#0284c7' }};
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .kop-logo {
            width: 65px;
            text-align: center;
            vertical-align: middle;
        }

        .kop-clinic-name {
            font-size: 15pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        .kop-clinic-code {
            font-size: 8.5pt;
            font-weight: bold;
            color: {{ $clinic->primary_color ?? '#0284c7' }};
            padding-left: 6px;
        }

        .kop-sub {
            font-size: 8pt;
            color: #64748b;
            margin-top: 1.5px;
        }

        .kop-right {
            text-align: right;
            vertical-align: middle;
            font-size: 7.5pt;
            color: #64748b;
        }

        /* Document Title */
        .report-title {
            text-align: center;
            margin: 8px 0 10px 0;
        }

        .report-title h2 {
            margin: 0;
            font-size: 12pt;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .report-title p {
            margin: 1px 0 0 0;
            font-size: 7.5pt;
            color: #64748b;
            font-weight: 500;
        }

        /* Section Headings */
        .section-header {
            font-size: 8.5pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #f1f5f9;
            padding: 3.5px 8px;
            border-left: 3px solid {{ $clinic->primary_color ?? '#0284c7' }};
            margin-top: 8px;
            margin-bottom: 5px;
        }

        /* Info Grid Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        .info-table td {
            padding: 2.5px 6px;
            vertical-align: top;
        }

        .info-label {
            width: 38%;
            color: #64748b;
            font-weight: 500;
        }

        .info-value {
            width: 62%;
            color: #0f172a;
            font-weight: normal;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-bottom: 6px;
        }

        .data-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7pt;
            letter-spacing: 0.5px;
            padding: 4px 6px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }

        .data-table td {
            padding: 4px 6px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        /* Photo Table */
        .photo-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 6px;
        }

        .photo-box {
            width: 50%;
            padding: 4px;
            text-align: center;
            vertical-align: top;
        }

        .photo-title {
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 3px;
        }

        .photo-img {
            max-height: 120px;
            max-width: 90%;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
        }

        .photo-placeholder {
            height: 90px;
            line-height: 90px;
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 4px;
            font-size: 7pt;
            color: #94a3b8;
            font-style: italic;
        }

        /* Signature Table */
        .sign-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .sign-col {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 7.5pt;
        }

        .sign-img {
            max-height: 50px;
            max-width: 130px;
            margin: 4px auto;
            display: block;
        }
    </style>
</head>
<body>
    <!-- Kop Surat Klinik Dinamis -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo ONEW" style="height: 48px; width: auto; border-radius: 6px;">
                @else
                    <div style="width: 48px; height: 48px; background-color: #0284c7; color: #fff; font-weight: bold; font-size: 16pt; line-height: 48px; border-radius: 6px; text-align: center;">
                        {{ substr($clinic->clinic_code ?? 'ON', 0, 2) }}
                    </div>
                @endif
            </td>
            <td style="padding-left: 10px; vertical-align: middle;">
                <div class="kop-clinic-name">
                    {{ $clinic->name ?? 'ONEW Clinic' }}
                    <span class="kop-clinic-code">[{{ $clinic->clinic_code ?? 'ONEW' }}]</span>
                </div>
                <div class="kop-sub">{{ $clinic->address ?? 'Jl. Laksana I no 6, Kebayoran Baru, Jaksel 12180' }}</div>
                <div class="kop-sub">Telp: {{ $clinic->phone ?? '+62 8119 6977 886 / +62 21382 506 94' }} &bull; Rekam Medis Elektronik Terintegrasi</div>
            </td>
            <td class="kop-right">
                <strong>FORM REKAM MEDIS</strong><br>
                <span>No. RM: {{ $patient->patient_code }}</span><br>
                <span>Encounter ID: #{{ $visit->id }}</span>
            </td>
        </tr>
    </table>

    <!-- Judul Dokumen -->
    <div class="report-title">
        <h2>RINGKASAN REKAM MEDIS KUNJUNGAN ESTETIKA</h2>
        <p>Medical Summary & Clinical Treatment Encounter Report</p>
    </div>

    <!-- Informasi Pasien & Kunjungan (2 Kolom) -->
    <table style="width: 100%; margin-bottom: 6px;">
        <tr>
            <!-- Kolom Kiri: Pasien -->
            <td style="width: 50%; vertical-align: top; padding-right: 8px;">
                <div class="section-header">Identitas Pasien</div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">No. Rekam Medis</td>
                        <td class="info-value" style="font-family: monospace; font-weight: bold; color: {{ $clinic->primary_color ?? '#0284c7' }};">
                            {{ $patient->patient_code }}
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Nama Lengkap / NIK</td>
                        <td class="info-value"><strong>{{ $patient->name }}</strong> @if($patient->nik) <span style="font-size: 7pt; color: #64748b;">({{ $patient->nik }})</span> @endif</td>
                    </tr>
                    <tr>
                        <td class="info-label">Tanggal Lahir / Usia</td>
                        <td class="info-value">
                            {{ $patient->date_of_birth ? $patient->date_of_birth->format('d/m/Y') : '-' }}
                            ({{ $patient->date_of_birth ? $patient->date_of_birth->age . ' Thn' : '-' }}) &bull; {{ $patient->gender === 'female' ? 'P' : 'L' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">No. Telepon / WA</td>
                        <td class="info-value">{{ $patient->phone_number }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Kontak Darurat</td>
                        <td class="info-value">{{ $patient->emergency_contact_name ?: '-' }} ({{ $patient->emergency_contact_relation ?: '-' }})</td>
                    </tr>
                </table>
            </td>

            <!-- Kolom Kanan: Kunjungan -->
            <td style="width: 50%; vertical-align: top; padding-left: 8px;">
                <div class="section-header">Informasi Kunjungan & Tim Medis</div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Tanggal & Jam</td>
                        <td class="info-value">{{ $visit->created_at->translatedFormat('d M Y') }} &bull; {{ $visit->created_at->format('H:i') }} WIB</td>
                    </tr>
                    <tr>
                        <td class="info-label">Dokter Pemeriksa</td>
                        <td class="info-value"><strong>{{ $visit->doctor?->name ?? 'dr. Alana Wijaya, Sp.D.V.E' }}</strong></td>
                    </tr>
                    <tr>
                        <td class="info-label">Perawat / Asisten</td>
                        <td class="info-value">{{ $visit->assistant_name ?? 'Ns. Ratna Dewi, S.Kep' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">General Consent</td>
                        <td class="info-value" style="color: #166534; font-weight: bold;">
                            {{ $visit->consent_signed ? 'DITANDATANGANI (' . $visit->consent_signed_at?->format('H:i') . ' WIB)' : 'BELUM' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Informed Consent</td>
                        <td class="info-value" style="font-weight: bold; color: {{ $visit->informedConsent ? '#166534' : '#b45309' }};">
                            {{ $visit->informedConsent ? 'DISETUJUI & TERTANDATANGAN' : 'TIDAK DIBUTUHKAN / BELUM' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Diagnosis Medis Dokter -->
    <div class="section-header">Diagnosis Medis & Evaluasi Klinis Dokter</div>
    <table class="info-table" style="margin-bottom: 6px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px;">
        <tr>
            <td style="width: 25%; font-weight: bold; color: #475569; padding: 4px 6px;">Working Diagnosis (WD):</td>
            <td style="width: 75%; font-weight: bold; color: #0f172a; padding: 4px 6px;">{{ $visit->diagnosis_wd ?: 'Evaluasi Kulit & Estetika Wajah (General Aesthetic Evaluation)' }}</td>
        </tr>
        @if($visit->diagnosis_dd)
            <tr>
                <td style="font-weight: bold; color: #475569; padding: 4px 6px;">Differential Diagnosis (DD):</td>
                <td style="color: #334155; padding: 4px 6px;">{{ $visit->diagnosis_dd }}</td>
            </tr>
        @endif
        @if($visit->doctor_notes)
            <tr>
                <td style="font-weight: bold; color: #475569; padding: 4px 6px;">Catatan Klinis:</td>
                <td style="color: #334155; padding: 4px 6px;">{{ $visit->doctor_notes }}</td>
            </tr>
        @endif
    </table>

    <!-- Daftar Tindakan Perawatan Medis -->
    <div class="section-header">Daftar Tindakan Perawatan (Treatments Administered)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Tindakan</th>
                <th style="width: 25%;">Kategori</th>
                <th style="width: 35%;">Catatan Klinis Dokter</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visit->treatments as $index => $treatment)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $treatment->name }}</strong></td>
                    <td style="color: #64748b;">{{ $treatment->category ?? 'Estetika' }}</td>
                    <td style="font-size: 7.5pt; color: #334155;">
                        {{ $treatment->pivot->notes ?: 'Tindakan selesai dilakukan tanpa komplikasi.' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #94a3b8; padding: 6px;">
                        Tidak ada tindakan perawatan yang tercatat.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Bahan Baku / BHP (Barang Habis Pakai) yang Digunakan -->
    @if($visit->visitConsumables && $visit->visitConsumables->count() > 0)
        <div class="section-header">Bahan Habis Pakai (BHP Tindakan Harian ONEW)</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 50%;">Nama Bahan Medis / BHP</th>
                    <th style="width: 25%;">Kategori</th>
                    <th style="width: 20%; text-align: center;">Jumlah Dipakai (QT)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($visit->visitConsumables as $cIdx => $vc)
                    <tr>
                        <td style="text-align: center;">{{ $cIdx + 1 }}</td>
                        <td><strong>{{ $vc->consumable?->name ?? 'BHP' }}</strong></td>
                        <td style="color: #64748b;">{{ $vc->consumable?->category ?? 'Disposable' }}</td>
                        <td style="text-align: center; font-family: monospace; font-weight: bold;">
                            {{ $vc->quantity }} {{ $vc->consumable?->unit ?? 'pcs' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Komparasi Foto Klinis Side-by-Side (Before & After) -->
    <div class="section-header">Dokumentasi Foto Klinis (Before & After)</div>
    <table class="photo-table">
        <tr>
            <td class="photo-box">
                <div class="photo-title">Foto Sebelum Tindakan (Before)</div>
                @if($photoBeforeBase64)
                    <img src="{{ $photoBeforeBase64 }}" alt="Foto Before" class="photo-img">
                @else
                    <div class="photo-placeholder">Tidak Ada Foto Before</div>
                @endif
                <div style="font-size: 6.5pt; color: #64748b; margin-top: 2px;">Pemeriksaan Awal</div>
            </td>
            <td class="photo-box">
                <div class="photo-title">Foto Sesudah Tindakan (After)</div>
                @if($photoAfterBase64)
                    <img src="{{ $photoAfterBase64 }}" alt="Foto After" class="photo-img">
                @else
                    <div class="photo-placeholder">Tidak Ada Foto After</div>
                @endif
                <div style="font-size: 6.5pt; color: #64748b; margin-top: 2px;">Evaluasi Pasca Tindakan (Perawat / Dokter)</div>
            </td>
        </tr>
    </table>

    <!-- Tanda Tangan Pasien & Dokter Penanggung Jawab -->
    <table class="sign-table">
        <tr>
            <td class="sign-col">
                <div>Persetujuan Pasien (General Consent)</div>
                @if($signatureBase64)
                    <img src="{{ $signatureBase64 }}" alt="Tanda Tangan Pasien" class="sign-img">
                @else
                    <div style="height: 45px; line-height: 45px; color: #94a3b8; font-style: italic;">[Tanda Tangan Tersimpan]</div>
                @endif
                <div style="border-top: 1px dotted #94a3b8; width: 60%; margin: 0 auto; padding-top: 2px;">
                    <strong>{{ $patient->name }}</strong><br>
                    <span style="font-size: 6.5pt; color: #64748b;">Pasien / Penanggung Jawab</span>
                </div>
            </td>

            <td class="sign-col">
                <div>Dokter Penanggung Jawab Pelayanan</div>
                <div style="height: 45px; line-height: 45px; font-style: italic; color: {{ $clinic->primary_color ?? '#0284c7' }}; font-weight: bold; font-size: 10pt;">
                    Signed Electronically
                </div>
                <div style="border-top: 1px dotted #94a3b8; width: 60%; margin: 0 auto; padding-top: 2px;">
                    <strong>{{ $visit->doctor?->name ?? 'dr. Alana Wijaya, Sp.D.V.E' }}</strong><br>
                    <span style="font-size: 6.5pt; color: #64748b;">Dokter Pelaksana Tindakan</span>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
