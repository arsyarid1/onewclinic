<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PatientCodeGenerator
{
    /**
     * Generate unique patient code with format: [CODE][YY]-[000001]
     * Example: ONEW26-000001
     *
     * Uses DB transaction and lockForUpdate() to prevent race conditions.
     */
    public function generate(Clinic $clinic, ?Carbon $date = null): string
    {
        $date = $date ?? Carbon::now();
        $year2Digit = $date->format('y'); // e.g. "26"
        $clinicCode = strtoupper(trim($clinic->clinic_code)); // e.g. "ONEW"
        $prefix = "{$clinicCode}{$year2Digit}"; // e.g. "ONEW26"

        return DB::transaction(function () use ($clinic, $prefix) {
            // Find the latest patient code for this clinic and current year prefix with lockForUpdate()
            $latestPatient = Patient::where('clinic_id', $clinic->id)
                ->where('patient_code', 'LIKE', "{$prefix}-%")
                ->lockForUpdate()
                ->orderBy('patient_code', 'desc')
                ->first();

            $nextNumber = 1;

            if ($latestPatient && !empty($latestPatient->patient_code)) {
                // Format: ONEW26-000001 -> parts[1] is 000001
                $parts = explode('-', $latestPatient->patient_code);
                if (isset($parts[1]) && is_numeric($parts[1])) {
                    $nextNumber = ((int) $parts[1]) + 1;
                }
            }

            $paddedSequence = str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);

            return "{$prefix}-{$paddedSequence}";
        });
    }
}
