<?php

namespace App\Livewire;

use App\Models\Clinic;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class ClinicSettings extends Component
{
    use WithFileUploads;

    public Clinic $clinic;

    public string $name = '';
    public string $clinic_code = '';
    public string $primary_color = '#0284C7';
    public string $secondary_color = '#0EA5E9';
    public string $address = '';
    public string $phone = '';
    public string $general_consent_text = '';
    public $logo;

    public function mount(): void
    {
        $this->clinic = Clinic::firstOrCreate(
            ['clinic_code' => 'ONEW'],
            [
                'name' => 'Onew Clinic',
                'clinic_code' => 'ONEW',
                'primary_color' => '#0284C7',
                'secondary_color' => '#0EA5E9',
                'address' => 'Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan',
                'phone' => '0812-9988-7766',
            ]
        );

        $this->name = $this->clinic->name;
        $this->clinic_code = $this->clinic->clinic_code;
        $this->primary_color = $this->clinic->primary_color ?? '#0284C7';
        $this->secondary_color = $this->clinic->secondary_color ?? '#0EA5E9';
        $this->address = $this->clinic->address ?? '';
        $this->phone = $this->clinic->phone ?? '';
        $this->general_consent_text = $this->clinic->general_consent_text ?? '';
    }

    public function saveSettings(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'clinic_code' => ['required', 'string', 'size:4'],
            'primary_color' => ['required', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'secondary_color' => ['nullable', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'general_consent_text' => ['required', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'name' => trim($this->name),
            'clinic_code' => strtoupper(trim($this->clinic_code)),
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'address' => trim($this->address),
            'phone' => trim($this->phone),
            'general_consent_text' => trim($this->general_consent_text),
        ];

        if ($this->logo) {
            $path = $this->logo->store('clinic', 'public');
            $data['logo_path'] = $path;
        }

        $this->clinic->update($data);

        session()->flash('success', 'Konfigurasi White-Label klinik berhasil diperbarui! Perubahan warna, nama, dan teks persetujuan telah diterapkan.');
    }

    public function render()
    {
        return view('livewire.clinic-settings');
    }
}
