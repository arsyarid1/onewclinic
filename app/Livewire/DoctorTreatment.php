<?php

namespace App\Livewire;

use App\Contracts\TrustmedisServiceInterface;
use App\Jobs\SyncVisitJob;
use App\Models\Clinic;
use App\Models\Treatment;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class DoctorTreatment extends Component
{
    use WithFileUploads;

    public ?int $selectedVisitId = null;

    // Selected treatments: array of treatment IDs
    public array $selectedTreatments = [];

    // Clinical notes keyed by treatment ID: [treatment_id => notes]
    public array $treatmentNotes = [];

    // General doctor encounter diagnosis / notes
    public string $doctorNotes = '';

    // Camera photo after
    public $photo_after;

    // Modal state after completion
    public bool $showSuccessModal = false;
    public ?int $completedVisitId = null;

    public function mount(?int $visit_id = null): void
    {
        $clinic = Clinic::first();

        if ($visit_id) {
            $this->selectVisit($visit_id);
        } else {
            $defaultVisit = Visit::where('clinic_id', $clinic?->id)
                ->whereIn('status', ['waiting_doctor', 'in_treatment'])
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
        $this->showSuccessModal = false;
        $visit = Visit::with(['treatments'])->find($visitId);

        if ($visit) {
            // Update to in_treatment if previously waiting_doctor
            if ($visit->status === 'waiting_doctor') {
                $visit->update(['status' => 'in_treatment']);
            }

            // Populate existing treatments if any
            $this->selectedTreatments = $visit->treatments->pluck('id')->map(fn($id) => (string) $id)->toArray();
            $this->treatmentNotes = [];
            foreach ($visit->treatments as $tr) {
                $this->treatmentNotes[$tr->id] = $tr->pivot->notes ?? '';
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

        $visit = Visit::with(['patient', 'clinic'])->findOrFail($this->selectedVisitId);

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

        // Mark visit as completed
        $visit->status = 'completed';
        $visit->save();

        // Trigger Trustmedis Mock Sync
        $trustmedisService->syncVisit($visit);

        // Dispatch SyncVisitJob (simulasi background job & auto cleanup hook)
        SyncVisitJob::dispatch($visit);

        $this->completedVisitId = $visit->id;
        $this->showSuccessModal = true;
        session()->flash('success', "Kunjungan #{$visit->id} selesai dan berhasil disinkronisasi ke Trustmedis!");

        $this->photo_after = null;
    }

    public function render()
    {
        $clinic = Clinic::first();
        $clinicId = $clinic?->id ?? 1;

        $waitingVisits = Visit::with(['patient'])
            ->where('clinic_id', $clinicId)
            ->whereIn('status', ['waiting_doctor', 'in_treatment'])
            ->oldest()
            ->get();

        $treatments = Treatment::where('clinic_id', $clinicId)->get();

        $currentVisit = $this->selectedVisitId
            ? Visit::with(['patient', 'doctor', 'treatments'])->find($this->selectedVisitId)
            : null;

        return view('livewire.doctor-treatment', [
            'waitingVisits' => $waitingVisits,
            'treatments' => $treatments,
            'currentVisit' => $currentVisit,
        ]);
    }
}
