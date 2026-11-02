<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MedicalExportController extends Controller
{
    /**
     * Export all medical records and clinical flow visits to CSV (compatible with Excel).
     */
    public function exportCsv(): StreamedResponse
    {
        $filename = 'onew_clinic_rekam_medis_' . Carbon::now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');

            // Write UTF-8 BOM for Microsoft Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV Columns Header
            fputcsv($handle, [
                'ID Kunjungan',
                'No. Rekam Medis',
                'No. ID InBody',
                'Tanggal Registrasi',
                'Nama Lengkap Pasien',
                'NIK / No. Paspor',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Jenis Kelamin',
                'Status Perkawinan',
                'No. HP / WhatsApp',
                'Email',
                'Alamat Identitas',
                'Alamat Domisili',
                'Pekerjaan',
                'Kontak Darurat',
                'Hubungan Kontak Darurat',
                'No. HP Kontak Darurat',
                'Jenis Pembayaran',
                'Nama Asuransi / Perusahaan',
                'No. Polis',
                'Status Pasien',
                'Tujuan Kunjungan',
                'Dokter / Poli Tujuan',
                'Keluhan Singkat',
                'Dokter Pelaksana',
                'Perawat / Terapis',
                'Diagnosis (WD & DD)',
                'Tindakan Kedokteran (Informed Consent)',
                'Daftar Tindakan Harian',
                'Bahan Baku / BHP yang Digunakan',
                'Foto Before (5 Sisi)',
                'Foto After (5 Sisi)',
                'Status Kunjungan',
                'Waktu Pembuatan Data',
            ]);

            // Stream visits with relations
            Visit::with(['patient', 'doctor', 'nurse', 'informedConsent', 'visitConsumables.consumable'])
                ->orderBy('id', 'desc')
                ->chunk(100, function ($visits) use ($handle) {
                    foreach ($visits as $visit) {
                        $p = $visit->patient;
                        $ic = $visit->informedConsent;

                        // Compile Custom Treatments
                        $treatmentList = '';
                        if (!empty($visit->custom_treatment_list) && is_array($visit->custom_treatment_list)) {
                            $actions = array_map(fn($row) => $row['action'] ?? '', $visit->custom_treatment_list);
                            $treatmentList = implode('; ', array_filter($actions));
                        }

                        // Compile BHP Consumables
                        $consumablesSummary = [];
                        foreach ($visit->visitConsumables as $vc) {
                            if ($vc->consumable && $vc->quantity > 0) {
                                $consumablesSummary[] = "{$vc->consumable->name} ({$vc->quantity} {$vc->consumable->unit})";
                            }
                        }
                        $bhpText = implode('; ', $consumablesSummary);

                        // Check 5 Sides Photos
                        $beforeCount = 0;
                        if ($visit->photo_before_front) $beforeCount++;
                        if ($visit->photo_before_right_tilt) $beforeCount++;
                        if ($visit->photo_before_right_side) $beforeCount++;
                        if ($visit->photo_before_left_tilt) $beforeCount++;
                        if ($visit->photo_before_left_side) $beforeCount++;
                        $beforeStatus = $beforeCount > 0 ? "{$beforeCount}/5 Sisi Terunggah" : ($visit->photo_before_path ? '1 Foto Awal' : 'Belum Ada');

                        $afterCount = 0;
                        if ($visit->photo_after_front) $afterCount++;
                        if ($visit->photo_after_right_tilt) $afterCount++;
                        if ($visit->photo_after_right_side) $afterCount++;
                        if ($visit->photo_after_left_tilt) $afterCount++;
                        if ($visit->photo_after_left_side) $afterCount++;
                        $afterStatus = $afterCount > 0 ? "{$afterCount}/5 Sisi Terunggah" : ($visit->photo_after_path ? '1 Foto Akhir' : 'Belum Ada');

                        fputcsv($handle, [
                            $visit->id,
                            $visit->manual_patient_code ?: ($p->patient_code ?? '-'),
                            $visit->inbody_id ?: ($p->inbody_id ?? '-'),
                            $visit->registration_date ? $visit->registration_date->format('Y-m-d') : ($visit->created_at->format('Y-m-d')),
                            $p->name ?? 'Pasien',
                            $p->nik_passport ?: ($p->nik ?? '-'),
                            $p->birth_place ?? '-',
                            $p->date_of_birth ? $p->date_of_birth->format('Y-m-d') : '-',
                            $p->gender ? ucfirst($p->gender) : '-',
                            $p->marital_status ?? '-',
                            $p->phone_number ?? '-',
                            $p->email ?? '-',
                            $p->id_address ?: ($p->address ?? '-'),
                            $p->domicile_address ?? '-',
                            $p->occupation ?? '-',
                            $p->emergency_contact_name ?? '-',
                            $p->emergency_contact_relation ?? '-',
                            $p->emergency_contact_phone ?? '-',
                            $p->payment_type ?? 'Pribadi / Umum',
                            $p->insurance_company_name ?? '-',
                            $p->insurance_card_number ?? '-',
                            $visit->patient_service_status ?: ($p->patient_service_status ?? 'Pasien Baru'),
                            $visit->visit_purpose ?? 'Estetika',
                            $visit->destination_doctor_poli ?? '-',
                            $visit->chief_complaint ?? '-',
                            $visit->doctor_name_override ?: ($visit->doctor?->name ?? '-'),
                            $visit->therapist_nurse_name ?: ($visit->assistant_name ?? '-'),
                            $visit->diagnosis_wd ? "{$visit->diagnosis_wd} / {$visit->diagnosis_dd}" : ($ic->diagnosis_wd_dd ?? '-'),
                            $ic->action_name ?? '-',
                            $treatmentList ?: '-',
                            $bhpText ?: '-',
                            $beforeStatus,
                            $afterStatus,
                            strtoupper($visit->status),
                            $visit->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }
}
