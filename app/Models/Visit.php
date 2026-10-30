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
        'diagnosis_wd',
        'diagnosis_dd',
        'doctor_notes',
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

    public function getConsentSignatureUrlAttribute(): ?string
    {
        if (!$this->consent_signature_path) {
            return null;
        }
        if (str_starts_with($this->consent_signature_path, 'http://') || str_starts_with($this->consent_signature_path, 'https://') || str_starts_with($this->consent_signature_path, 'data:')) {
            return $this->consent_signature_path;
        }
        $cleanPath = ltrim(preg_replace('#^/?storage/#', '', $this->consent_signature_path), '/');
        return '/storage/' . $cleanPath;
    }

    public function getPhotoBeforeUrlAttribute(): ?string
    {
        if (!$this->photo_before_path) {
            return null;
        }
        if (str_starts_with($this->photo_before_path, 'http://') || str_starts_with($this->photo_before_path, 'https://') || str_starts_with($this->photo_before_path, 'data:')) {
            return $this->photo_before_path;
        }
        $cleanPath = ltrim(preg_replace('#^/?storage/#', '', $this->photo_before_path), '/');
        return '/storage/' . $cleanPath;
    }

    public function getPhotoAfterUrlAttribute(): ?string
    {
        if (!$this->photo_after_path) {
            return null;
        }
        if (str_starts_with($this->photo_after_path, 'http://') || str_starts_with($this->photo_after_path, 'https://') || str_starts_with($this->photo_after_path, 'data:')) {
            return $this->photo_after_path;
        }
        $cleanPath = ltrim(preg_replace('#^/?storage/#', '', $this->photo_after_path), '/');
        return '/storage/' . $cleanPath;
    }
}
