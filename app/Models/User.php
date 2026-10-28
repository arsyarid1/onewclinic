<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'clinic_id',
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctorVisits(): HasMany
    {
        return $this->hasMany(Visit::class, 'doctor_id');
    }

    public function nurseVisits(): HasMany
    {
        return $this->hasMany(Visit::class, 'nurse_id');
    }

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist';
    }

    public function isNurse(): bool
    {
        return $this->role === 'nurse';
    }

    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }
}
