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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('inbody_id', 50)->nullable()->after('patient_code');
            $table->string('nik_passport', 50)->nullable()->after('name');
            $table->string('marital_status', 30)->nullable()->after('gender');
            $table->text('id_address')->nullable()->after('address');
            $table->text('domicile_address')->nullable()->after('id_address');
            $table->string('occupation', 100)->nullable()->after('email');
            $table->string('payment_type', 50)->nullable()->default('Pribadi / Umum');
            $table->string('insurance_company_name', 150)->nullable();
            $table->string('insurance_card_number', 100)->nullable();
            $table->string('patient_service_status', 50)->nullable()->default('Pasien Baru');
            $table->string('referral_source', 100)->nullable();
            $table->string('referral_source_other', 150)->nullable();
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->string('manual_patient_code', 50)->nullable()->after('patient_id');
            $table->string('inbody_id', 50)->nullable()->after('manual_patient_code');
            $table->date('registration_date')->nullable()->after('inbody_id');
            $table->string('visit_purpose', 100)->nullable();
            $table->string('destination_doctor_poli', 150)->nullable();
            $table->text('chief_complaint')->nullable();
            $table->boolean('whatsapp_comm_consent')->default(true);

            // Tanda Tangan Formulir Pendaftaran (Hal 1-2)
            $table->string('registration_patient_signature_path')->nullable();
            $table->string('registration_patient_signer_name')->nullable();
            $table->string('registration_staff_signature_path')->nullable();
            $table->string('registration_staff_signer_name')->nullable();
            $table->timestamp('registration_staff_signed_at')->nullable();

            // General Consent Staff & Pilihan Pasien (Hal 3-4)
            $table->string('general_consent_staff_name')->nullable();
            $table->string('general_consent_staff_title')->nullable()->default('Petugas Pendaftaran');
            $table->string('general_consent_staff_signature_path')->nullable();
            $table->string('general_consent_patient_relation')->nullable()->default('Diri Sendiri');
            $table->string('general_consent_patient_signer_name')->nullable();
            $table->boolean('general_consent_whatsapp_reminder')->default(true);
            $table->boolean('general_consent_promo_info')->default(false);
            $table->string('general_consent_insurance_disclosure', 20)->default('Ya');
            $table->boolean('general_consent_third_party_info')->default(false);
            $table->string('general_consent_third_party_name')->nullable();
            $table->string('general_consent_third_party_phone')->nullable();
            $table->text('general_consent_third_party_notes')->nullable();

            // Foto Before 5 Sisi Wajah
            $table->string('photo_before_front')->nullable();
            $table->string('photo_before_right_tilt')->nullable();
            $table->string('photo_before_right_side')->nullable();
            $table->string('photo_before_left_tilt')->nullable();
            $table->string('photo_before_left_side')->nullable();

            // Foto After 5 Sisi Wajah
            $table->string('photo_after_front')->nullable();
            $table->string('photo_after_right_tilt')->nullable();
            $table->string('photo_after_right_side')->nullable();
            $table->string('photo_after_left_tilt')->nullable();
            $table->string('photo_after_left_side')->nullable();

            // BHP Harian Header & Custom Treatments
            $table->string('therapist_nurse_name')->nullable();
            $table->string('doctor_name_override')->nullable();
            $table->json('custom_treatment_list')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn([
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
                'therapist_nurse_name',
                'doctor_name_override',
                'custom_treatment_list',
            ]);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'inbody_id',
                'nik_passport',
                'marital_status',
                'id_address',
                'domicile_address',
                'occupation',
                'payment_type',
                'insurance_company_name',
                'insurance_card_number',
                'patient_service_status',
                'referral_source',
                'referral_source_other',
            ]);
        });
    }
};
