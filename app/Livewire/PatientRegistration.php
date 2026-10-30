<?php

namespace App\Livewire;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use App\Services\PatientCodeGenerator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class PatientRegistration extends Component
{
    use WithFileUploads;

    // Mode: 'new' or 'search'
    public string $mode = 'new';

    // Search query for existing patients
    public string $searchQuery = '';
    public ?int $selectedPatientId = null;

    // Patient Identity Fields
    public string $name = '';
    public string $nik = '';
    public string $birth_place = 'Jakarta';
    public string $date_of_birth = '';
    public string $gender = 'female';
    public string $phone_number = '';
    public string $email = '';
    public string $address = '';

    // Emergency Contact
    public string $emergency_contact_name = '';
    public string $emergency_contact_relation = '';
    public string $emergency_contact_phone = '';

    // Communication Preferences
    public bool $whatsapp_reminder = true;
    public bool $promo_consent = false;

    // Nurse / Initial Checkup Fields
    public ?int $doctor_id = null;
    public string $assistant_name = 'Ns. Ratna Dewi, S.Kep';
    public $photo_before;

    // General Consent & Digital Signature
    public bool $agreementChecked = true;
    public string $signatureData = '';

    public function mount(): void
    {
        $this->date_of_birth = Carbon::now()->subYears(25)->format('Y-m-d');

        $firstDoctor = User::where('role', 'doctor')->first();
        if ($firstDoctor) {
            $this->doctor_id = $firstDoctor->id;
        }
    }

    public function selectExistingPatient(int $id): void
    {
        $this->selectedPatientId = $id;
        $patient = Patient::find($id);

        if ($patient) {
            $this->name = $patient->name;
            $this->nik = $patient->nik ?? '';
            $this->birth_place = $patient->birth_place ?? '';
            $this->date_of_birth = $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : $this->date_of_birth;
            $this->gender = $patient->gender ?? 'female';
            $this->phone_number = $patient->phone_number;
            $this->email = $patient->email ?? '';
            $this->address = $patient->address ?? '';
            $this->emergency_contact_name = $patient->emergency_contact_name ?? '';
            $this->emergency_contact_relation = $patient->emergency_contact_relation ?? '';
            $this->emergency_contact_phone = $patient->emergency_contact_phone ?? '';
            $this->whatsapp_reminder = (bool) $patient->whatsapp_reminder;
            $this->promo_consent = (bool) $patient->promo_consent;
        }

        $this->searchQuery = '';
    }

    public function resetSelection(): void
    {
        $this->selectedPatientId = null;
        $this->reset([
            'name', 'nik', 'birth_place', 'phone_number', 'email', 'address',
            'emergency_contact_name', 'emergency_contact_relation', 'emergency_contact_phone',
            'photo_before', 'signatureData'
        ]);
        $this->date_of_birth = Carbon::now()->subYears(25)->format('Y-m-d');
    }

    public function registerAndSubmitVisit(PatientCodeGenerator $codeGenerator)
    {
        $rules = [
            'doctor_id' => ['required', 'exists:users,id'],
            'assistant_name' => ['required', 'string', 'max:255'],
            'photo_before' => ['nullable', 'image', 'max:10240'],
            'agreementChecked' => ['accepted'],
        ];

        if ($this->mode === 'new') {
            $rules = array_merge($rules, [
                'name' => ['required', 'string', 'max:255'],
                'nik' => ['nullable', 'string', 'max:20'],
                'birth_place' => ['nullable', 'string', 'max:100'],
                'date_of_birth' => ['required', 'date', 'before:today'],
                'gender' => ['required', 'in:female,male'],
                'phone_number' => ['required', 'string', 'min:8', 'max:20'],
                'email' => ['nullable', 'email', 'max:255'],
                'address' => ['nullable', 'string', 'max:500'],
                'emergency_contact_name' => ['nullable', 'string', 'max:255'],
                'emergency_contact_relation' => ['nullable', 'string', 'max:100'],
                'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            ]);
        } else {
            $rules['selectedPatientId'] = ['required', 'exists:patients,id'];
        }

        $this->validate($rules, [
            'agreementChecked.accepted' => 'Anda harus mencentang persetujuan General Consent.',
            'doctor_id.required' => 'Pilih dokter pemeriksa jaga.',
            'assistant_name.required' => 'Masukkan nama perawat/asisten jaga.',
        ]);

        $clinic = Clinic::firstOrFail();

        $visit = DB::transaction(function () use ($clinic, $codeGenerator) {
            $patient = null;

            if ($this->mode === 'new') {
                $patientCode = $codeGenerator->generate($clinic);

                $patient = Patient::create([
                    'clinic_id' => $clinic->id,
                    'patient_code' => $patientCode,
                    'name' => trim($this->name),
                    'nik' => trim($this->nik) ?: null,
                    'birth_place' => trim($this->birth_place) ?: null,
                    'date_of_birth' => $this->date_of_birth,
                    'gender' => $this->gender,
                    'phone_number' => trim($this->phone_number),
                    'email' => trim($this->email) ?: null,
                    'address' => trim($this->address) ?: null,
                    'emergency_contact_name' => trim($this->emergency_contact_name) ?: null,
                    'emergency_contact_relation' => trim($this->emergency_contact_relation) ?: null,
                    'emergency_contact_phone' => trim($this->emergency_contact_phone) ?: null,
                    'whatsapp_reminder' => $this->whatsapp_reminder,
                    'promo_consent' => $this->promo_consent,
                ]);
            } else {
                $patient = Patient::findOrFail($this->selectedPatientId);
                // Update optional details if provided
                $patient->update([
                    'nik' => trim($this->nik) ?: $patient->nik,
                    'birth_place' => trim($this->birth_place) ?: $patient->birth_place,
                    'email' => trim($this->email) ?: $patient->email,
                    'address' => trim($this->address) ?: $patient->address,
                    'emergency_contact_name' => trim($this->emergency_contact_name) ?: $patient->emergency_contact_name,
                    'emergency_contact_relation' => trim($this->emergency_contact_relation) ?: $patient->emergency_contact_relation,
                    'emergency_contact_phone' => trim($this->emergency_contact_phone) ?: $patient->emergency_contact_phone,
                    'whatsapp_reminder' => $this->whatsapp_reminder,
                    'promo_consent' => $this->promo_consent,
                ]);
            }

            // Save digital signature if provided via signature pad
            $signaturePath = null;
            if (!empty($this->signatureData) && str_starts_with($this->signatureData, 'data:image')) {
                $imageParts = explode(';base64,', $this->signatureData);
                $imageBase64 = base64_decode($imageParts[1] ?? '');
                if ($imageBase64) {
                    $signaturePath = "signatures/consent_patient_{$patient->id}_" . time() . ".png";
                    Storage::disk('public')->put($signaturePath, $imageBase64);
                }
            }

            // Save photo before if uploaded
            $photoBeforePath = null;
            if ($this->photo_before) {
                $photoFilename = "photos/before/before_patient_{$patient->id}_" . time() . '.' . $this->photo_before->getClientOriginalExtension();
                $photoBeforePath = $this->photo_before->storeAs('photos/before', basename($photoFilename), 'public');
            }

            // Create visit directly ready for Doctor examination (waiting_doctor)
            return Visit::create([
                'clinic_id' => $clinic->id,
                'patient_id' => $patient->id,
                'doctor_id' => $this->doctor_id,
                'assistant_name' => trim($this->assistant_name),
                'status' => 'waiting_doctor', // langsung siap masuk ke pemeriksaan dokter
                'consent_signed' => true,
                'consent_signature_path' => $signaturePath,
                'consent_signed_at' => Carbon::now(),
                'photo_before_path' => $photoBeforePath,
                'trustmedis_sync_status' => 'pending',
            ]);
        });

        session()->flash('success', "Registrasi pasien {$visit->patient->name} berhasil disimpan! Pasien siap masuk ke Ruang Pemeriksaan Dokter.");
        $this->dispatch('notify', message: "Registrasi pasien {$visit->patient->name} berhasil disimpan!");

        return redirect()->route('doctor.treatment', ['visit_id' => $visit->id]);
    }

    public function render()
    {
        $clinic = Clinic::first();
        $searchResults = collect();

        if ($this->mode === 'search' && strlen(trim($this->searchQuery)) >= 2) {
            $q = '%' . trim($this->searchQuery) . '%';
            $searchResults = Patient::where('clinic_id', $clinic?->id)
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', $q)
                          ->orWhere('phone_number', 'like', $q)
                          ->orWhere('patient_code', 'like', $q)
                          ->orWhere('nik', 'like', $q);
                })
                ->limit(6)
                ->get();
        }

        $doctors = User::where('role', 'doctor')->get();
        $selectedPatient = $this->selectedPatientId ? Patient::find($this->selectedPatientId) : null;

        return view('livewire.patient-registration', [
            'clinic' => $clinic,
            'searchResults' => $searchResults,
            'selectedPatient' => $selectedPatient,
            'doctors' => $doctors,
        ]);
    }
}
