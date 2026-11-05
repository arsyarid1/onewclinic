<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Consumable;
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
            if (! $path) {
                return null;
            }
            if (str_starts_with($path, 'data:image')) {
                return $path;
            }

            // Normalize path (handle Windows backslashes, leading slashes, storage/ prefix)
            $cleanPath = str_replace('\\', '/', $path);
            $cleanPath = preg_replace('#^/?storage/#', '', $cleanPath);
            $cleanPath = ltrim($cleanPath, '/');

            // 1. Try Storage disk 'public'
            if (Storage::disk('public')->exists($cleanPath)) {
                $content = Storage::disk('public')->get($cleanPath);
                $mime = Storage::disk('public')->mimeType($cleanPath) ?: 'image/jpeg';

                return 'data:'.$mime.';base64,'.base64_encode($content);
            }

            // 2. Try storage_path('app/public/' . $cleanPath)
            $storageFile = storage_path('app/public/'.$cleanPath);
            if (file_exists($storageFile)) {
                $content = file_get_contents($storageFile);
                $mime = mime_content_type($storageFile) ?: 'image/jpeg';

                return 'data:'.$mime.';base64,'.base64_encode($content);
            }

            // 3. Try public_path('storage/' . $cleanPath)
            $publicFile = public_path('storage/'.$cleanPath);
            if (file_exists($publicFile)) {
                $content = file_get_contents($publicFile);
                $mime = mime_content_type($publicFile) ?: 'image/jpeg';

                return 'data:'.$mime.';base64,'.base64_encode($content);
            }

            // 4. Try direct file_exists
            if (file_exists($path)) {
                $content = file_get_contents($path);
                $mime = mime_content_type($path) ?: 'image/jpeg';

                return 'data:'.$mime.';base64,'.base64_encode($content);
            }

            return null;
        };

        // Prefer official SVG logo as seen in dashboard
        $logoBase64 = null;
        if (file_exists(public_path('logo.svg'))) {
            $logoContent = file_get_contents(public_path('logo.svg'));
            $logoBase64 = 'data:image/svg+xml;base64,'.base64_encode($logoContent);
        } elseif (file_exists(public_path('icon.png'))) {
            $logoContent = file_get_contents(public_path('icon.png'));
            $logoBase64 = 'data:image/png;base64,'.base64_encode($logoContent);
        } elseif (file_exists(public_path('apple-touch-icon.png'))) {
            $logoContent = file_get_contents(public_path('apple-touch-icon.png'));
            $logoBase64 = 'data:image/png;base64,'.base64_encode($logoContent);
        } elseif ($clinic?->logo_path && Storage::disk('public')->exists($clinic->logo_path)) {
            $logoBase64 = $toBase64($clinic->logo_path);
        }

        $signatureBase64 = $toBase64($visit->consent_signature_path);
        $procedureConsentSigBase64 = $toBase64($visit->informedConsent?->patient_signature_path);
        $doctorConsentSigBase64 = $toBase64($visit->informedConsent?->doctor_signature_path);
        $witnessConsentSigBase64 = $toBase64($visit->informedConsent?->witness_signature_path);
        $registrationPatientSigBase64 = $toBase64($visit->registration_patient_signature_path);
        $registrationStaffSigBase64 = $toBase64($visit->registration_staff_signature_path);
        $generalConsentStaffSigBase64 = $toBase64($visit->general_consent_staff_signature_path ?: $visit->registration_staff_signature_path);

        $photoBeforeFrontBase64 = $toBase64($visit->photo_before_front ?: $visit->photo_before_path);
        $photoBeforeRightTiltBase64 = $toBase64($visit->photo_before_right_tilt);
        $photoBeforeRightSideBase64 = $toBase64($visit->photo_before_right_side);
        $photoBeforeLeftTiltBase64 = $toBase64($visit->photo_before_left_tilt);
        $photoBeforeLeftSideBase64 = $toBase64($visit->photo_before_left_side);
        $photoBeforeBase64 = $photoBeforeFrontBase64 ?: $toBase64($visit->photo_before_path);

        $photoAfterFrontBase64 = $toBase64($visit->photo_after_front ?: $visit->photo_after_path);
        $photoAfterRightTiltBase64 = $toBase64($visit->photo_after_right_tilt);
        $photoAfterRightSideBase64 = $toBase64($visit->photo_after_right_side);
        $photoAfterLeftTiltBase64 = $toBase64($visit->photo_after_left_tilt);
        $photoAfterLeftSideBase64 = $toBase64($visit->photo_after_left_side);
        $photoAfterBase64 = $photoAfterFrontBase64 ?: $toBase64($visit->photo_after_path);

        $consumables = Consumable::orderBy('id')->get();

        $data = [
            'clinic' => $clinic,
            'visit' => $visit,
            'patient' => $visit->patient,
            'consumables' => $consumables,
            'logoBase64' => $logoBase64,
            'signatureBase64' => $signatureBase64,
            'procedureConsentSigBase64' => $procedureConsentSigBase64,
            'doctorConsentSigBase64' => $doctorConsentSigBase64,
            'witnessConsentSigBase64' => $witnessConsentSigBase64,
            'registrationPatientSigBase64' => $registrationPatientSigBase64,
            'registrationStaffSigBase64' => $registrationStaffSigBase64,
            'generalConsentStaffSigBase64' => $generalConsentStaffSigBase64,
            'photoBeforeBase64' => $photoBeforeBase64,
            'photoAfterBase64' => $photoAfterBase64,
            'photoBeforeFrontBase64' => $photoBeforeFrontBase64,
            'photoBeforeRightTiltBase64' => $photoBeforeRightTiltBase64,
            'photoBeforeRightSideBase64' => $photoBeforeRightSideBase64,
            'photoBeforeLeftTiltBase64' => $photoBeforeLeftTiltBase64,
            'photoBeforeLeftSideBase64' => $photoBeforeLeftSideBase64,
            'photoAfterFrontBase64' => $photoAfterFrontBase64,
            'photoAfterRightTiltBase64' => $photoAfterRightTiltBase64,
            'photoAfterRightSideBase64' => $photoAfterRightSideBase64,
            'photoAfterLeftTiltBase64' => $photoAfterLeftTiltBase64,
            'photoAfterLeftSideBase64' => $photoAfterLeftSideBase64,
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
