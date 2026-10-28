<?php

namespace App\Livewire;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Visit;
use App\Services\PatientCodeGenerator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PatientRegistration extends Component
{
    // Mode: 'new' or 'search'
    public string $mode = 'new';

    // Search query for existing patients
    public string $searchQuery = '';
    public ?int $selectedPatientId = null;

    // Form fields for new patient
    public string $name = '';
    public string $phone_number = '';
    public string $gender = 'female';
    public string $date_of_birth = '';
    public string $address = '';

    // Active visit created
    public ?int $createdVisitId = null;

    protected function rules(): array
    {
        if ($this->mode === 'new') {
            return [
                'name' => ['required', 'string', 'max:255'],
                'phone_number' => ['required', 'string', 'min:8', 'max:20'],
                'gender' => ['required', 'in:female,male'],
                'date_of_birth' => ['required', 'date', 'before:today'],
                'address' => ['nullable', 'string', 'max:500'],
            ];
        }

        return [
            'selectedPatientId' => ['required', 'exists:patients,id'],
        ];
    }

    public function mount(): void
    {
        $this->date_of_birth = Carbon::now()->subYears(25)->format('Y-m-d');
    }

    public function selectExistingPatient(int $id): void
    {
        $this->selectedPatientId = $id;
        $this->searchQuery = '';
    }

    public function resetSelection(): void
    {
        $this->selectedPatientId = null;
    }

    public function registerAndOpenConsent(PatientCodeGenerator $codeGenerator)
    {
        $this->validate();

        $clinic = Clinic::firstOrFail();

        $visit = DB::transaction(function () use ($clinic, $codeGenerator) {
            $patient = null;

            if ($this->mode === 'new') {
                // Generate sequential patient MRN code with lockForUpdate()
                $patientCode = $codeGenerator->generate($clinic);

                $patient = Patient::create([
                    'clinic_id' => $clinic->id,
                    'patient_code' => $patientCode,
                    'name' => trim($this->name),
                    'phone_number' => trim($this->phone_number),
                    'gender' => $this->gender,
                    'date_of_birth' => $this->date_of_birth,
                    'address' => trim($this->address),
                ]);
            } else {
                $patient = Patient::findOrFail($this->selectedPatientId);
            }

            // Create new visit encounter for this patient
            return Visit::create([
                'clinic_id' => $clinic->id,
                'patient_id' => $patient->id,
                'status' => 'registered',
                'consent_signed' => false,
                'trustmedis_sync_status' => 'pending',
            ]);
        });

        $this->createdVisitId = $visit->id;

        session()->flash('success', "Registrasi berhasil! Kode Pasien: {$visit->patient->patient_code}. Silakan buka Mode Tablet Pasien.");

        // Redirect to Patient Kiosk mode directly
        return redirect()->route('kiosk.show', ['visit' => $visit->id]);
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
                          ->orWhere('patient_code', 'like', $q);
                })
                ->limit(6)
                ->get();
        }

        $selectedPatient = $this->selectedPatientId
            ? Patient::find($this->selectedPatientId)
            : null;

        return view('livewire.patient-registration', [
            'clinic' => $clinic,
            'searchResults' => $searchResults,
            'selectedPatient' => $selectedPatient,
        ]);
    }
}
