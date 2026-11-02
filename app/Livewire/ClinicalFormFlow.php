<?php

namespace App\Livewire;

use App\Models\Clinic;
use App\Models\Consumable;
use App\Models\InformedConsent;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitConsumable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.form-pwa')]
class ClinicalFormFlow extends Component
{
    use WithFileUploads;

    // Active Step (1 - 7)
    #[Url(as: 'step')]
    public int $currentStep = 1;

    #[Url(as: 'visit_id')]
    public ?int $visitId = null;

    public ?int $patientId = null;

    // ==========================================
    // STEP 1: FORMULIR PENDAFTARAN PASIEN (Hal 1 & 2)
    // ==========================================
    public string $manual_patient_code = ''; // Ditulis Manual
    public string $registration_date = '';
    public string $inbody_id = ''; // ID Inbody dari alat kesehatan

    // Identitas Pasien
    public string $name = '';
    public string $nik_passport = '';
    public string $birth_place = '';
    public string $date_of_birth = '';
    public string $gender = 'Perempuan'; // Laki-laki / Perempuan
    public string $marital_status = 'Belum Menikah'; // Belum Menikah / Menikah / Cerai
    public string $id_address = '';
    public bool $is_domicile_same = true;
    public string $domicile_address = '';
    public string $phone_number = '';
    public string $email = '';
    public string $occupation = '';

    // Kontak Darurat / Penanggung Jawab
    public string $emergency_name = '';
    public string $emergency_relation = 'Keluarga';
    public string $emergency_phone = '';

    // Data Penjamin / Pembayaran
    public string $payment_type = 'Pribadi / Umum';
    public string $insurance_company_name = '';
    public string $insurance_card_number = '';

    // Informasi Pelayanan
    public string $patient_service_status = 'Pasien Baru';
    public string $visit_purpose = 'Estetika';
    public string $destination_doctor_poli = '';
    public string $chief_complaint = '';

    // Informasi Tambahan
    public string $referral_source = 'Instagram / TikTok';
    public string $referral_source_other = '';

    // Pernyataan & Persetujuan
    public bool $whatsapp_comm_consent = true;
    public string $registration_patient_signature = '';
    public string $registration_patient_signer_name = '';
    public string $registration_staff_signature = '';
    public string $registration_staff_signer_name = 'Ns. Ratna Dewi, S.Kep';
    public string $registration_staff_signed_date = '';

    // ==========================================
    // STEP 2: GENERAL CONSENT (Hal 3 & 4)
    // ==========================================
    public bool $gc_whatsapp_reminder = true;
    public bool $gc_promo_info = false;
    public string $gc_insurance_disclosure = 'Ya';
    public bool $gc_third_party_info = false;
    public string $gc_third_party_name = '';
    public string $gc_third_party_phone = '';
    public string $gc_third_party_notes = '';

    public string $gc_city = 'Jakarta';
    public string $gc_date = '';
    public string $gc_time = '';
    public string $gc_patient_relation = 'Diri Sendiri';
    public string $gc_patient_signer_name = '';
    public string $gc_patient_signature = '';

    public string $gc_staff_name = 'Ns. Ratna Dewi, S.Kep';
    public string $gc_staff_title = 'Petugas Pendaftaran';
    public string $gc_staff_signature = '';

    // ==========================================
    // STEP 3: PERSETUJUAN TINDAKAN KEDOKTERAN (Hal 5)
    // ==========================================
    public string $ic_doctor_name = 'dr. Alana Wijaya, Sp.D.V.E';
    public string $ic_info_provider = 'dr. Alana Wijaya, Sp.D.V.E';
    public string $ic_recipient_name = '';

    // 11 Poin Informasi Medis
    public string $ic_diagnosis = '';
    public bool $ic_diagnosis_checked = true;
    public string $ic_diagnosis_basis = 'Pemeriksaan Klinis & Evaluasi Kondisi Kulit';
    public bool $ic_diagnosis_basis_checked = true;
    public string $ic_medical_procedure = 'Messo + Botox + Laser CO2 + Multivitamin Infuse';
    public bool $ic_medical_procedure_checked = true;
    public string $ic_indication = 'Peremajaan kulit, perbaikan tekstur & hiperpigmentasi';
    public bool $ic_indication_checked = true;
    public string $ic_procedure_steps = 'Asepsis, anestesi topikal 30-45 menit, tindakan klinis, post-treatment soothing';
    public bool $ic_procedure_steps_checked = true;
    public string $ic_procedure_goal = 'Meningkatkan elastisitas, kecerahan, dan regenerasi sel kulit';
    public bool $ic_procedure_goal_checked = true;
    public string $ic_risks = 'Kemerahan sementara (eritema), pembengkakan ringan, rasa hangat';
    public bool $ic_risks_checked = true;
    public string $ic_complications = 'Hematoma ringan, reaksi hipersensitivitas minimal';
    public bool $ic_complications_checked = true;
    public string $ic_prognosis = 'Dubia ad Bonam';
    public bool $ic_prognosis_checked = true;
    public string $ic_alternatives_risks = 'Perawatan topikal harian atau peeling kimiawi bertahap';
    public bool $ic_alternatives_risks_checked = true;
    public string $ic_other_notes = 'Gunakan sunscreen SPF 50+, hindari paparan sinar matahari langsung 3 hari';
    public bool $ic_other_notes_checked = false;

    // Tanda Tangan Informed Consent
    public string $ic_doctor_signature = '';
    public string $ic_recipient_signature = '';
    public string $ic_witness_signature = '';
    public string $ic_witness_name = 'Ns. Ratna Dewi, S.Kep';

    public string $ic_patient_age = '';
    public string $ic_patient_gender = 'Perempuan';
    public string $ic_patient_address = '';
    public string $ic_action_target_relation = 'saya';
    public string $ic_target_patient_name = '';

    // ==========================================
    // STEP 4: FOTO BEFORE 5 SISI WAJAH
    // ==========================================
    public $temp_photo_before_front;
    public $temp_photo_before_right_tilt;
    public $temp_photo_before_right_side;
    public $temp_photo_before_left_tilt;
    public $temp_photo_before_left_side;

    public ?string $photo_before_front_path = null;
    public ?string $photo_before_right_tilt_path = null;
    public ?string $photo_before_right_side_path = null;
    public ?string $photo_before_left_tilt_path = null;
    public ?string $photo_before_left_side_path = null;

    // ==========================================
    // STEP 5: TINDAKAN & BHP TINDAKAN HARIAN (Hal 6)
    // ==========================================
    public string $bhp_patient_name = '';
    public string $bhp_therapist_nurse = 'Ns. Ratna Dewi, S.Kep';
    public string $bhp_doctor_name = 'dr. Alana Wijaya, Sp.D.V.E';
    public string $bhp_date = '';

    // List Tindakan (Custom dynamic rows)
    public array $treatmentRows = [
        ['action' => 'Messo perut 1 Vial'],
        ['action' => 'Botox 50 unit'],
        ['action' => 'Laser CO2'],
        ['action' => 'Multivitamin Infus'],
        ['action' => 'Secretome 1'],
    ];

    // Master BHP List & Selected Quantities [consumable_id => quantity]
    public array $selectedBhpQuantities = [];
    public string $newCustomBhpName = '';
    public int $newCustomBhpQty = 1;

    // ==========================================
    // STEP 6: FOTO AFTER 5 SISI WAJAH
    // ==========================================
    public $temp_photo_after_front;
    public $temp_photo_after_right_tilt;
    public $temp_photo_after_right_side;
    public $temp_photo_after_left_tilt;
    public $temp_photo_after_left_side;

    public ?string $photo_after_front_path = null;
    public ?string $photo_after_right_tilt_path = null;
    public ?string $photo_after_right_side_path = null;
    public ?string $photo_after_left_tilt_path = null;
    public ?string $photo_after_left_side_path = null;

    public function mount(?int $visit_id = null): void
    {
        $today = Carbon::today()->format('Y-m-d');
        $this->registration_date = $today;
        $this->registration_staff_signed_date = $today;
        $this->gc_date = $today;
        $this->gc_time = Carbon::now()->format('H:i');
        $this->bhp_date = Carbon::now()->translatedFormat('d F Y');

        // Check if loading existing visit
        $targetVisitId = $visit_id ?: $this->visitId;
        if ($targetVisitId) {
            $this->loadExistingVisit((int) $targetVisitId);
        } else {
            // Default sample number format placeholder
            $this->manual_patient_code = 'ONEW260000' . rand(10, 99);
        }

        // Initialize default consumables quantity from database
        $this->initBhpQuantities();
    }

    public function initBhpQuantities(): void
    {
        $consumables = Consumable::where('is_active', true)->get();
        foreach ($consumables as $c) {
            if (!isset($this->selectedBhpQuantities[$c->id])) {
                // Initialize default quantities for common items like on paper
                if (str_contains($c->name, 'SPONGE')) $this->selectedBhpQuantities[$c->id] = 2;
                elseif (str_contains($c->name, 'HAIR CUP')) $this->selectedBhpQuantities[$c->id] = 1;
                elseif (str_contains($c->name, 'CLEANSING')) $this->selectedBhpQuantities[$c->id] = 3;
                elseif (str_contains($c->name, 'FACIAL WASH')) $this->selectedBhpQuantities[$c->id] = 3;
                elseif (str_contains($c->name, 'TONER')) $this->selectedBhpQuantities[$c->id] = 3;
                elseif (str_contains($c->name, 'KAPAS')) $this->selectedBhpQuantities[$c->id] = 10;
                elseif (str_contains($c->name, 'MASKER ANESTESI')) $this->selectedBhpQuantities[$c->id] = 1;
                elseif (str_contains($c->name, 'MASKER WAJAH')) $this->selectedBhpQuantities[$c->id] = 1;
                elseif (str_contains($c->name, 'ANTI IRITASI')) $this->selectedBhpQuantities[$c->id] = 3;
                elseif (str_contains($c->name, 'SPUIT 1CC')) $this->selectedBhpQuantities[$c->id] = 3;
                elseif (str_contains($c->name, 'SPUIT 3CC')) $this->selectedBhpQuantities[$c->id] = 1;
                elseif (str_contains($c->name, 'SPUIT 5CC')) $this->selectedBhpQuantities[$c->id] = 2;
                elseif (str_contains($c->name, 'NEEDLE 27G')) $this->selectedBhpQuantities[$c->id] = 4;
                elseif (str_contains($c->name, 'NEEDLE 30G')) $this->selectedBhpQuantities[$c->id] = 4;
                elseif (str_contains($c->name, 'NEEDLE 32G')) $this->selectedBhpQuantities[$c->id] = 2;
                elseif (str_contains($c->name, 'INFUSE SET')) $this->selectedBhpQuantities[$c->id] = 1;
                elseif (str_contains($c->name, 'ABOCATH')) $this->selectedBhpQuantities[$c->id] = 1;
                elseif (str_contains($c->name, 'NEUROBION')) $this->selectedBhpQuantities[$c->id] = 1;
                elseif (str_contains($c->name, 'VITAMIN C')) $this->selectedBhpQuantities[$c->id] = 1;
                elseif (str_contains($c->name, 'MICROPORE')) $this->selectedBhpQuantities[$c->id] = 30;
                elseif (str_contains($c->name, 'ALKOHOL SWAB')) $this->selectedBhpQuantities[$c->id] = 10;
                elseif (str_contains($c->name, 'NACL 100')) $this->selectedBhpQuantities[$c->id] = 1;
                elseif (str_contains($c->name, 'OTC PLAST')) $this->selectedBhpQuantities[$c->id] = 1;
                else $this->selectedBhpQuantities[$c->id] = 0;
            }
        }
    }

    public function loadExistingVisit(int $visitId): void
    {
        $visit = Visit::with(['patient', 'informedConsent', 'visitConsumables'])->find($visitId);
        if (!$visit) return;

        $this->visitId = $visit->id;
        $this->patientId = $visit->patient_id;
        $p = $visit->patient;

        // Step 1
        $this->manual_patient_code = $visit->manual_patient_code ?: ($p->patient_code ?? '');
        $this->inbody_id = $visit->inbody_id ?: ($p->inbody_id ?? '');
        $this->registration_date = $visit->registration_date ? $visit->registration_date->format('Y-m-d') : '';
        $this->name = $p->name ?? '';
        $this->nik_passport = $p->nik_passport ?: ($p->nik ?? '');
        $this->birth_place = $p->birth_place ?? '';
        $this->date_of_birth = $p->date_of_birth ? $p->date_of_birth->format('Y-m-d') : '';
        $this->gender = $p->gender ? ucfirst($p->gender) : 'Perempuan';
        $this->marital_status = $p->marital_status ?? 'Belum Menikah';
        $this->id_address = $p->id_address ?: ($p->address ?? '');
        $this->domicile_address = $p->domicile_address ?: $this->id_address;
        $this->is_domicile_same = ($this->domicile_address === $this->id_address);
        $this->phone_number = $p->phone_number ?? '';
        $this->email = $p->email ?? '';
        $this->occupation = $p->occupation ?? '';

        $this->emergency_name = $p->emergency_contact_name ?? '';
        $this->emergency_relation = $p->emergency_contact_relation ?? 'Keluarga';
        $this->emergency_phone = $p->emergency_contact_phone ?? '';

        $this->payment_type = $p->payment_type ?? 'Pribadi / Umum';
        $this->insurance_company_name = $p->insurance_company_name ?? '';
        $this->insurance_card_number = $p->insurance_card_number ?? '';

        $this->patient_service_status = $visit->patient_service_status ?: ($p->patient_service_status ?? 'Pasien Baru');
        $this->visit_purpose = $visit->visit_purpose ?? 'Estetika';
        $this->destination_doctor_poli = $visit->destination_doctor_poli ?? '';
        $this->chief_complaint = $visit->chief_complaint ?? '';

        $this->referral_source = $p->referral_source ?? 'Instagram / TikTok';
        $this->referral_source_other = $p->referral_source_other ?? '';
        $this->whatsapp_comm_consent = $visit->whatsapp_comm_consent ?? true;

        $this->registration_patient_signer_name = $visit->registration_patient_signer_name ?: $this->name;
        $this->registration_staff_signer_name = $visit->registration_staff_signer_name ?: 'Ns. Ratna Dewi, S.Kep';

        // Step 2 General Consent
        $this->gc_whatsapp_reminder = $visit->general_consent_whatsapp_reminder ?? true;
        $this->gc_promo_info = $visit->general_consent_promo_info ?? false;
        $this->gc_insurance_disclosure = $visit->general_consent_insurance_disclosure ?? 'Ya';
        $this->gc_third_party_info = $visit->general_consent_third_party_info ?? false;
        $this->gc_third_party_name = $visit->general_consent_third_party_name ?? '';
        $this->gc_third_party_phone = $visit->general_consent_third_party_phone ?? '';
        $this->gc_third_party_notes = $visit->general_consent_third_party_notes ?? '';
        $this->gc_patient_relation = $visit->general_consent_patient_relation ?? 'Diri Sendiri';
        $this->gc_patient_signer_name = $visit->general_consent_patient_signer_name ?: $this->name;
        $this->gc_staff_name = $visit->general_consent_staff_name ?: 'Ns. Ratna Dewi, S.Kep';
        $this->gc_staff_title = $visit->general_consent_staff_title ?: 'Petugas Pendaftaran';

        // Step 3 Informed Consent
        if ($visit->informedConsent) {
            $ic = $visit->informedConsent;
            $this->ic_doctor_name = $ic->doctor_name ?? 'dr. Alana Wijaya, Sp.D.V.E';
            $this->ic_info_provider = $ic->information_provider ?? $this->ic_doctor_name;
            $this->ic_recipient_name = $ic->recipient_name ?: $this->name;
            $this->ic_diagnosis = $ic->diagnosis_wd_dd ?? '';
            $this->ic_medical_procedure = $ic->medical_procedure ?? 'Messo + Botox + Laser CO2 + Multivitamin Infuse';
            $this->ic_indication = $ic->indication ?? '';
            $this->ic_procedure_steps = $ic->procedure_steps ?? '';
            $this->ic_procedure_goal = $ic->procedure_goal ?? '';
            $this->ic_risks = $ic->risks ?? '';
            $this->ic_complications = $ic->complications ?? '';
            $this->ic_prognosis = $ic->prognosis ?? '';
            $this->ic_alternatives_risks = $ic->alternatives_risks ?? '';
            $this->ic_other_notes = $ic->other_notes ?? '';
        }

        // Step 4 & 6 Photos
        $this->photo_before_front_path = $visit->photo_before_front ?: $visit->photo_before_path;
        $this->photo_before_right_tilt_path = $visit->photo_before_right_tilt;
        $this->photo_before_right_side_path = $visit->photo_before_right_side;
        $this->photo_before_left_tilt_path = $visit->photo_before_left_tilt;
        $this->photo_before_left_side_path = $visit->photo_before_left_side;

        $this->photo_after_front_path = $visit->photo_after_front ?: $visit->photo_after_path;
        $this->photo_after_right_tilt_path = $visit->photo_after_right_tilt;
        $this->photo_after_right_side_path = $visit->photo_after_right_side;
        $this->photo_after_left_tilt_path = $visit->photo_after_left_tilt;
        $this->photo_after_left_side_path = $visit->photo_after_left_side;

        // Step 5 BHP
        $this->bhp_patient_name = $this->name;
        $this->bhp_therapist_nurse = $visit->therapist_nurse_name ?: 'Ns. Ratna Dewi, S.Kep';
        $this->bhp_doctor_name = $visit->doctor_name_override ?: ($visit->doctor?->name ?? 'dr. Alana Wijaya, Sp.D.V.E');
        if (!empty($visit->custom_treatment_list)) {
            $this->treatmentRows = $visit->custom_treatment_list;
        }

        foreach ($visit->visitConsumables as $vc) {
            $this->selectedBhpQuantities[$vc->consumable_id] = $vc->quantity;
        }
    }

    public function addTreatmentRow(): void
    {
        $this->treatmentRows[] = ['action' => ''];
    }

    public function removeTreatmentRow(int $index): void
    {
        if (isset($this->treatmentRows[$index])) {
            unset($this->treatmentRows[$index]);
            $this->treatmentRows = array_values($this->treatmentRows);
        }
    }

    public function addCustomBhp(): void
    {
        $clinic = Clinic::first();
        if (trim($this->newCustomBhpName) !== '') {
            $created = Consumable::firstOrCreate(
                [
                    'clinic_id' => $clinic->id,
                    'name' => strtoupper(trim($this->newCustomBhpName)),
                ],
                [
                    'category' => 'BHP Tambahan',
                    'unit' => 'pcs',
                    'default_quantity' => $this->newCustomBhpQty ?: 1,
                    'is_active' => true,
                ]
            );
            $this->selectedBhpQuantities[$created->id] = $this->newCustomBhpQty ?: 1;
            $this->newCustomBhpName = '';
            $this->newCustomBhpQty = 1;
            $this->dispatch('notify', message: 'Item BHP baru ditambahkan.');
        }
    }

    // Navigation methods
    public function goToStep(int $step): void
    {
        if ($step < 1) $step = 1;
        if ($step > 7) $step = 7;

        // Auto save on step change if patient data exists
        $this->saveCurrentState();
        $this->currentStep = $step;
    }

    public function goBack(): void
    {
        if ($this->currentStep > 1) {
            $this->goToStep($this->currentStep - 1);
        }
    }

    public function continue(): void
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'manual_patient_code' => ['required', 'string', 'max:50'],
                'name' => ['required', 'string', 'max:255'],
                'phone_number' => ['required', 'string', 'max:30'],
            ], [
                'manual_patient_code.required' => 'Nomor rekam medis wajib ditulis secara manual.',
                'name.required' => 'Nama lengkap pasien wajib diisi.',
                'phone_number.required' => 'Nomor WhatsApp / HP wajib diisi.',
            ]);
        }

        $this->saveCurrentState();

        if ($this->currentStep < 7) {
            $this->currentStep++;
            $this->dispatch('notify', message: 'Halaman formulir berhasil disimpan.');
        }
    }

    public function saveCurrentState(): void
    {
        $clinic = Clinic::first();
        if (!$clinic) return;

        // 1. Create / Update Patient
        $patientData = [
            'clinic_id' => $clinic->id,
            'patient_code' => trim($this->manual_patient_code) ?: ('ONEW' . time()),
            'inbody_id' => trim($this->inbody_id) ?: null,
            'name' => trim($this->name) ?: 'Pasien Baru',
            'nik' => trim($this->nik_passport) ?: null,
            'nik_passport' => trim($this->nik_passport) ?: null,
            'phone_number' => trim($this->phone_number) ?: '0812000000',
            'email' => trim($this->email) ?: null,
            'gender' => strtolower($this->gender) === 'laki-laki' ? 'male' : 'female',
            'birth_place' => trim($this->birth_place) ?: null,
            'date_of_birth' => $this->date_of_birth ?: '1995-01-01',
            'marital_status' => $this->marital_status,
            'address' => trim($this->id_address) ?: null,
            'id_address' => trim($this->id_address) ?: null,
            'domicile_address' => $this->is_domicile_same ? trim($this->id_address) : trim($this->domicile_address),
            'occupation' => trim($this->occupation) ?: null,
            'payment_type' => $this->payment_type,
            'insurance_company_name' => trim($this->insurance_company_name) ?: null,
            'insurance_card_number' => trim($this->insurance_card_number) ?: null,
            'patient_service_status' => $this->patient_service_status,
            'referral_source' => $this->referral_source,
            'referral_source_other' => trim($this->referral_source_other) ?: null,
            'emergency_contact_name' => trim($this->emergency_name) ?: null,
            'emergency_contact_relation' => trim($this->emergency_relation) ?: null,
            'emergency_contact_phone' => trim($this->emergency_phone) ?: null,
            'whatsapp_reminder' => $this->gc_whatsapp_reminder,
            'promo_consent' => $this->gc_promo_info,
        ];

        if ($this->patientId) {
            $patient = Patient::find($this->patientId);
            $patient?->update($patientData);
        } else {
            // Find or create by manual patient code
            $patient = Patient::updateOrCreate(
                ['clinic_id' => $clinic->id, 'patient_code' => $patientData['patient_code']],
                $patientData
            );
            $this->patientId = $patient->id;
        }

        // Auto sync names
        $this->bhp_patient_name = $this->name;
        $this->ic_recipient_name = $this->ic_recipient_name ?: $this->name;
        $this->gc_patient_signer_name = $this->gc_patient_signer_name ?: $this->name;
        $this->registration_patient_signer_name = $this->registration_patient_signer_name ?: $this->name;

        // Helper to save base64 signature
        $saveSig = function (?string $base64Data, string $prefix) use ($patient): ?string {
            if ($base64Data && str_starts_with($base64Data, 'data:image')) {
                $parts = explode(',', $base64Data);
                $decoded = base64_decode($parts[1] ?? '');
                if ($decoded) {
                    $path = "signatures/{$prefix}_patient_{$patient->id}_" . time() . "_" . rand(100, 999) . ".png";
                    Storage::disk('public')->put($path, $decoded);
                    return $path;
                }
            }
            return null;
        };

        // 2. Handle 5 Before Photos
        if ($this->temp_photo_before_front) {
            $this->photo_before_front_path = $this->temp_photo_before_front->storeAs('photos/before', "before_front_{$patient->id}_" . time() . '.png', 'public');
            $this->temp_photo_before_front = null;
        }
        if ($this->temp_photo_before_right_tilt) {
            $this->photo_before_right_tilt_path = $this->temp_photo_before_right_tilt->storeAs('photos/before', "before_rtilt_{$patient->id}_" . time() . '.png', 'public');
            $this->temp_photo_before_right_tilt = null;
        }
        if ($this->temp_photo_before_right_side) {
            $this->photo_before_right_side_path = $this->temp_photo_before_right_side->storeAs('photos/before', "before_rside_{$patient->id}_" . time() . '.png', 'public');
            $this->temp_photo_before_right_side = null;
        }
        if ($this->temp_photo_before_left_tilt) {
            $this->photo_before_left_tilt_path = $this->temp_photo_before_left_tilt->storeAs('photos/before', "before_ltilt_{$patient->id}_" . time() . '.png', 'public');
            $this->temp_photo_before_left_tilt = null;
        }
        if ($this->temp_photo_before_left_side) {
            $this->photo_before_left_side_path = $this->temp_photo_before_left_side->storeAs('photos/before', "before_lside_{$patient->id}_" . time() . '.png', 'public');
            $this->temp_photo_before_left_side = null;
        }

        // 3. Handle 5 After Photos
        if ($this->temp_photo_after_front) {
            $this->photo_after_front_path = $this->temp_photo_after_front->storeAs('photos/after', "after_front_{$patient->id}_" . time() . '.png', 'public');
            $this->temp_photo_after_front = null;
        }
        if ($this->temp_photo_after_right_tilt) {
            $this->photo_after_right_tilt_path = $this->temp_photo_after_right_tilt->storeAs('photos/after', "after_rtilt_{$patient->id}_" . time() . '.png', 'public');
            $this->temp_photo_after_right_tilt = null;
        }
        if ($this->temp_photo_after_right_side) {
            $this->photo_after_right_side_path = $this->temp_photo_after_right_side->storeAs('photos/after', "after_rside_{$patient->id}_" . time() . '.png', 'public');
            $this->temp_photo_after_right_side = null;
        }
        if ($this->temp_photo_after_left_tilt) {
            $this->photo_after_left_tilt_path = $this->temp_photo_after_left_tilt->storeAs('photos/after', "after_ltilt_{$patient->id}_" . time() . '.png', 'public');
            $this->temp_photo_after_left_tilt = null;
        }
        if ($this->temp_photo_after_left_side) {
            $this->photo_after_left_side_path = $this->temp_photo_after_left_side->storeAs('photos/after', "after_lside_{$patient->id}_" . time() . '.png', 'public');
            $this->temp_photo_after_left_side = null;
        }

        // 4. Create / Update Visit
        $doctor = User::where('role', 'doctor')->first();
        $nurse = User::where('role', 'nurse')->first();

        $visitData = [
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'manual_patient_code' => trim($this->manual_patient_code) ?: $patient->patient_code,
            'inbody_id' => trim($this->inbody_id) ?: null,
            'registration_date' => $this->registration_date ?: Carbon::today(),
            'visit_purpose' => $this->visit_purpose,
            'destination_doctor_poli' => trim($this->destination_doctor_poli) ?: null,
            'chief_complaint' => trim($this->chief_complaint) ?: null,
            'whatsapp_comm_consent' => $this->whatsapp_comm_consent,
            'registration_patient_signer_name' => trim($this->registration_patient_signer_name) ?: $patient->name,
            'registration_staff_signer_name' => trim($this->registration_staff_signer_name) ?: 'Ns. Ratna Dewi, S.Kep',
            'registration_staff_signed_at' => Carbon::now(),

            'general_consent_staff_name' => trim($this->gc_staff_name) ?: 'Ns. Ratna Dewi, S.Kep',
            'general_consent_staff_title' => trim($this->gc_staff_title) ?: 'Petugas Pendaftaran',
            'general_consent_patient_relation' => $this->gc_patient_relation,
            'general_consent_patient_signer_name' => trim($this->gc_patient_signer_name) ?: $patient->name,
            'general_consent_whatsapp_reminder' => $this->gc_whatsapp_reminder,
            'general_consent_promo_info' => $this->gc_promo_info,
            'general_consent_insurance_disclosure' => $this->gc_insurance_disclosure,
            'general_consent_third_party_info' => $this->gc_third_party_info,
            'general_consent_third_party_name' => trim($this->gc_third_party_name) ?: null,
            'general_consent_third_party_phone' => trim($this->gc_third_party_phone) ?: null,
            'general_consent_third_party_notes' => trim($this->gc_third_party_notes) ?: null,

            'photo_before_front' => $this->photo_before_front_path,
            'photo_before_right_tilt' => $this->photo_before_right_tilt_path,
            'photo_before_right_side' => $this->photo_before_right_side_path,
            'photo_before_left_tilt' => $this->photo_before_left_tilt_path,
            'photo_before_left_side' => $this->photo_before_left_side_path,
            'photo_before_path' => $this->photo_before_front_path ?: $this->photo_before_right_tilt_path,

            'photo_after_front' => $this->photo_after_front_path,
            'photo_after_right_tilt' => $this->photo_after_right_tilt_path,
            'photo_after_right_side' => $this->photo_after_right_side_path,
            'photo_after_left_tilt' => $this->photo_after_left_tilt_path,
            'photo_after_left_side' => $this->photo_after_left_side_path,
            'photo_after_path' => $this->photo_after_front_path ?: $this->photo_after_right_tilt_path,

            'therapist_nurse_name' => trim($this->bhp_therapist_nurse) ?: 'Ns. Ratna Dewi, S.Kep',
            'doctor_name_override' => trim($this->bhp_doctor_name) ?: 'dr. Alana Wijaya, Sp.D.V.E',
            'custom_treatment_list' => array_values(array_filter($this->treatmentRows, fn($r) => !empty(trim($r['action'] ?? '')))),

            'doctor_id' => $doctor?->id,
            'nurse_id' => $nurse?->id,
            'assistant_name' => trim($this->bhp_therapist_nurse) ?: 'Ns. Ratna Dewi, S.Kep',
            'diagnosis_wd' => trim($this->ic_diagnosis) ?: 'Pemeriksaan Estetika Klinis',
            'diagnosis_dd' => 'Kondisi Kulit & Kontur Wajah',
            'doctor_notes' => trim($this->ic_other_notes) ?: null,
            'status' => $this->currentStep >= 6 ? 'completed' : 'in_treatment',
            'consent_signed' => true,
            'consent_signed_at' => Carbon::now(),
        ];

        // Process Signatures if provided as new dataURL
        $regPatSig = $saveSig($this->registration_patient_signature, 'reg_patient');
        if ($regPatSig) $visitData['registration_patient_signature_path'] = $regPatSig;

        $regStaffSig = $saveSig($this->registration_staff_signature, 'reg_staff');
        if ($regStaffSig) $visitData['registration_staff_signature_path'] = $regStaffSig;

        $gcPatSig = $saveSig($this->gc_patient_signature, 'gc_patient');
        if ($gcPatSig) {
            $visitData['consent_signature_path'] = $gcPatSig;
        }

        $gcStaffSig = $saveSig($this->gc_staff_signature, 'gc_staff');
        if ($gcStaffSig) $visitData['general_consent_staff_signature_path'] = $gcStaffSig;

        if ($this->visitId) {
            $visit = Visit::find($this->visitId);
            $visit?->update($visitData);
        } else {
            $visit = Visit::create($visitData);
            $this->visitId = $visit->id;
        }

        // 5. Create / Update Informed Consent (Step 3)
        $icDocSig = $saveSig($this->ic_doctor_signature, 'ic_doctor');
        $icRecSig = $saveSig($this->ic_recipient_signature, 'ic_recipient');
        $icWitSig = $saveSig($this->ic_witness_signature, 'ic_witness');

        $consentData = [
            'visit_id' => $visit->id,
            'doctor_id' => $doctor?->id,
            'information_provider' => trim($this->ic_info_provider) ?: 'dr. Alana Wijaya, Sp.D.V.E',
            'recipient_name' => trim($this->ic_recipient_name) ?: $patient->name,
            'recipient_age' => $this->ic_patient_age ?: ($patient->date_of_birth ? (string) $patient->date_of_birth->age : '28'),
            'recipient_gender' => $patient->gender,
            'recipient_relation' => $this->ic_action_target_relation,
            'recipient_address' => trim($this->id_address) ?: null,
            'target_patient_name' => trim($this->ic_target_patient_name) ?: $patient->name,
            'target_patient_age' => $this->ic_patient_age ?: ($patient->date_of_birth ? (string) $patient->date_of_birth->age : '28'),
            'target_patient_gender' => $patient->gender,
            'target_patient_address' => trim($this->id_address) ?: null,
            'action_name' => trim($this->ic_medical_procedure),

            'diagnosis_wd_dd' => trim($this->ic_diagnosis),
            'diagnosis_checked' => $this->ic_diagnosis_checked,
            'diagnosis_basis' => trim($this->ic_diagnosis_basis),
            'diagnosis_basis_checked' => $this->ic_diagnosis_basis_checked,
            'medical_procedure' => trim($this->ic_medical_procedure),
            'medical_procedure_checked' => $this->ic_medical_procedure_checked,
            'indication' => trim($this->ic_indication),
            'indication_checked' => $this->ic_indication_checked,
            'procedure_steps' => trim($this->ic_procedure_steps),
            'procedure_steps_checked' => $this->ic_procedure_steps_checked,
            'procedure_goal' => trim($this->ic_procedure_goal),
            'procedure_goal_checked' => $this->ic_procedure_goal_checked,
            'risks' => trim($this->ic_risks),
            'risks_checked' => $this->ic_risks_checked,
            'complications' => trim($this->ic_complications),
            'complications_checked' => $this->ic_complications_checked,
            'prognosis' => trim($this->ic_prognosis),
            'prognosis_checked' => $this->ic_prognosis_checked,
            'alternatives_risks' => trim($this->ic_alternatives_risks),
            'alternatives_risks_checked' => $this->ic_alternatives_risks_checked,
            'other_notes' => trim($this->ic_other_notes),
            'other_notes_checked' => $this->ic_other_notes_checked,

            'witness_name' => trim($this->ic_witness_name) ?: 'Ns. Ratna Dewi, S.Kep',
            'consent_city' => 'Jakarta',
            'consent_date' => Carbon::today(),
            'consent_time' => Carbon::now()->format('H:i'),
            'is_agreed' => true,
            'patient_signed' => true,
            'doctor_signed' => true,
        ];

        if ($icDocSig) $consentData['doctor_signature_path'] = $icDocSig;
        if ($icRecSig) $consentData['patient_signature_path'] = $icRecSig;
        if ($icWitSig) $consentData['witness_signature_path'] = $icWitSig;

        InformedConsent::updateOrCreate(['visit_id' => $visit->id], $consentData);

        // 6. Sync BHP Consumables (Step 5)
        VisitConsumable::where('visit_id', $visit->id)->delete();
        foreach ($this->selectedBhpQuantities as $consumableId => $qty) {
            if ($qty > 0) {
                VisitConsumable::create([
                    'visit_id' => $visit->id,
                    'consumable_id' => $consumableId,
                    'quantity' => $qty,
                ]);
            }
        }
    }

    public function resetNewPatient()
    {
        return redirect()->route('clinical.form.flow');
    }

    public function render()
    {
        $clinic = Clinic::first();
        $doctors = User::where('role', 'doctor')->get();
        $nurses = User::where('role', 'nurse')->get();
        $consumables = Consumable::where('is_active', true)->orderBy('id')->get();

        return view('livewire.clinical-form-flow', [
            'clinic' => $clinic,
            'doctors' => $doctors,
            'nurses' => $nurses,
            'consumables' => $consumables,
        ]);
    }
}
