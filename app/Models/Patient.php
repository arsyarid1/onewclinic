<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_code',
        'name',
        'nik',
        'phone_number',
        'email',
        'gender',
        'birth_place',
        'date_of_birth',
        'address',
        'emergency_contact_name',
        'emergency_contact_relation',
        'emergency_contact_phone',
        'whatsapp_reminder',
        'promo_consent',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'whatsapp_reminder' => 'boolean',
            'promo_consent' => 'boolean',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }
}
