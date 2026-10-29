<?php

namespace App\Livewire;

use App\Models\Clinic;
use App\Models\Consumable;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class ClinicSettings extends Component
{
    use WithFileUploads;

    public Clinic $clinic;

    public string $activeTab = 'general'; // 'general', 'staff', 'consumables'

    // Pengaturan Umum Klinik
    public string $name = '';
    public string $clinic_code = '';
    public string $primary_color = '#0284C7';
    public string $secondary_color = '#0EA5E9';
    public string $address = '';
    public string $phone = '';
    public string $general_consent_text = '';
    public $logo;

    // Form Tambah Dokter & Perawat
    public string $staff_name = '';
    public string $staff_email = '';
    public string $staff_password = 'password';
    public string $staff_role = 'doctor'; // 'doctor' | 'nurse'

    // Form Tambah Bahan Baku (BHP)
    public string $consumable_name = '';
    public string $consumable_category = 'Disposable';
    public string $consumable_unit = 'pcs';
    public int $consumable_default_qty = 1;

    public function mount(): void
    {
        // Akses khusus Super Admin sesuai instruksi
        if (Auth::check() && !Auth::user()->isSuperadmin()) {
            abort(403, 'Akses terbatas: Hanya Super Admin yang berwenang mengakses halaman pengaturan.');
        }

        $this->clinic = Clinic::firstOrCreate(
            ['clinic_code' => 'ONEW'],
            [
                'name' => 'Onew Clinic',
                'clinic_code' => 'ONEW',
                'primary_color' => '#0284C7',
                'secondary_color' => '#0EA5E9',
                'address' => 'Jl. Laksana I no 6, Kebayoran Baru, Jaksel 12180',
                'phone' => '+62 8119 6977 886 / +62 21382 506 94',
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

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
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

        session()->flash('success', 'Konfigurasi White-Label klinik berhasil diperbarui!');
    }

    public function addStaff(): void
    {
        $this->validate([
            'staff_name' => ['required', 'string', 'max:255'],
            'staff_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'staff_role' => ['required', 'in:doctor,nurse,receptionist'],
            'staff_password' => ['required', 'string', 'min:6'],
        ], [
            'staff_name.required' => 'Nama tenaga medis/staf wajib diisi.',
            'staff_email.required' => 'Email staf wajib diisi.',
            'staff_email.unique' => 'Email ini sudah terdaftar untuk pengguna lain.',
            'staff_password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        User::create([
            'clinic_id' => $this->clinic->id,
            'name' => trim($this->staff_name),
            'email' => trim($this->staff_email),
            'role' => $this->staff_role,
            'password' => Hash::make($this->staff_password),
        ]);

        $roleLabel = match($this->staff_role) {
            'doctor' => 'Dokter',
            'nurse' => 'Perawat',
            default => 'Resepsionis',
        };

        $this->reset(['staff_name', 'staff_email']);
        $this->staff_password = 'password';

        session()->flash('success', "Tenaga medis baru ({$roleLabel}) berhasil ditambahkan ke klinik!");
    }

    public function deleteStaff(int $id): void
    {
        $staff = User::where('clinic_id', $this->clinic->id)->find($id);
        if ($staff && $staff->id !== Auth::id()) {
            $name = $staff->name;
            $staff->delete();
            session()->flash('success', "Data staf {$name} berhasil dihapus.");
        }
    }

    public function addConsumable(): void
    {
        $this->validate([
            'consumable_name' => ['required', 'string', 'max:255'],
            'consumable_category' => ['nullable', 'string', 'max:100'],
            'consumable_unit' => ['required', 'string', 'max:50'],
            'consumable_default_qty' => ['required', 'integer', 'min:1'],
        ], [
            'consumable_name.required' => 'Nama bahan baku (BHP) wajib diisi.',
        ]);

        Consumable::create([
            'clinic_id' => $this->clinic->id,
            'name' => strtoupper(trim($this->consumable_name)),
            'category' => trim($this->consumable_category) ?: 'BHP',
            'unit' => trim($this->consumable_unit) ?: 'pcs',
            'default_quantity' => $this->consumable_default_qty,
            'is_active' => true,
        ]);

        $this->reset(['consumable_name']);
        $this->consumable_default_qty = 1;

        session()->flash('success', 'Bahan baku / BHP baru berhasil ditambahkan ke katalog klinik!');
    }

    public function deleteConsumable(int $id): void
    {
        $item = Consumable::where('clinic_id', $this->clinic->id)->find($id);
        if ($item) {
            $name = $item->name;
            $item->delete();
            session()->flash('success', "Bahan baku {$name} berhasil dihapus dari katalog.");
        }
    }

    public function render()
    {
        $doctors = User::where('clinic_id', $this->clinic->id)->where('role', 'doctor')->get();
        $nurses = User::where('clinic_id', $this->clinic->id)->where('role', 'nurse')->get();
        $receptionists = User::where('clinic_id', $this->clinic->id)->where('role', 'receptionist')->get();
        $consumables = Consumable::where('clinic_id', $this->clinic->id)->orderBy('name')->get();

        return view('livewire.clinic-settings', [
            'doctors' => $doctors,
            'nurses' => $nurses,
            'receptionists' => $receptionists,
            'consumables' => $consumables,
        ]);
    }
}
