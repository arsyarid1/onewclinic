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
        // 1. Master Bahan Baku / BHP (Barang Habis Pakai)
        Schema::create('consumables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->string('name');
            $table->string('category')->nullable()->default('BHP');
            $table->string('unit')->default('pcs');
            $table->integer('default_quantity')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Pivot BHP yang dipakai per kunjungan pasien
        Schema::create('visit_consumables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('visits')->cascadeOnDelete();
            $table->foreignId('consumable_id')->constrained('consumables')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        // 3. Persetujuan Tindakan Kedokteran (Informed Consent Form Resmi ONEW)
        Schema::create('informed_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('visits')->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();

            // Identitas Pemberi & Penerima Informasi
            $table->string('information_provider')->nullable(); // Dokter / Perawat pemeriksa
            $table->string('recipient_name')->nullable();       // Nama yang bertandatangan
            $table->string('recipient_age')->nullable();
            $table->string('recipient_gender')->nullable();
            $table->string('recipient_relation')->default('Diri Sendiri'); // Hubungan dengan pasien
            $table->text('recipient_address')->nullable();

            // Identitas Pasien Penerima Tindakan
            $table->string('target_patient_name')->nullable();
            $table->string('target_patient_age')->nullable();
            $table->string('target_patient_gender')->nullable();
            $table->text('target_patient_address')->nullable();
            $table->string('action_name')->nullable(); // Tindakan medis yang disetujui

            // Tabel 11 Poin Informasi Medis
            $table->text('diagnosis_wd_dd')->nullable();
            $table->boolean('diagnosis_checked')->default(true);

            $table->text('diagnosis_basis')->nullable();
            $table->boolean('diagnosis_basis_checked')->default(true);

            $table->text('medical_procedure')->nullable();
            $table->boolean('medical_procedure_checked')->default(true);

            $table->text('indication')->nullable();
            $table->boolean('indication_checked')->default(true);

            $table->text('procedure_steps')->nullable();
            $table->boolean('procedure_steps_checked')->default(true);

            $table->text('procedure_goal')->nullable();
            $table->boolean('procedure_goal_checked')->default(true);

            $table->text('risks')->nullable();
            $table->boolean('risks_checked')->default(true);

            $table->text('complications')->nullable();
            $table->boolean('complications_checked')->default(true);

            $table->text('prognosis')->nullable();
            $table->boolean('prognosis_checked')->default(true);

            $table->text('alternatives_risks')->nullable();
            $table->boolean('alternatives_risks_checked')->default(true);

            $table->text('other_notes')->nullable();
            $table->boolean('other_notes_checked')->default(false);

            // Tanda Tangan & Persetujuan
            $table->boolean('doctor_signed')->default(false);
            $table->string('doctor_signature_path')->nullable();
            $table->timestamp('doctor_signed_at')->nullable();

            $table->boolean('patient_signed')->default(false);
            $table->string('patient_signature_path')->nullable();
            $table->timestamp('patient_signed_at')->nullable();

            $table->string('witness_name')->nullable();
            $table->string('witness_signature_path')->nullable();

            $table->string('consent_city')->default('Jakarta');
            $table->date('consent_date')->nullable();
            $table->string('consent_time')->nullable();
            $table->boolean('is_agreed')->default(true);

            $table->timestamps();
        });

        // 4. Diagnosis & Catatan Dokter pada Visits
        Schema::table('visits', function (Blueprint $table) {
            $table->string('diagnosis_wd')->nullable()->after('assistant_name');
            $table->string('diagnosis_dd')->nullable()->after('diagnosis_wd');
            $table->text('doctor_notes')->nullable()->after('diagnosis_dd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn(['diagnosis_wd', 'diagnosis_dd', 'doctor_notes']);
        });

        Schema::dropIfExists('informed_consents');
        Schema::dropIfExists('visit_consumables');
        Schema::dropIfExists('consumables');
    }
};
