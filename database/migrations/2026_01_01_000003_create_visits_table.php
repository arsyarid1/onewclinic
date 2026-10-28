<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('nurse_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('assistant_name')->nullable();
            
            $table->enum('status', [
                'registered',
                'consent_signed',
                'waiting_doctor',
                'in_treatment',
                'completed'
            ])->default('registered');

            $table->boolean('consent_signed')->default(false);
            $table->string('consent_signature_path')->nullable();
            $table->timestamp('consent_signed_at')->nullable();

            $table->string('photo_before_path')->nullable();
            $table->string('photo_after_path')->nullable();

            $table->enum('trustmedis_sync_status', [
                'pending',
                'synced',
                'failed'
            ])->default('pending');
            $table->json('trustmedis_payload')->nullable();
            $table->timestamp('trustmedis_synced_at')->nullable();

            $table->timestamps();

            $table->index(['clinic_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
