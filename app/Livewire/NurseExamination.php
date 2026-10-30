<?php

namespace App\Livewire;

use App\Models\Clinic;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class NurseExamination extends Component
{
    use WithFileUploads;

    public ?int $selectedVisitId = null;

    // Form inputs
    public ?int $doctor_id = null;
    public string $assistant_name = 'Ns. Ratna Dewi, S.Kep';
    public $photo_before;

    public function mount(?int $visit_id = null): void
    {
        $clinic = Clinic::first();
        $firstDoctor = User::where('role', 'doctor')->first();
        if ($firstDoctor) {
            $this->doctor_id = $firstDoctor->id;
        }

        if ($visit_id) {
            $this->selectVisit($visit_id);
        } else {
            // Select the oldest active visit needing nurse examination
            $defaultVisit = Visit::where('clinic_id', $clinic?->id)
                ->whereIn('status', ['consent_signed', 'registered'])
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
        $visit = Visit::find($visitId);

        if ($visit) {
            $this->doctor_id = $visit->doctor_id ?? $this->doctor_id;
            $this->assistant_name = $visit->assistant_name ?: $this->assistant_name;
        }
    }

    public function saveExamination(): void
    {
        $this->validate([
            'selectedVisitId' => ['required', 'exists:visits,id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'assistant_name' => ['required', 'string', 'max:255'],
            'photo_before' => ['nullable', 'image', 'max:10240'], // 10MB max
        ]);

        $visit = Visit::findOrFail($this->selectedVisitId);

        $nurseUser = User::where('role', 'nurse')->first();

        $updateData = [
            'doctor_id' => $this->doctor_id,
            'nurse_id' => $nurseUser?->id,
            'assistant_name' => trim($this->assistant_name),
            'status' => 'waiting_doctor',
        ];

        // Store Photo Before if uploaded
        if ($this->photo_before) {
            $filename = "photos/before/before_visit_{$visit->id}_" . time() . '.' . $this->photo_before->getClientOriginalExtension();
            $path = $this->photo_before->storeAs('photos/before', basename($filename), 'public');
            $updateData['photo_before_path'] = $path;
        }

        $visit->update($updateData);

        $this->dispatch('notify', message: "Pemeriksaan awal & foto pasien {$visit->patient->name} berhasil disimpan!");
        session()->flash('success', "Pemeriksaan awal & Foto Before pasien {$visit->patient->name} berhasil disimpan! Pasien siap masuk ke Ruang Dokter.");

        // Reset uploaded file
        $this->photo_before = null;

        // Auto move to next waiting patient or dashboard
        $nextVisit = Visit::where('status', 'consent_signed')->oldest()->first();
        if ($nextVisit) {
            $this->selectVisit($nextVisit->id);
        }
    }

    public function render()
    {
        $clinic = Clinic::first();
        $clinicId = $clinic?->id ?? 1;

        // Queue visits waiting for nurse checkup
        $waitingVisits = Visit::with(['patient'])
            ->where('clinic_id', $clinicId)
            ->whereIn('status', ['consent_signed', 'registered'])
            ->oldest()
            ->get();

        $doctors = User::where('role', 'doctor')->get();

        $currentVisit = $this->selectedVisitId
            ? Visit::with(['patient', 'doctor'])->find($this->selectedVisitId)
            : null;

        return view('livewire.nurse-examination', [
            'waitingVisits' => $waitingVisits,
            'doctors' => $doctors,
            'currentVisit' => $currentVisit,
        ]);
    }
}
