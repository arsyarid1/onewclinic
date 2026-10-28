<?php

namespace App\Services;

use App\Contracts\TrustmedisServiceInterface;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MockTrustmedisService implements TrustmedisServiceInterface
{
    /**
     * Synchronize visit record with mock Trustmedis API.
     */
    public function syncVisit(Visit $visit): bool
    {
        $visit->loadMissing([
            'clinic',
            'patient',
            'doctor',
            'nurse',
            'treatments',
            'visitTreatments.treatment'
        ]);

        // Helper to convert storage file to base64 preview if desired for EHR interoperability
        $getPhotoBase64 = function (?string $path): ?string {
            if ($path && Storage::disk('public')->exists($path)) {
                $content = Storage::disk('public')->get($path);
                $mime = Storage::disk('public')->mimeType($path) ?: 'image/jpeg';
                return 'data:' . $mime . ';base64,' . base64_encode($content);
            }
            return null;
        };

        $treatmentsData = $visit->treatments->map(function ($treatment) {
            return [
                'treatment_id' => $treatment->id,
                'name' => $treatment->name,
                'category' => $treatment->category,
                'price' => (float) $treatment->price,
                'clinical_notes' => $treatment->pivot->notes ?? null,
            ];
        })->toArray();

        // Build Trustmedis EHR integration payload
        $payload = [
            'meta' => [
                'system' => 'Trustmedis EHR Integration Gateway',
                'version' => 'v2.4-mock',
                'synced_at' => Carbon::now()->toIso8601String(),
                'transaction_id' => 'TM-' . strtoupper(uniqid()),
            ],
            'clinic' => [
                'clinic_id' => $visit->clinic_id,
                'clinic_code' => $visit->clinic?->clinic_code,
                'clinic_name' => $visit->clinic?->name,
                'clinic_phone' => $visit->clinic?->phone,
            ],
            'patient' => [
                'patient_id' => $visit->patient_id,
                'medical_record_number' => $visit->patient?->patient_code,
                'full_name' => $visit->patient?->name,
                'phone_number' => $visit->patient?->phone_number,
                'gender' => $visit->patient?->gender,
                'date_of_birth' => $visit->patient?->date_of_birth?->format('Y-m-d'),
                'address' => $visit->patient?->address,
            ],
            'encounter' => [
                'visit_id' => $visit->id,
                'status' => $visit->status,
                'doctor' => [
                    'id' => $visit->doctor_id,
                    'name' => $visit->doctor?->name,
                    'email' => $visit->doctor?->email,
                ],
                'nurse' => [
                    'id' => $visit->nurse_id,
                    'name' => $visit->nurse?->name,
                ],
                'assistant_name' => $visit->assistant_name,
                'consent' => [
                    'signed' => (bool) $visit->consent_signed,
                    'signed_at' => $visit->consent_signed_at?->toIso8601String(),
                    'signature_path' => $visit->consent_signature_path,
                ],
                'treatments' => $treatmentsData,
                'media' => [
                    'photo_before' => [
                        'path' => $visit->photo_before_path,
                        'url' => $visit->photo_before_url,
                        'base64_available' => !empty($visit->photo_before_path),
                    ],
                    'photo_after' => [
                        'path' => $visit->photo_after_path,
                        'url' => $visit->photo_after_url,
                        'base64_available' => !empty($visit->photo_after_path),
                    ],
                ],
            ],
        ];

        // Update model with sync payload and status
        $visit->update([
            'trustmedis_sync_status' => 'synced',
            'trustmedis_payload' => $payload,
            'trustmedis_synced_at' => Carbon::now(),
        ]);

        Log::info("Visit #{$visit->id} synced to mock Trustmedis", [
            'visit_id' => $visit->id,
            'patient_code' => $visit->patient?->patient_code,
            'transaction_id' => $payload['meta']['transaction_id'],
        ]);

        return true;
    }
}
