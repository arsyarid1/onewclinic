<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'manual_patient_code',
        'inbody_id',
        'registration_date',
        'visit_purpose',
        'destination_doctor_poli',
        'chief_complaint',
        'whatsapp_comm_consent',
        'registration_patient_signature_path',
        'registration_patient_signer_name',
        'registration_staff_signature_path',
        'registration_staff_signer_name',
        'registration_staff_signed_at',
        'general_consent_staff_name',
        'general_consent_staff_title',
        'general_consent_staff_signature_path',
        'general_consent_patient_relation',
        'general_consent_patient_signer_name',
        'general_consent_whatsapp_reminder',
        'general_consent_promo_info',
        'general_consent_insurance_disclosure',
        'general_consent_third_party_info',
        'general_consent_third_party_name',
        'general_consent_third_party_phone',
        'general_consent_third_party_notes',
        'doctor_id',
        'nurse_id',
        'assistant_name',
        'therapist_nurse_name',
        'doctor_name_override',
        'custom_treatment_list',
        'diagnosis_wd',
        'diagnosis_dd',
        'doctor_notes',
        'status',
        'consent_signed',
        'consent_signature_path',
        'consent_signed_at',
        'photo_before_path',
        'photo_after_path',
        'photo_before_front',
        'photo_before_right_tilt',
        'photo_before_right_side',
        'photo_before_left_tilt',
        'photo_before_left_side',
        'photo_after_front',
        'photo_after_right_tilt',
        'photo_after_right_side',
        'photo_after_left_tilt',
        'photo_after_left_side',
        'trustmedis_sync_status',
        'trustmedis_payload',
        'trustmedis_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'registration_date' => 'date',
            'registration_staff_signed_at' => 'datetime',
            'whatsapp_comm_consent' => 'boolean',
            'general_consent_whatsapp_reminder' => 'boolean',
            'general_consent_promo_info' => 'boolean',
            'general_consent_third_party_info' => 'boolean',
            'custom_treatment_list' => 'array',
            'consent_signed' => 'boolean',
            'consent_signed_at' => 'datetime',
            'trustmedis_payload' => 'array',
            'trustmedis_synced_at' => 'datetime',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function nurse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    public function treatments(): BelongsToMany
    {
        return $this->belongsToMany(Treatment::class, 'visit_treatments')
            ->withPivot('notes')
            ->withTimestamps();
    }

    public function visitTreatments(): HasMany
    {
        return $this->hasMany(VisitTreatment::class);
    }

    public function informedConsent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(InformedConsent::class);
    }

    public function visitConsumables(): HasMany
    {
        return $this->hasMany(VisitConsumable::class);
    }

    public function consumables(): BelongsToMany
    {
        return $this->belongsToMany(Consumable::class, 'visit_consumables')
            ->withPivot(['quantity', 'notes'])
            ->withTimestamps();
    }

    public static function formatPublicUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return $path;
        }
        $cleanPath = ltrim(preg_replace('#^/?storage/#', '', $path), '/');
        
        // Verify file existence on disk to avoid broken image links
        if (Storage::disk('public')->exists($cleanPath) || file_exists(public_path('storage/' . $cleanPath))) {
            return '/storage/' . $cleanPath;
        }
        
        return null;
    }

    public function getConsentSignatureUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->consent_signature_path);
    }

    public function getRegistrationPatientSignatureUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->registration_patient_signature_path);
    }

    public function getRegistrationStaffSignatureUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->registration_staff_signature_path);
    }

    public function getGeneralConsentStaffSignatureUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->general_consent_staff_signature_path);
    }

    public function getPhotoBeforeUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->photo_before_path)
            ?: static::formatPublicUrl($this->photo_before_front)
            ?: static::formatPublicUrl($this->photo_before_right_tilt)
            ?: static::formatPublicUrl($this->photo_before_left_tilt)
            ?: static::formatPublicUrl($this->photo_before_right_side)
            ?: static::formatPublicUrl($this->photo_before_left_side);
    }

    public function getPhotoAfterUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->photo_after_path)
            ?: static::formatPublicUrl($this->photo_after_front)
            ?: static::formatPublicUrl($this->photo_after_right_tilt)
            ?: static::formatPublicUrl($this->photo_after_left_tilt)
            ?: static::formatPublicUrl($this->photo_after_right_side)
            ?: static::formatPublicUrl($this->photo_after_left_side);
    }

    public function getPhotoBeforeFrontUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->photo_before_front);
    }

    public function getPhotoBeforeRightTiltUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->photo_before_right_tilt);
    }

    public function getPhotoBeforeRightSideUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->photo_before_right_side);
    }

    public function getPhotoBeforeLeftTiltUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->photo_before_left_tilt);
    }

    public function getPhotoBeforeLeftSideUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->photo_before_left_side);
    }

    public function getPhotoAfterFrontUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->photo_after_front);
    }

    public function getPhotoAfterRightTiltUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->photo_after_right_tilt);
    }

    public function getPhotoAfterRightSideUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->photo_after_right_side);
    }

    public function getPhotoAfterLeftTiltUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->photo_after_left_tilt);
    }

    public function getPhotoAfterLeftSideUrlAttribute(): ?string
    {
        return static::formatPublicUrl($this->photo_after_left_side);
    }

    public function getHasFiveSidesBeforeAttribute(): bool
    {
        return !empty($this->photo_before_right_tilt_url)
            || !empty($this->photo_before_right_side_url)
            || !empty($this->photo_before_left_tilt_url)
            || !empty($this->photo_before_left_side_url);
    }

    public function getHasFiveSidesAfterAttribute(): bool
    {
        return !empty($this->photo_after_right_tilt_url)
            || !empty($this->photo_after_right_side_url)
            || !empty($this->photo_after_left_tilt_url)
            || !empty($this->photo_after_left_side_url);
    }
}
