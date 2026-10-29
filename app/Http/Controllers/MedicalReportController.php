<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Visit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class MedicalReportController extends Controller
{
    /**
     * Generate and stream Medical Summary Report PDF.
     */
    public function downloadPdf(Visit $visit): Response
    {
        $visit->loadMissing([
            'clinic',
            'patient',
            'doctor',
            'nurse',
            'treatments',
            'visitTreatments.treatment',
            'visitConsumables.consumable',
            'informedConsent',
        ]);

        $clinic = $visit->clinic ?? Clinic::first();

        // Helper to convert storage file to Base64 for guaranteed DomPDF rendering
        $toBase64 = function (?string $path): ?string {
            if ($path && Storage::disk('public')->exists($path)) {
                $content = Storage::disk('public')->get($path);
                $mime = Storage::disk('public')->mimeType($path) ?: 'image/jpeg';
                return 'data:' . $mime . ';base64,' . base64_encode($content);
            }
            return null;
        };

        // Prefer clean icon emblem in public (icon.png without text) as requested by user
        $logoBase64 = null;
        if (file_exists(public_path('icon.png'))) {
            $logoContent = file_get_contents(public_path('icon.png'));
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoContent);
        } elseif ($clinic?->logo_path && Storage::disk('public')->exists($clinic->logo_path)) {
            $logoBase64 = $toBase64($clinic->logo_path);
        }

        $signatureBase64 = $toBase64($visit->consent_signature_path);
        $procedureConsentSigBase64 = $toBase64($visit->informedConsent?->patient_signature_path);
        $photoBeforeBase64 = $toBase64($visit->photo_before_path);
        $photoAfterBase64 = $toBase64($visit->photo_after_path);

        $data = [
            'clinic' => $clinic,
            'visit' => $visit,
            'patient' => $visit->patient,
            'logoBase64' => $logoBase64,
            'signatureBase64' => $signatureBase64,
            'procedureConsentSigBase64' => $procedureConsentSigBase64,
            'photoBeforeBase64' => $photoBeforeBase64,
            'photoAfterBase64' => $photoAfterBase64,
            'generatedAt' => now(),
        ];

        $pdf = Pdf::loadView('reports.medical-summary-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);

        $safeCode = str_replace('/', '-', $visit->patient?->patient_code ?? 'PASIEN');
        $fileName = "Rekap-Medis-{$safeCode}-Visit-{$visit->id}.pdf";

        return $pdf->stream($fileName);
    }
}
