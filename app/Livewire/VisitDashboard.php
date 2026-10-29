<?php

namespace App\Livewire;

use App\Contracts\TrustmedisServiceInterface;
use App\Models\Clinic;
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

    public function render()
    {
        $clinic = Clinic::first();
        $clinicId = $clinic?->id ?? 1;

        $query = Visit::with(['patient', 'doctor', 'nurse', 'treatments'])
            ->where('clinic_id', $clinicId)
            ->latest();

        if ($this->statusFilter === 'waiting') {
            $query->whereIn('status', ['registered', 'consent_signed', 'waiting_doctor', 'in_treatment']);
        } elseif ($this->statusFilter === 'completed') {
            $query->where('status', 'completed');
        } elseif (!empty($this->statusFilter) && $this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (!empty($this->search)) {
            $search = '%' . $this->search . '%';
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('patient_code', 'like', $search)
                  ->orWhere('phone_number', 'like', $search)
                  ->orWhere('nik', 'like', $search);
            });
        }

        $visits = $query->paginate(10);

        // Summary counts
        $today = Carbon::today();
        $counts = [
            'total_today' => Visit::where('clinic_id', $clinicId)->whereDate('created_at', $today)->count(),
            'waiting' => Visit::where('clinic_id', $clinicId)->whereIn('status', ['registered', 'consent_signed', 'waiting_doctor', 'in_treatment'])->count(),
            'completed' => Visit::where('clinic_id', $clinicId)->where('status', 'completed')->count(),
        ];

        return view('livewire.visit-dashboard', [
            'visits' => $visits,
            'counts' => $counts,
            'currentClinic' => $clinic,
        ]);
    }
}
