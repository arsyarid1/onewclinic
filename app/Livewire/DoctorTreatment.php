<?php

namespace App\Livewire;

use App\Contracts\TrustmedisServiceInterface;
use App\Jobs\SyncVisitJob;
use App\Models\Clinic;
use App\Models\Consumable;
use App\Models\InformedConsent;
use App\Models\Treatment;
use App\Models\Visit;
use App\Models\VisitConsumable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class DoctorTreatment extends Component
{
    use WithFileUploads;

    #[Url(as: 'visit_id')]
    public ?int $visit_id = null;

    public ?int $selectedVisitId = null;

    // Diagnosis Medis (WD & DD)
    public string $diagnosis_wd = ''; // Working Diagnosis
    public string $diagnosis_dd = ''; // Differential Diagnosis
    public string $doctorNotes = '';

    // Selected treatments: array of treatment IDs
    public array $selectedTreatments = [];
    public array $treatmentNotes = [];

    // Master BHP / Bahan Habis Pakai yang Dipakai [consumable_id => quantity]
    public array $selectedConsumables = [];
    public array $consumableNotes = [];

    // Foto Akhir (Before/After) - Dapat diunggah oleh Perawat maupun Dokter
    public $photo_after;

    // Persetujuan Tindakan Kedokteran (Informed Consent Modal State & Fields)
    public bool $showConsentModal = false;
    public string $consent_information_provider = '';
    public string $consent_recipient_name = '';
    public string $consent_recipient_age = '';
    public string $consent_recipient_gender = 'female';
    public string $consent_recipient_relation = 'Diri Sendiri';
    public string $consent_recipient_address = '';
    public string $consent_action_name = '';

    // 11 Poin Informasi Medis
    public string $info_diagnosis = '';
    public bool $info_diagnosis_checked = true;

    public string $info_diagnosis_basis = '';
    public bool $info_diagnosis_basis_checked = true;

    public string $info_medical_procedure = '';
    public bool $info_medical_procedure_checked = true;

    public string $info_indication = '';
    public bool $info_indication_checked = true;

    public string $info_procedure_steps = '';
    public bool $info_procedure_steps_checked = true;

    public string $info_procedure_goal = '';
    public bool $info_procedure_goal_checked = true;

    public string $info_risks = '';
    public bool $info_risks_checked = true;

    public string $info_complications = '';
    public bool $info_complications_checked = true;

    public string $info_prognosis = '';
    public bool $info_prognosis_checked = true;

    public string $info_alternatives_risks = '';
    public bool $info_alternatives_risks_checked = true;

    public string $info_other_notes = '';
    public bool $info_other_notes_checked = false;

    // Tanda Tangan Informed Consent
    public string $patientConsentSignature = '';
    public string $doctorConsentSignature = '';
    public string $witnessName = 'Ns. Ratna Dewi, S.Kep';
    public bool $hasExistingConsent = false;

    // Modal state after completion
    public bool $showSuccessModal = false;
    public ?int $completedVisitId = null;

    public function mount(?int $visit_id = null): void
    {
        $clinic = Clinic::first();
        $targetId = $visit_id ?: $this->visit_id ?: request()->query('visit_id') ?: request()->route('visit_id');

        if ($targetId) {
            $this->selectVisit((int) $targetId);
        } else {
            $defaultVisit = Visit::where('clinic_id', $clinic?->id)
                ->whereIn('status', ['waiting_doctor', 'in_treatment', 'consent_signed', 'registered'])
                ->oldest()
                ->first();

            if ($defaultVisit) {
                $this->selectVisit($defaultVisit->id);
            }
        }
    }

    public function selectVisit(int $visitId): void
    {
        $this->selectedVisitId = $visitId;
        $this->visit_id = $visitId;
        $this->showSuccessModal = false;

        $visit = Visit::with(['patient', 'doctor', 'treatments', 'visitConsumables', 'informedConsent'])->find($visitId);

        if ($visit) {
            // Update to in_treatment if not already completed
            if ($visit->status !== 'completed') {
                $visit->update(['status' => 'in_treatment']);
            }

            // Populate diagnosis & doctor notes
            $this->diagnosis_wd = $visit->diagnosis_wd ?? '';
            $this->diagnosis_dd = $visit->diagnosis_dd ?? '';
            $this->doctorNotes = $visit->doctor_notes ?? '';

            // Populate existing treatments
            $this->selectedTreatments = $visit->treatments->pluck('id')->map(fn($id) => (string) $id)->toArray();
            $this->treatmentNotes = [];
            foreach ($visit->treatments as $tr) {
                $this->treatmentNotes[$tr->id] = $tr->pivot->notes ?? '';
            }

            // Populate existing consumables (BHP)
            $this->selectedConsumables = [];
            $this->consumableNotes = [];
            foreach ($visit->visitConsumables as $vc) {
                $this->selectedConsumables[$vc->consumable_id] = $vc->quantity;
                $this->consumableNotes[$vc->consumable_id] = $vc->notes ?? '';
            }

            // Populate or prefill Informed Consent
            $patient = $visit->patient;
            $doctorUser = Auth::user() && Auth::user()->isDoctor() ? Auth::user() : $visit->doctor;

            if ($visit->informedConsent) {
                $consent = $visit->informedConsent;
                $this->hasExistingConsent = true;
                $this->consent_information_provider = $consent->information_provider ?? ($doctorUser?->name ?? 'Dokter Pemeriksa');
                $this->consent_recipient_name = $consent->recipient_name ?? ($patient?->name ?? '');
                $this->consent_recipient_age = (string) ($consent->recipient_age ?? ($patient?->date_of_birth ? $patient->date_of_birth->age : ''));
                $this->consent_recipient_gender = $consent->recipient_gender ?? ($patient?->gender ?? 'female');
                $this->consent_recipient_relation = $consent->recipient_relation ?? 'Diri Sendiri';
                $this->consent_recipient_address = $consent->recipient_address ?? ($patient?->address ?? '');
                $this->consent_action_name = $consent->action_name ?? '';

                $this->info_diagnosis = $consent->diagnosis_wd_dd ?? $this->diagnosis_wd;
                $this->info_diagnosis_checked = (bool) $consent->diagnosis_checked;
                $this->info_diagnosis_basis = $consent->diagnosis_basis ?? 'Pemeriksaan Klinis & Evaluasi Dermatologis';
                $this->info_diagnosis_basis_checked = (bool) $consent->diagnosis_basis_checked;
                $this->info_medical_procedure = $consent->medical_procedure ?? $consent->action_name ?? '';
                $this->info_medical_procedure_checked = (bool) $consent->medical_procedure_checked;
                $this->info_indication = $consent->indication ?? 'Indikasi estetika & peremajaan jaringan kulit';
                $this->info_indication_checked = (bool) $consent->indication_checked;
                $this->info_procedure_steps = $consent->procedure_steps ?? 'Aseptik, aplikasi anestesi topikal bila perlu, pelaksanaan tindakan sesuai SOP steril';
                $this->info_procedure_steps_checked = (bool) $consent->procedure_steps_checked;
                $this->info_procedure_goal = $consent->procedure_goal ?? 'Perbaikan tekstur, peremajaan kulit, dan pemulihan estetika wajah';
                $this->info_procedure_goal_checked = (bool) $consent->procedure_goal_checked;
                $this->info_risks = $consent->risks ?? 'Kemerahan ringan sementara, pembengkakan minimal, rasa hangat pada area tindakan';
                $this->info_risks_checked = (bool) $consent->risks_checked;
                $this->info_complications = $consent->complications ?? 'Reaksi hipersensitivitas sangat jarang, hiperpigmentasi pasca inflamasi bila terpapar matahari';
                $this->info_complications_checked = (bool) $consent->complications_checked;
                $this->info_prognosis = $consent->prognosis ?? 'Dubia ad bonam (baik)';
                $this->info_prognosis_checked = (bool) $consent->prognosis_checked;
                $this->info_alternatives_risks = $consent->alternatives_risks ?? 'Perawatan topikal rumahan dengan masa pemulihan lebih lambat';
                $this->info_alternatives_risks_checked = (bool) $consent->alternatives_risks_checked;
                $this->info_other_notes = $consent->other_notes ?? '';
                $this->info_other_notes_checked = (bool) $consent->other_notes_checked;
                $this->witnessName = $consent->witness_name ?? $this->witnessName;
            } else {
                $this->hasExistingConsent = false;
                $this->consent_information_provider = $doctorUser?->name ?? 'dr. Alana Wijaya, Sp.D.V.E';
                $this->consent_recipient_name = $patient?->name ?? '';
                $this->consent_recipient_age = (string) ($patient?->date_of_birth ? $patient->date_of_birth->age : '25');
                $this->consent_recipient_gender = $patient?->gender ?? 'female';
                $this->consent_recipient_relation = 'Diri Sendiri';
                $this->consent_recipient_address = $patient?->address ?? '';
                $this->consent_action_name = $visit->treatments->pluck('name')->implode(', ');

                $this->info_diagnosis = $this->diagnosis_wd ?: 'Photoaging / Acne Vulgaris / Hyperpigmentation';
                $this->info_diagnosis_checked = true;
                $this->info_diagnosis_basis = 'Pemeriksaan Klinis & Evaluasi Dermatologis';
                $this->info_diagnosis_basis_checked = true;
                $this->info_medical_procedure = $this->consent_action_name ?: 'Tindakan Estetika Medis';
                $this->info_medical_procedure_checked = true;
                $this->info_indication = 'Indikasi estetika & peremajaan jaringan kulit';
                $this->info_indication_checked = true;
                $this->info_procedure_steps = 'Aseptik, aplikasi anestesi topikal bila perlu, pelaksanaan tindakan sesuai SOP steril';
                $this->info_procedure_steps_checked = true;
                $this->info_procedure_goal = 'Perbaikan tekstur, peremajaan kulit, dan pemulihan estetika wajah';
                $this->info_procedure_goal_checked = true;
                $this->info_risks = 'Kemerahan ringan sementara, pembengkakan minimal, rasa hangat pada area tindakan';
                $this->info_risks_checked = true;
                $this->info_complications = 'Reaksi hipersensitivitas sangat jarang, hiperpigmentasi pasca inflamasi bila terpapar matahari';
                $this->info_complications_checked = true;
                $this->info_prognosis = 'Dubia ad bonam (baik)';
                $this->info_prognosis_checked = true;
                $this->info_alternatives_risks = 'Perawatan topikal rumahan dengan masa pemulihan lebih lambat';
                $this->info_alternatives_risks_checked = true;
                $this->info_other_notes = '';
                $this->info_other_notes_checked = false;
            }
        }
    }

    public function toggleTreatment(int $treatmentId): void
    {
        $strId = (string) $treatmentId;
        if (in_array($strId, $this->selectedTreatments)) {
            $this->selectedTreatments = array_diff($this->selectedTreatments, [$strId]);
            unset($this->treatmentNotes[$treatmentId]);
        } else {
            $this->selectedTreatments[] = $strId;
            if (!isset($this->treatmentNotes[$treatmentId])) {
                $this->treatmentNotes[$treatmentId] = '';
            }
        }

        // Update action name in informed consent
        $treatmentNames = Treatment::whereIn('id', $this->selectedTreatments)->pluck('name')->toArray();
        if (!empty($treatmentNames)) {
            $this->consent_action_name = implode(', ', $treatmentNames);
            $this->info_medical_procedure = $this->consent_action_name;
        }
    }

    public function toggleConsumable(int $consumableId, int $defaultQty = 1): void
    {
        if (isset($this->selectedConsumables[$consumableId])) {
            unset($this->selectedConsumables[$consumableId]);
            unset($this->consumableNotes[$consumableId]);
        } else {
            $this->selectedConsumables[$consumableId] = $defaultQty ?: 1;
        }
    }

    public function incrementConsumable(int $consumableId): void
    {
        if (!isset($this->selectedConsumables[$consumableId])) {
            $this->selectedConsumables[$consumableId] = 1;
        } else {
            $this->selectedConsumables[$consumableId]++;
        }
    }

    public function decrementConsumable(int $consumableId): void
    {
        if (isset($this->selectedConsumables[$consumableId])) {
            if ($this->selectedConsumables[$consumableId] > 1) {
                $this->selectedConsumables[$consumableId]--;
            } else {
                unset($this->selectedConsumables[$consumableId]);
            }
        }
    }

    public function openConsentModal(): void
    {
        $this->showConsentModal = true;
    }

    public function closeConsentModal(): void
    {
        $this->showConsentModal = false;
    }

    public function saveInformedConsent(): void
    {
        $this->validate([
            'selectedVisitId' => ['required', 'exists:visits,id'],
            'consent_recipient_name' => ['required', 'string', 'max:255'],
            'consent_action_name' => ['required', 'string', 'max:255'],
        ], [
            'consent_recipient_name.required' => 'Nama penerima informasi persetujuan harus diisi.',
            'consent_action_name.required' => 'Nama tindakan medis harus diisi.',
        ]);

        $visit = Visit::findOrFail($this->selectedVisitId);
        $doctorUser = Auth::user() && Auth::user()->isDoctor() ? Auth::user() : $visit->doctor;

        // Save patient signature if provided
        $patientSigPath = $visit->informedConsent?->patient_signature_path;
        if (!empty($this->patientConsentSignature) && str_starts_with($this->patientConsentSignature, 'data:image')) {
            $imageParts = explode(';base64,', $this->patientConsentSignature);
            $imageBase64 = base64_decode($imageParts[1] ?? '');
            if ($imageBase64) {
                $patientSigPath = "signatures/consent_procedure_patient_{$visit->id}_" . time() . ".png";
                Storage::disk('public')->put($patientSigPath, $imageBase64);
            }
        }

        InformedConsent::updateOrCreate(
            ['visit_id' => $visit->id],
            [
                'doctor_id' => $doctorUser?->id,
                'information_provider' => trim($this->consent_information_provider) ?: ($doctorUser?->name ?? 'Dokter Pemeriksa'),
                'recipient_name' => trim($this->consent_recipient_name),
                'recipient_age' => trim($this->consent_recipient_age),
                'recipient_gender' => $this->consent_recipient_gender,
                'recipient_relation' => trim($this->consent_recipient_relation),
                'recipient_address' => trim($this->consent_recipient_address),
                'target_patient_name' => $visit->patient?->name,
                'target_patient_age' => (string) ($visit->patient?->date_of_birth ? $visit->patient->date_of_birth->age : ''),
                'target_patient_gender' => $visit->patient?->gender,
                'target_patient_address' => $visit->patient?->address,
                'action_name' => trim($this->consent_action_name),

                'diagnosis_wd_dd' => trim($this->info_diagnosis),
                'diagnosis_checked' => $this->info_diagnosis_checked,
                'diagnosis_basis' => trim($this->info_diagnosis_basis),
                'diagnosis_basis_checked' => $this->info_diagnosis_basis_checked,
                'medical_procedure' => trim($this->info_medical_procedure),
                'medical_procedure_checked' => $this->info_medical_procedure_checked,
                'indication' => trim($this->info_indication),
                'indication_checked' => $this->info_indication_checked,
                'procedure_steps' => trim($this->info_procedure_steps),
                'procedure_steps_checked' => $this->info_procedure_steps_checked,
                'procedure_goal' => trim($this->info_procedure_goal),
                'procedure_goal_checked' => $this->info_procedure_goal_checked,
                'risks' => trim($this->info_risks),
                'risks_checked' => $this->info_risks_checked,
                'complications' => trim($this->info_complications),
                'complications_checked' => $this->info_complications_checked,
                'prognosis' => trim($this->info_prognosis),
                'prognosis_checked' => $this->info_prognosis_checked,
                'alternatives_risks' => trim($this->info_alternatives_risks),
                'alternatives_risks_checked' => $this->info_alternatives_risks_checked,
                'other_notes' => trim($this->info_other_notes),
                'other_notes_checked' => $this->info_other_notes_checked,

                'patient_signed' => true,
                'patient_signature_path' => $patientSigPath,
                'patient_signed_at' => Carbon::now(),
                'witness_name' => trim($this->witnessName),
                'consent_city' => 'Jakarta',
                'consent_date' => Carbon::today(),
                'consent_time' => Carbon::now()->format('H:i'),
                'is_agreed' => true,
            ]
        );

        $this->hasExistingConsent = true;
        $this->showConsentModal = false;
        $this->dispatch('notify', message: 'Formulir Persetujuan Tindakan Kedokteran (Informed Consent) berhasil disimpan!');
        session()->flash('success', 'Formulir Persetujuan Tindakan Kedokteran (Informed Consent) berhasil ditandatangani dan disimpan!');
    }

    public function completeVisitAndSync(TrustmedisServiceInterface $trustmedisService): void
    {
        $this->validate([
            'selectedVisitId' => ['required', 'exists:visits,id'],
            'selectedTreatments' => ['required', 'array', 'min:1'],
            'photo_after' => ['nullable', 'image', 'max:10240'],
        ], [
            'selectedTreatments.required' => 'Pilih minimal satu tindakan perawatan yang dilakukan pada pasien.',
            'selectedTreatments.min' => 'Pilih minimal satu tindakan perawatan yang dilakukan pada pasien.',
        ]);

        $visit = Visit::with(['patient', 'clinic', 'visitConsumables'])->findOrFail($this->selectedVisitId);

        // Update diagnosis & doctor notes
        $visit->diagnosis_wd = trim($this->diagnosis_wd) ?: null;
        $visit->diagnosis_dd = trim($this->diagnosis_dd) ?: null;
        $visit->doctor_notes = trim($this->doctorNotes) ?: null;

        // If logged in as doctor, ensure doctor_id is assigned
        if (Auth::user() && Auth::user()->isDoctor()) {
            $visit->doctor_id = Auth::id();
        }

        // Store Photo After if uploaded
        if ($this->photo_after) {
            $filename = "photos/after/after_visit_{$visit->id}_" . time() . '.' . $this->photo_after->getClientOriginalExtension();
            $path = $this->photo_after->storeAs('photos/after', basename($filename), 'public');
            $visit->photo_after_path = $path;
        }

        // Attach treatments and clinical notes to pivot table
        $syncData = [];
        foreach ($this->selectedTreatments as $treatmentId) {
            $intId = (int) $treatmentId;
            $syncData[$intId] = [
                'notes' => $this->treatmentNotes[$intId] ?? null,
            ];
        }
        $visit->treatments()->sync($syncData);

        // Sync Consumables (BHP Bahan Habis Pakai)
        VisitConsumable::where('visit_id', $visit->id)->delete();
        foreach ($this->selectedConsumables as $consumableId => $qty) {
            if ($qty > 0) {
                VisitConsumable::create([
                    'visit_id' => $visit->id,
                    'consumable_id' => (int) $consumableId,
                    'quantity' => (int) $qty,
                    'notes' => $this->consumableNotes[$consumableId] ?? null,
                ]);
            }
        }

        // Mark visit as completed
        $visit->status = 'completed';
        $visit->save();

        // Trigger Trustmedis Mock Sync
        $trustmedisService->syncVisit($visit);

        // Dispatch SyncVisitJob
        SyncVisitJob::dispatch($visit);

        $this->completedVisitId = $visit->id;
        $this->showSuccessModal = true;
        $this->dispatch('notify', message: "Pemeriksaan medis pasien {$visit->patient?->name} berhasil diselesaikan!");
        session()->flash('success', "Pemeriksaan medis kunjungan #{$visit->id} selesai dan rekam medis berhasil difinalisasi!");

        $this->photo_after = null;
    }

    public function render()
    {
        $clinic = Clinic::first();
        $clinicId = $clinic?->id ?? 1;

        $waitingVisits = Visit::with(['patient', 'doctor'])
            ->where('clinic_id', $clinicId)
            ->whereIn('status', ['registered', 'consent_signed', 'waiting_doctor', 'in_treatment'])
            ->oldest()
            ->get();

        $treatments = Treatment::where('clinic_id', $clinicId)->get();
        $consumables = Consumable::where('clinic_id', $clinicId)->where('is_active', true)->get();

        $currentVisit = $this->selectedVisitId
            ? Visit::with(['patient', 'doctor', 'treatments', 'visitConsumables.consumable', 'informedConsent'])->find($this->selectedVisitId)
            : null;

        return view('livewire.doctor-treatment', [
            'waitingVisits' => $waitingVisits,
            'treatments' => $treatments,
            'consumables' => $consumables,
            'currentVisit' => $currentVisit,
        ]);
    }
}
