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
        'doctor_id',
        'nurse_id',
        'assistant_name',
        'status',
        'consent_signed',
        'consent_signature_path',
        'consent_signed_at',
        'photo_before_path',
        'photo_after_path',
        'trustmedis_sync_status',
        'trustmedis_payload',
        'trustmedis_synced_at',
    ];

    protected function casts(): array
    {
        return [
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

    public function getConsentSignatureUrlAttribute(): ?string
    {
        return $this->consent_signature_path ? Storage::disk('public')->url($this->consent_signature_path) : null;
    }

    public function getPhotoBeforeUrlAttribute(): ?string
    {
        return $this->photo_before_path ? Storage::disk('public')->url($this->photo_before_path) : null;
    }

    public function getPhotoAfterUrlAttribute(): ?string
    {
        return $this->photo_after_path ? Storage::disk('public')->url($this->photo_after_path) : null;
    }
}
