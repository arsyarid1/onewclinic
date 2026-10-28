<?php

namespace App\Livewire;

use App\Models\Clinic;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.kiosk')]
class PatientKiosk extends Component
{
    public Visit $visit;

    public bool $agreementChecked = true;
    public bool $isCompleted = false;
    public string $staffPin = '';
    public string $pinError = '';

    public function mount(Visit $visit): void
    {
        $this->visit = $visit->loadMissing(['patient', 'clinic']);

        if ($this->visit->consent_signed) {
            $this->isCompleted = true;
        }
    }

    public function saveSignature(string $signatureDataUrl): void
    {
        if (empty($signatureDataUrl) || !str_starts_with($signatureDataUrl, 'data:image')) {
            $this->addError('signature', 'Mohon bubuhkan tanda tangan pada kotak yang telah disediakan.');
            return;
        }

        if (!$this->agreementChecked) {
            $this->addError('agreement', 'Anda harus mencentang persetujuan General Consent.');
            return;
        }

        try {
            // Process base64 signature image
            $imageParts = explode(';base64,', $signatureDataUrl);
            $imageTypeAux = explode('image/', $imageParts[0]);
            $imageType = $imageTypeAux[1] ?? 'png';
            $imageBase64 = base64_decode($imageParts[1]);

            $fileName = "signatures/consent_visit_{$this->visit->id}_" . time() . ".{$imageType}";
            Storage::disk('public')->put($fileName, $imageBase64);

            $this->visit->update([
                'consent_signed' => true,
                'consent_signature_path' => $fileName,
                'consent_signed_at' => Carbon::now(),
                'status' => 'consent_signed',
            ]);

            $this->isCompleted = true;
        } catch (\Throwable $e) {
            $this->addError('signature', 'Terjadi kesalahan saat menyimpan tanda tangan: ' . $e->getMessage());
        }
    }

    public function verifyStaffPin(): mixed
    {
        // Default staff PIN for kiosk exit security
        $validPin = '1234';

        if (trim($this->staffPin) === $validPin) {
            return redirect()->route('dashboard');
        }

        $this->pinError = 'PIN staf tidak valid. Silakan hubungi petugas resepsionis.';
        $this->staffPin = '';
        return null;
    }

    public function render()
    {
        $clinic = $this->visit->clinic ?? Clinic::first();

        return view('livewire.patient-kiosk', [
            'clinic' => $clinic,
            'patient' => $this->visit->patient,
        ]);
    }
}
