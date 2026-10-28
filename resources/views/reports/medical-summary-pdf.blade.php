<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Medis Pasien - {{ $patient->patient_code ?? 'PASIEN' }}</title>
    <style>
        @page {
            margin: 1.2cm 1.2cm 1.2cm 1.2cm;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #1e293b;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            line-height: 1.4;
        }

        /* Kop Surat Klinik */
        .kop-table {
            width: 100%;
            border-bottom: 2.5px solid {{ $clinic->primary_color ?? '#0284c7' }};
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .kop-logo {
            width: 70px;
            text-align: center;
            vertical-align: middle;
        }

        .kop-logo-box {
            width: 58px;
            height: 58px;
            background-color: {{ $clinic->primary_color ?? '#0284c7' }};
            color: #ffffff;
            font-weight: bold;
            font-size: 20pt;
            line-height: 58px;
            border-radius: 8px;
            text-align: center;
        }

        .kop-clinic-name {
            font-size: 16pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        .kop-clinic-code {
            font-size: 9pt;
            font-weight: bold;
            color: {{ $clinic->primary_color ?? '#0284c7' }};
            padding-left: 6px;
        }

        .kop-sub {
            font-size: 8.5pt;
            color: #64748b;
            margin-top: 2px;
        }

        .kop-right {
            text-align: right;
            vertical-align: middle;
            font-size: 8pt;
            color: #64748b;
        }

        /* Document Title */
        .report-title {
            text-align: center;
            margin: 10px 0 14px 0;
        }

        .report-title h2 {
            margin: 0;
            font-size: 13pt;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .report-title p {
            margin: 2px 0 0 0;
            font-size: 8pt;
            color: #64748b;
            font-weight: 500;
        }

        /* Section Headings */
        .section-header {
            font-size: 9pt;
            font-weight: bold;
            color: {{ $clinic->primary_color ?? '#0284c7' }};
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
        }

        /* Two-Column Info Grids */
        .info-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 3px 6px;
            vertical-align: top;
            font-size: 8.5pt;
        }

        .info-label {
            width: 28%;
            color: #64748b;
            font-weight: 600;
        }

        .info-value {
            color: #0f172a;
            font-weight: 500;
        }

        /* Treatments Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .data-table th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 8pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1.5px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
        }

        .data-table td {
            border-bottom: 1px solid #f1f5f9;
            padding: 6px 8px;
            font-size: 8.5pt;
            vertical-align: middle;
        }

        .data-table tr:nth-child(even) td {
            background-color: #fbfcfe;
        }

        .price-col {
            text-align: right;
            font-family: monospace;
            font-size: 9pt;
        }

        /* Photo Comparison Box */
        .photo-table {
            width: 100%;
            margin-top: 6px;
            margin-bottom: 14px;
            border-collapse: separate;
            border-spacing: 12px 0;
        }

        .photo-box {
            width: 50%;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px;
            text-align: center;
            background-color: #f8fafc;
        }

        .photo-title {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 6px;
        }

        .photo-img {
            max-width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }

        .photo-placeholder {
            height: 140px;
            line-height: 140px;
            color: #94a3b8;
            font-size: 8pt;
            background: #ffffff;
            border: 1px dashed #cbd5e1;
            border-radius: 4px;
        }

        /* Sign-off & Consent Box */
        .sign-table {
            width: 100%;
            margin-top: 16px;
            border-collapse: collapse;
        }

        .sign-col {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 8pt;
        }

        .sign-img {
            height: 60px;
            margin: 6px auto;
            display: block;
        }

        .sign-placeholder {
            height: 60px;
            margin: 6px auto;
            line-height: 60px;
            color: #cbd5e1;
            font-size: 7.5pt;
        }

        /* Trustmedis Sync Stamp Footer */
        .sync-badge {
            margin-top: 15px;
            padding: 6px 10px;
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            font-size: 7.5pt;
            color: #166534;
        }
    </style>
</head>
<body>
    <!-- Kop Surat Klinik Dinamis -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo" style="height: 52px; width: auto; border-radius: 6px;">
                @else
                    <div class="kop-logo-box">
                        {{ substr($clinic->clinic_code ?? 'ON', 0, 2) }}
                    </div>
                @endif
            </td>
            <td style="padding-left: 10px; vertical-align: middle;">
                <div class="kop-clinic-name">
                    {{ $clinic->name ?? 'ONEW Clinic' }}
                    <span class="kop-clinic-code">[{{ $clinic->clinic_code ?? 'ONEW' }}]</span>
                </div>
                <div class="kop-sub">{{ $clinic->address ?? 'Klinik Pratama Rawat Jalan Pelayanan Medik Estetika' }}</div>
                <div class="kop-sub">Telp: {{ $clinic->phone ?? '0812-9988-7766' }} &bull; Rekam Medis Elektronik Terintegrasi</div>
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
    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <!-- Kolom Kiri: Pasien -->
            <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                <div class="section-header">Identitas Pasien</div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">No. Rekam Medis</td>
                        <td class="info-value" style="font-family: monospace; font-weight: bold; color: {{ $clinic->primary_color ?? '#0284c7' }};">
                            {{ $patient->patient_code }}
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Nama Lengkap</td>
                        <td class="info-value"><strong>{{ $patient->name }}</strong></td>
                    </tr>
                    <tr>
                        <td class="info-label">Tanggal Lahir / Usia</td>
                        <td class="info-value">
                            {{ $patient->date_of_birth ? $patient->date_of_birth->format('d/m/Y') : '-' }}
                            ({{ $patient->date_of_birth ? $patient->date_of_birth->age . ' Tahun' : '-' }})
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Jenis Kelamin</td>
                        <td class="info-value">{{ $patient->gender === 'female' ? 'Perempuan' : 'Laki-laki' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">No. Telepon / WA</td>
                        <td class="info-value">{{ $patient->phone_number }}</td>
                    </tr>
                </table>
            </td>

            <!-- Kolom Kanan: Kunjungan -->
            <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                <div class="section-header">Informasi Kunjungan & Tim Medis</div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Tanggal Pelayanan</td>
                        <td class="info-value">{{ $visit->created_at->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Waktu Encounter</td>
                        <td class="info-value">{{ $visit->created_at->format('H:i') }} WIB</td>
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
                        <td class="info-label">Status General Consent</td>
                        <td class="info-value" style="color: #166534; font-weight: bold;">
                            {{ $visit->consent_signed ? 'DITANDATANGANI (' . $visit->consent_signed_at?->format('H:i') . ' WIB)' : 'BELUM' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
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
                    <td style="font-size: 8pt; color: #334155;">
                        {{ $treatment->pivot->notes ?: 'Tindakan selesai dilakukan tanpa komplikasi.' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #94a3b8; padding: 12px;">
                        Tidak ada tindakan perawatan yang tercatat.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Komparasi Foto Klinis Side-by-Side (Before & After) -->
    <div class="section-header">Dokumentasi Foto Klinis (Before & After Comparison)</div>
    <table class="photo-table">
        <tr>
            <td class="photo-box">
                <div class="photo-title">Dokumentasi Foto Sebelum Tindakan (Before)</div>
                @if($photoBeforeBase64)
                    <img src="{{ $photoBeforeBase64 }}" alt="Foto Before" class="photo-img">
                @else
                    <div class="photo-placeholder">Tidak Ada Dokumentasi Foto Before</div>
                @endif
                <div style="font-size: 7pt; color: #64748b; margin-top: 4px;">Pemeriksaan Awal Perawat</div>
            </td>
            <td class="photo-box">
                <div class="photo-title">Dokumentasi Foto Sesudah Tindakan (After)</div>
                @if($photoAfterBase64)
                    <img src="{{ $photoAfterBase64 }}" alt="Foto After" class="photo-img">
                @else
                    <div class="photo-placeholder">Tidak Ada Dokumentasi Foto After</div>
                @endif
                <div style="font-size: 7pt; color: #64748b; margin-top: 4px;">Evaluasi Pasca Tindakan Dokter</div>
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
                    <div class="sign-placeholder">[Tanda Tangan Digital Tersimpan]</div>
                @endif
                <div style="border-top: 1px dotted #94a3b8; width: 60%; margin: 0 auto; padding-top: 3px;">
                    <strong>{{ $patient->name }}</strong><br>
                    <span style="font-size: 7pt; color: #64748b;">Pasien / Penanggung Jawab</span>
                </div>
            </td>

            <td class="sign-col">
                <div>Dokter Penanggung Jawab Pelayanan</div>
                <div style="height: 60px; line-height: 60px; font-style: italic; color: {{ $clinic->primary_color ?? '#0284c7' }}; font-weight: bold; font-size: 11pt;">
                    Signed Electronically
                </div>
                <div style="border-top: 1px dotted #94a3b8; width: 60%; margin: 0 auto; padding-top: 3px;">
                    <strong>{{ $visit->doctor?->name ?? 'dr. Alana Wijaya, Sp.D.V.E' }}</strong><br>
                    <span style="font-size: 7pt; color: #64748b;">SIP: 446.1/0928/DS/DMP/2024</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Trustmedis Sync Verification Footer -->
    <div class="sync-badge">
        <table style="width: 100%;">
            <tr>
                <td style="vertical-align: middle;">
                    <strong>STATUS INTEGRASI TRUSTMEDIS EHR:</strong>
                    @if($visit->trustmedis_sync_status === 'synced')
                        <span style="font-weight: bold; color: #166534;">TERVERIFIKASI & TERSINKRONISASI</span>
                    @else
                        <span style="color: #b45309;">MENUNGGU SINKRONISASI</span>
                    @endif
                </td>
                <td style="text-align: right; vertical-align: middle; font-family: monospace; font-size: 7pt;">
                    REF ID: {{ $visit->trustmedis_payload['meta']['transaction_id'] ?? ('TM-' . strtoupper(substr(md5($visit->id), 0, 8))) }} &bull;
                    Printed: {{ $generatedAt->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
