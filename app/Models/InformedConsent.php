<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InformedConsent extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'doctor_id',
        'information_provider',
        'recipient_name',
        'recipient_age',
        'recipient_gender',
        'recipient_relation',
        'recipient_address',
        'target_patient_name',
        'target_patient_age',
        'target_patient_gender',
        'target_patient_address',
        'action_name',

        'diagnosis_wd_dd',
        'diagnosis_checked',
        'diagnosis_basis',
        'diagnosis_basis_checked',
        'medical_procedure',
        'medical_procedure_checked',
        'indication',
        'indication_checked',
        'procedure_steps',
        'procedure_steps_checked',
        'procedure_goal',
        'procedure_goal_checked',
        'risks',
        'risks_checked',
        'complications',
        'complications_checked',
        'prognosis',
        'prognosis_checked',
        'alternatives_risks',
        'alternatives_risks_checked',
        'other_notes',
        'other_notes_checked',

        'doctor_signed',
        'doctor_signature_path',
        'doctor_signed_at',
        'patient_signed',
        'patient_signature_path',
        'patient_signed_at',
        'witness_name',
        'witness_signature_path',
        'consent_city',
        'consent_date',
        'consent_time',
        'is_agreed',
    ];

    protected function casts(): array
    {
        return [
            'diagnosis_checked' => 'boolean',
            'diagnosis_basis_checked' => 'boolean',
            'medical_procedure_checked' => 'boolean',
            'indication_checked' => 'boolean',
            'procedure_steps_checked' => 'boolean',
            'procedure_goal_checked' => 'boolean',
            'risks_checked' => 'boolean',
            'complications_checked' => 'boolean',
            'prognosis_checked' => 'boolean',
            'alternatives_risks_checked' => 'boolean',
            'other_notes_checked' => 'boolean',
            'doctor_signed' => 'boolean',
            'doctor_signed_at' => 'datetime',
            'patient_signed' => 'boolean',
            'patient_signed_at' => 'datetime',
            'consent_date' => 'date',
            'is_agreed' => 'boolean',
        ];
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
