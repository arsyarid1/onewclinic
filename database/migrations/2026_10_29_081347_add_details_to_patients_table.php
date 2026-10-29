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
            $table->string('nik', 20)->nullable()->after('name');
            $table->string('birth_place')->nullable()->after('gender');
            $table->string('email')->nullable()->after('phone_number');
            $table->string('emergency_contact_name')->nullable()->after('address');
            $table->string('emergency_contact_relation')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_relation');
            $table->boolean('whatsapp_reminder')->default(true)->after('emergency_contact_phone');
            $table->boolean('promo_consent')->default(false)->after('whatsapp_reminder');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'birth_place',
                'email',
                'emergency_contact_name',
                'emergency_contact_relation',
                'emergency_contact_phone',
                'whatsapp_reminder',
                'promo_consent',
            ]);
        });
    }
};
