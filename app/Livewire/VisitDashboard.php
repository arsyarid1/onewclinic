<?php

namespace App\Livewire;

use App\Contracts\TrustmedisServiceInterface;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Visit;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class VisitDashboard extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function triggerSync(int $visitId, TrustmedisServiceInterface $trustmedisService): void
    {
        $visit = Visit::findOrFail($visitId);
        $success = $trustmedisService->syncVisit($visit);

        if ($success) {
            session()->flash('success', "Kunjungan #{$visit->id} berhasil disinkronisasi ke Trustmedis.");
        } else {
            session()->flash('error', "Gagal menyinkronkan kunjungan #{$visit->id}.");
        }
    }

    public function render()
    {
        $clinic = Clinic::first();
        $clinicId = $clinic?->id ?? 1;

        $query = Visit::with(['patient', 'doctor', 'nurse', 'treatments'])
            ->where('clinic_id', $clinicId)
            ->latest();

        if (!empty($this->statusFilter) && $this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (!empty($this->search)) {
            $search = '%' . $this->search . '%';
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('patient_code', 'like', $search)
                  ->orWhere('phone_number', 'like', $search);
            });
        }

        $visits = $query->paginate(10);

        // Summary counts
        $today = Carbon::today();
        $counts = [
            'total_today' => Visit::where('clinic_id', $clinicId)->whereDate('created_at', $today)->count(),
            'registered' => Visit::where('clinic_id', $clinicId)->where('status', 'registered')->count(),
            'consent_signed' => Visit::where('clinic_id', $clinicId)->where('status', 'consent_signed')->count(),
            'waiting_doctor' => Visit::where('clinic_id', $clinicId)->whereIn('status', ['waiting_doctor', 'in_treatment'])->count(),
            'completed' => Visit::where('clinic_id', $clinicId)->where('status', 'completed')->count(),
            'synced' => Visit::where('clinic_id', $clinicId)->where('trustmedis_sync_status', 'synced')->count(),
        ];

        return view('livewire.visit-dashboard', [
            'visits' => $visits,
            'counts' => $counts,
            'currentClinic' => $clinic,
        ]);
    }
}
