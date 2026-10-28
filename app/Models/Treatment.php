<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Treatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'name',
        'category',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function visits(): BelongsToMany
    {
        return $this->belongsToMany(Visit::class, 'visit_treatments')
            ->withPivot('notes')
            ->withTimestamps();
    }

    public function visitTreatments(): HasMany
    {
        return $this->hasMany(VisitTreatment::class);
    }
}
