<div class="min-h-screen flex flex-col justify-between" x-data="formFlowApp()">
    <!-- Top Step Progress Navigation Bar (PWA Sticky) -->
    <div class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 py-2.5 shadow-xs no-print">
        <div class="max-w-4xl mx-auto flex flex-wrap items-center justify-between gap-3">
            <!-- Brand & Step Indicator -->
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 group text-slate-700 hover:text-slate-900 transition-colors" title="Kembali ke Dashboard Kunjungan">
                    <img src="{{ asset('logo.svg') }}" alt="ONEW Clinic" class="h-6 w-auto object-contain">
                    <span class="font-bold text-xs tracking-tight text-slate-900">FORM FLOW</span>
                </a>
                <span class="text-slate-300">|</span>
                <span class="text-xs font-semibold text-slate-800 hidden md:inline">
                    @if($currentStep === 1) 1. Formulir Pendaftaran
                    @elseif($currentStep === 2) 2. General Consent
                    @elseif($currentStep === 3) 3. Persetujuan Tindakan Medis
                    @elseif($currentStep === 4) 4. Foto Before (5 Sisi)
                    @elseif($currentStep === 5) 5. Tindakan & BHP Harian
                    @elseif($currentStep === 6) 6. Foto After (5 Sisi)
                    @elseif($currentStep === 7) 7. Ringkasan Kunjungan
                    @endif
                </span>
            </div>

            <!-- Patient Selector, Dashboard Action & Step Pills -->
            <div class="flex items-center gap-2">
                <!-- Dropdown Pasien / Antrian Pemeriksaan Dokter & Perawat -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border border-slate-300 bg-white hover:bg-slate-50 text-slate-800 shadow-2xs active:scale-95 transition-all">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="max-w-[110px] sm:max-w-[180px] truncate">{{ $name ?: 'Pilih Pasien' }}</span>
                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-72 sm:w-80 rounded-2xl bg-white shadow-apple-elevated border border-slate-200/90 p-2 z-50 text-xs max-h-80 overflow-y-auto">
                        <div class="px-2.5 py-1.5 border-b border-slate-100 font-bold text-slate-400 text-[10px] uppercase tracking-wider flex items-center justify-between">
                            <span>Antrian Kunjungan Pasien</span>
                            <a href="{{ route('clinical.form.flow') }}" class="text-sky-600 hover:text-sky-800 font-bold lowercase">+ baru</a>
                        </div>
                        @if(isset($todayVisits) && $todayVisits->isNotEmpty())
                            @foreach($todayVisits as $tv)
                                <button type="button"
                                        wire:click="selectVisit({{ $tv->id }})"
                                        @click="open = false"
                                        class="w-full text-left px-2.5 py-2 rounded-xl hover:bg-slate-50 flex flex-col gap-0.5 transition-colors {{ $visitId === $tv->id ? 'bg-sky-50/80 text-sky-950 font-semibold border border-sky-200/80' : 'text-slate-800' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs truncate max-w-[180px]">{{ $tv->patient?->name }}</span>
                                        <span class="text-[10px] font-mono text-slate-500">{{ $tv->manual_patient_code ?: $tv->patient?->patient_code }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-slate-400 mt-0.5">
                                        <span>{{ $tv->visit_purpose ?: 'Estetika' }}</span>
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-semibold {{ $tv->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                            {{ $tv->status === 'completed' ? 'Selesai' : 'Dalam Antrian' }}
                                        </span>
                                    </div>
                                </button>
                            @endforeach
                        @else
                            <div class="py-4 text-center text-slate-400 text-xs">Belum ada antrian pasien hari ini.</div>
                        @endif
                    </div>
                </div>

                <!-- Dashboard Quick Link -->
                <a href="{{ route('dashboard') }}" class="text-xs text-slate-600 hover:text-slate-900 px-2.5 py-1 rounded-full border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-all font-medium hidden sm:inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Dashboard</span>
                </a>

                <!-- Mini Progress Pills -->
                <div class="flex items-center gap-1 overflow-x-auto py-1 pl-1 border-l border-slate-200">
                    @for($s = 1; $s <= 6; $s++)
                        <button type="button" wire:click="goToStep({{ $s }})"
                                title="Langkah {{ $s }}"
                                class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold transition-all {{ $currentStep === $s ? 'bg-sky-600 text-white shadow-xs scale-105' : ($currentStep > $s ? 'bg-emerald-100 text-emerald-700 border border-emerald-300' : 'bg-slate-100 text-slate-400 hover:bg-slate-200') }}">
                            @if($currentStep > $s) ✓ @else {{ $s }} @endif
                        </button>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <!-- Paper Form Main Container -->
    <div class="max-w-4xl w-full mx-auto p-3 sm:p-6 lg:p-8 flex-1">
        <div class="bg-white rounded-2xl shadow-apple border border-slate-300/80 p-5 sm:p-9 paper-form transition-all">

            @if($existingVisit && $existingVisit->status === 'completed')
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs text-emerald-900 no-print">
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="font-bold">Mode Pembaruan Data Kunjungan:</span>
                        <span>Dokter dan perawat dapat melengkapi data formulir dan foto kapan saja.</span>
                    </div>
                    @if(!empty($existingVisit->edit_logs))
                        <span class="text-[10px] font-semibold text-emerald-800 bg-emerald-100/80 px-2.5 py-0.5 rounded-full">
                            {{ count($existingVisit->edit_logs) }} Riwayat Edit Tercatat
                        </span>
                    @endif
                </div>
            @endif

            <!-- ==================================================== -->
            <!-- STEP 1: FORMULIR PENDAFTARAN PASIEN (Hal 1 & 2 PDF) -->
            <!-- ==================================================== -->
            @if($currentStep === 1)
                <!-- Kop Surat Header Form -->
                <div class="flex flex-col sm:flex-row items-start justify-between pb-4 border-b-2 border-slate-900 gap-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('logo.svg') }}" alt="ONEW Clinic" class="h-14 w-auto object-contain">
                    </div>
                    <div class="text-left sm:text-right text-[11px] text-slate-600 leading-tight">
                        <p class="font-bold text-slate-900 text-xs">ONEW Klinik Utama</p>
                        <p>Jl. Laksana I no. 6, Kebayoran Baru, Jakarta 12180, Indonesia</p>
                        <p class="mt-0.5">website: <span class="font-medium text-slate-800">onewclinic.com</span> &bull; email: <span class="font-medium text-slate-800">onewclinic@gmail.com</span></p>
                        <p>mobile: <span class="font-semibold text-slate-900">+62 811 1660 9589</span> &bull; phone: <span class="font-semibold text-slate-900">+62 21382 506 94</span></p>
                    </div>
                </div>

                <!-- Form Title & Manual Top-Right Box (No. Rekam Medis & NO. ID) -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mt-5 pb-5 border-b border-slate-200">
                    <div class="md:col-span-7 flex flex-col justify-center">
                        <h1 class="text-lg sm:text-xl font-extrabold uppercase tracking-tight text-slate-900">
                            Formulir Pendaftaran Pasien
                        </h1>
                        <span class="text-xs font-semibold text-slate-500">ONEW Klinik Utama</span>
                    </div>

                    <!-- Kotak Manual No. Rekam Medis, Tanggal Daftar & NO. ID -->
                    <div class="md:col-span-5 border-2 border-slate-900 rounded-xl p-3 bg-slate-50/70 space-y-2">
                        <div class="flex items-center justify-between gap-2">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-700">No. Rekam Medis <span class="text-rose-600">*</span></label>
                            <input type="text"
                                   wire:model="manual_patient_code"
                                   placeholder="Contoh: ONEW26000022"
                                   class="px-2.5 py-1 text-xs font-mono font-bold text-slate-900 border border-slate-400 bg-white rounded-md text-right focus:ring-1 focus:ring-slate-900 focus:outline-none uppercase w-40">
                        </div>
                        @error('manual_patient_code') <span class="text-[10px] text-rose-600 font-semibold block text-right">{{ $message }}</span> @enderror

                        <div class="flex items-center justify-between gap-2">
                            <label class="text-[11px] font-semibold text-slate-600">Tanggal Daftar</label>
                            <input type="date"
                                   wire:model="registration_date"
                                   class="px-2 py-0.5 text-xs text-slate-800 border border-slate-300 bg-white rounded-md text-right w-40">
                        </div>

                        <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-200">
                            <div>
                                <label class="text-[11px] font-bold text-slate-900 uppercase tracking-wider">NO. ID</label>
                                <span class="text-[9px] text-slate-400 block -mt-0.5">(diisi perawat)</span>
                            </div>
                            <input type="text"
                                   wire:model="inbody_id"
                                   placeholder="Nomor ID"
                                   class="px-2.5 py-1 text-xs font-mono font-semibold text-slate-800 border border-slate-400 bg-white rounded-md text-right focus:ring-1 focus:ring-slate-900 focus:outline-none w-40">
                        </div>
                    </div>
                </div>

                <!-- Bagian A: IDENTITAS PASIEN -->
                <div class="mt-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider bg-slate-900 text-white px-3 py-1 rounded-sm mb-3">
                        A. Identitas Pasien
                    </h3>

                    <div class="divide-y divide-slate-200 border border-slate-300 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center">
                            <span class="sm:col-span-4 font-semibold text-slate-700">Nama Lengkap sesuai Identitas <span class="text-rose-500">*</span></span>
                            <div class="sm:col-span-8">
                                <input type="text" wire:model="name" placeholder="Nama lengkap sesuai KTP / Paspor" class="w-full px-3 py-1.5 border border-slate-300 rounded uppercase font-semibold text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                                @error('name') <span class="text-[10px] text-rose-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center bg-slate-50/50">
                            <span class="sm:col-span-4 font-semibold text-slate-700">NIK / No. Paspor</span>
                            <div class="sm:col-span-8">
                                <input type="text" wire:model="nik_passport" placeholder="Nomor Induk Kependudukan atau Nomor Paspor" class="w-full px-3 py-1.5 border border-slate-300 rounded font-mono text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center">
                            <span class="sm:col-span-4 font-semibold text-slate-700">Tempat, Tanggal Lahir</span>
                            <div class="sm:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <input type="text" wire:model="birth_place" placeholder="Kota Kelahiran" class="px-3 py-1.5 border border-slate-300 rounded text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                                <input type="date" wire:model="date_of_birth" class="px-3 py-1.5 border border-slate-300 rounded text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center bg-slate-50/50">
                            <span class="sm:col-span-4 font-semibold text-slate-700">Jenis Kelamin</span>
                            <div class="sm:col-span-8 flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Laki-laki" wire:model="gender" class="text-slate-900 focus:ring-slate-900">
                                    <span>Laki-laki</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Perempuan" wire:model="gender" class="text-slate-900 focus:ring-slate-900">
                                    <span>Perempuan</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center">
                            <span class="sm:col-span-4 font-semibold text-slate-700">Status Perkawinan</span>
                            <div class="sm:col-span-8 flex flex-wrap items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Belum Menikah" wire:model="marital_status" class="text-slate-900 focus:ring-slate-900">
                                    <span>Belum Menikah</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Menikah" wire:model="marital_status" class="text-slate-900 focus:ring-slate-900">
                                    <span>Menikah</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Cerai" wire:model="marital_status" class="text-slate-900 focus:ring-slate-900">
                                    <span>Cerai</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-start bg-slate-50/50">
                            <span class="sm:col-span-4 font-semibold text-slate-700 pt-1.5">Alamat sesuai Identitas</span>
                            <div class="sm:col-span-8">
                                <textarea wire:model="id_address" rows="2" placeholder="Alamat lengkap tertera di KTP / Identitas" class="w-full px-3 py-1.5 border border-slate-300 rounded text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none"></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-start">
                            <span class="sm:col-span-4 font-semibold text-slate-700 pt-1.5">Alamat Domisili</span>
                            <div class="sm:col-span-8 space-y-2">
                                <div class="flex items-center gap-4">
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model.live="is_domicile_same" class="rounded text-slate-900 focus:ring-slate-900">
                                        <span class="font-medium text-slate-800">Sama dengan identitas</span>
                                    </label>
                                </div>
                                @if(!$is_domicile_same)
                                    <textarea wire:model="domicile_address" rows="2" placeholder="Tuliskan alamat domisili tempat tinggal saat ini" class="w-full px-3 py-1.5 border border-slate-300 rounded text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none"></textarea>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center bg-slate-50/50">
                            <span class="sm:col-span-4 font-semibold text-slate-700">No. HP / WhatsApp <span class="text-rose-500">*</span></span>
                            <div class="sm:col-span-8">
                                <input type="text" wire:model="phone_number" placeholder="Contoh: 0811958541" class="w-full px-3 py-1.5 border border-slate-300 rounded font-mono font-medium text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                                @error('phone_number') <span class="text-[10px] text-rose-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center">
                            <span class="sm:col-span-4 font-semibold text-slate-700">Email</span>
                            <div class="sm:col-span-8">
                                <input type="email" wire:model="email" placeholder="alamat.email@contoh.com" class="w-full px-3 py-1.5 border border-slate-300 rounded text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center bg-slate-50/50">
                            <span class="sm:col-span-4 font-semibold text-slate-700">Pekerjaan</span>
                            <div class="sm:col-span-8">
                                <input type="text" wire:model="occupation" placeholder="Profesi / Pekerjaan Pasien" class="w-full px-3 py-1.5 border border-slate-300 rounded text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian B: PENANGGUNG JAWAB / KONTAK DARURAT -->
                <div class="mt-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider bg-slate-900 text-white px-3 py-1 rounded-sm mb-3">
                        B. Penanggung Jawab / Kontak Darurat
                    </h3>

                    <div class="divide-y divide-slate-200 border border-slate-300 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center">
                            <span class="sm:col-span-4 font-semibold text-slate-700">Nama</span>
                            <div class="sm:col-span-8">
                                <input type="text" wire:model="emergency_name" placeholder="Nama wali / keluarga penanggung jawab" class="w-full px-3 py-1.5 border border-slate-300 rounded text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center bg-slate-50/50">
                            <span class="sm:col-span-4 font-semibold text-slate-700">Hubungan dengan Pasien</span>
                            <div class="sm:col-span-8">
                                <input type="text" wire:model="emergency_relation" placeholder="Contoh: Orang Tua, Suami/Istri, Saudara Kandung" class="w-full px-3 py-1.5 border border-slate-300 rounded text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center">
                            <span class="sm:col-span-4 font-semibold text-slate-700">No. HP / WhatsApp</span>
                            <div class="sm:col-span-8">
                                <input type="text" wire:model="emergency_phone" placeholder="Nomor kontak darurat" class="w-full px-3 py-1.5 border border-slate-300 rounded font-mono text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian C: DATA PENJAMIN / PEMBAYARAN -->
                <div class="mt-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider bg-slate-900 text-white px-3 py-1 rounded-sm mb-3">
                        C. Data Penjamin / Pembayaran
                    </h3>

                    <div class="divide-y divide-slate-200 border border-slate-300 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center">
                            <span class="sm:col-span-4 font-semibold text-slate-700">Jenis Pembayaran</span>
                            <div class="sm:col-span-8 flex flex-wrap items-center gap-5">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Pribadi / Umum" wire:model="payment_type" class="text-slate-900 focus:ring-slate-900">
                                    <span>Pribadi / Umum</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Asuransi" wire:model="payment_type" class="text-slate-900 focus:ring-slate-900">
                                    <span>Asuransi</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Perusahaan" wire:model="payment_type" class="text-slate-900 focus:ring-slate-900">
                                    <span>Perusahaan</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Lainnya" wire:model="payment_type" class="text-slate-900 focus:ring-slate-900">
                                    <span>Lainnya</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center bg-slate-50/50">
                            <span class="sm:col-span-4 font-semibold text-slate-700">Nama Asuransi / Perusahaan</span>
                            <div class="sm:col-span-8">
                                <input type="text" wire:model="insurance_company_name" placeholder="Jika menggunakan penjamin / asuransi" class="w-full px-3 py-1.5 border border-slate-300 rounded text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center">
                            <span class="sm:col-span-4 font-semibold text-slate-700">No. Peserta / No. Polis</span>
                            <div class="sm:col-span-8">
                                <input type="text" wire:model="insurance_card_number" placeholder="Nomor polis asuransi atau nomor keanggotaan" class="w-full px-3 py-1.5 border border-slate-300 rounded font-mono text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian D: INFORMASI PELAYANAN (Hal 2 PDF) -->
                <div class="mt-8 pt-6 border-t-2 border-slate-300">
                    <h3 class="text-xs font-bold uppercase tracking-wider bg-slate-900 text-white px-3 py-1 rounded-sm mb-3">
                        D. Informasi Pelayanan
                    </h3>

                    <div class="divide-y divide-slate-200 border border-slate-300 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center">
                            <span class="sm:col-span-4 font-semibold text-slate-700">Status Pasien</span>
                            <div class="sm:col-span-8 flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Pasien Baru" wire:model="patient_service_status" class="text-slate-900 focus:ring-slate-900">
                                    <span>Pasien Baru</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Pasien Lama" wire:model="patient_service_status" class="text-slate-900 focus:ring-slate-900">
                                    <span>Pasien Lama</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center bg-slate-50/50">
                            <span class="sm:col-span-4 font-semibold text-slate-700">Tujuan Kunjungan</span>
                            <div class="sm:col-span-8 flex flex-wrap items-center gap-4">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Rawat Jalan / Dokter Umum" wire:model="visit_purpose" class="text-slate-900 focus:ring-slate-900">
                                    <span>Rawat Jalan / Umum</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Estetika" wire:model="visit_purpose" class="text-slate-900 focus:ring-slate-900">
                                    <span>Estetika</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Gigi" wire:model="visit_purpose" class="text-slate-900 focus:ring-slate-900">
                                    <span>Gigi</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" value="Spesialis" wire:model="visit_purpose" class="text-slate-900 focus:ring-slate-900">
                                    <span>Spesialis</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-center">
                            <span class="sm:col-span-4 font-semibold text-slate-700">Dokter / Poli Tujuan</span>
                            <div class="sm:col-span-8">
                                <input type="text" wire:model="destination_doctor_poli" placeholder="" class="w-full px-3 py-1.5 border border-slate-300 rounded text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 p-2.5 gap-2 items-start bg-slate-50/50">
                            <span class="sm:col-span-4 font-semibold text-slate-700 pt-1.5">Keluhan Singkat / Keperluan</span>
                            <div class="sm:col-span-8">
                                <textarea wire:model="chief_complaint" rows="2" placeholder="Tuliskan keluhan atau tujuan perawatan yang diinginkan pasien" class="w-full px-3 py-1.5 border border-slate-300 rounded text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian E: INFORMASI TAMBAHAN -->
                <div class="mt-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider bg-slate-900 text-white px-3 py-1 rounded-sm mb-3">
                        E. Informasi Tambahan
                    </h3>

                    <div class="border border-slate-300 p-3 text-xs">
                        <span class="font-semibold text-slate-700 block mb-2">Mengetahui Klinik dari:</span>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" value="Keluarga / Teman" wire:model="referral_source" class="text-slate-900 focus:ring-slate-900">
                                <span>Keluarga / Teman</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" value="Instagram / TikTok" wire:model="referral_source" class="text-slate-900 focus:ring-slate-900">
                                <span>Instagram / TikTok</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" value="Google / Internet" wire:model="referral_source" class="text-slate-900 focus:ring-slate-900">
                                <span>Google / Internet</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" value="Iklan" wire:model="referral_source" class="text-slate-900 focus:ring-slate-900">
                                <span>Iklan</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" value="Rujukan Dokter" wire:model="referral_source" class="text-slate-900 focus:ring-slate-900">
                                <span>Rujukan Dokter</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" value="Lainnya" wire:model="referral_source" class="text-slate-900 focus:ring-slate-900">
                                <span>Lainnya</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Bagian F: PERNYATAAN DAN PERSETUJUAN PASIEN -->
                <div class="mt-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider bg-slate-900 text-white px-3 py-1 rounded-sm mb-3">
                        F. Pernyataan dan Persetujuan Pasien
                    </h3>

                    <div class="border border-slate-300 p-4 text-xs text-slate-700 leading-relaxed space-y-2">
                        <p>1. Saya menyatakan bahwa seluruh data yang saya berikan dalam formulir ini adalah benar dan dapat dipertanggungjawabkan.</p>
                        <p>2. Saya bersedia memberikan informasi yang diperlukan untuk proses pendaftaran, pelayanan kesehatan, administrasi, penagihan, dan pencatatan rekam medis sesuai ketentuan yang berlaku.</p>
                        <p>3. Saya memahami bahwa informasi medis yang lebih rinci akan diperoleh melalui anamnesis, pemeriksaan, dan/atau formulir persetujuan tindakan medis sesuai jenis pelayanan yang saya terima.</p>
                        <p>4. Saya bersedia mematuhi ketentuan pelayanan dan tata tertib klinik yang berlaku.</p>

                        <div class="pt-3 border-t border-slate-200 mt-2">
                            <span class="font-bold text-slate-900 block mb-1.5">Persetujuan menerima informasi/komunikasi administratif melalui telepon atau WhatsApp:</span>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="whatsapp_comm_consent" value="1" wire:model.live="whatsapp_comm_consent" class="text-slate-900 focus:ring-slate-900">
                                    <span class="font-semibold text-emerald-800">Saya SETUJU</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="whatsapp_comm_consent" value="0" wire:model.live="whatsapp_comm_consent" class="text-slate-900 focus:ring-slate-900">
                                    <span class="text-slate-600">Saya TIDAK SETUJU</span>
                                </label>
                            </div>
                        </div>

                        <!-- Kotak Tanda Tangan Hal 2 -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 mt-3 border-t border-slate-200">
                            <!-- Kiri: Pasien / Penanggung Jawab -->
                            <div class="border border-slate-300 p-3 rounded-lg text-center bg-slate-50/50">
                                <span class="font-bold text-slate-800 block mb-2">Pasien / Penanggung Jawab</span>
                                <div class="relative bg-white border-2 border-slate-300 rounded-lg mb-2 shadow-inner">
                                    <canvas x-init="setupPad($el, 'canvas-reg-patient')" id="canvas-reg-patient" class="w-full h-44 sm:h-52 touch-none cursor-crosshair"></canvas>
                                    <button type="button" @click="clearSignature('reg-patient')" class="absolute top-2 right-2 px-2.5 py-1 rounded bg-white hover:bg-slate-100 border border-slate-300 text-xs text-slate-700 font-semibold shadow-xs">Hapus</button>
                                </div>
                                <div class="text-left">
                                    <label class="text-[10px] font-semibold text-slate-500">Nama Jelas:</label>
                                    <input type="text" wire:model="registration_patient_signer_name" placeholder="Nama jelas penandatangan" class="w-full px-2 py-1 text-xs border border-slate-300 rounded font-semibold text-slate-900">
                                </div>
                            </div>

                            <!-- Kanan: Petugas Pendaftaran (Perawat) -->
                            <div class="border border-slate-300 p-3 rounded-lg text-center bg-slate-50/50">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-slate-800">Petugas Pendaftaran</span>
                                    <span class="text-[10px] text-slate-500 font-mono">Tgl: {{ $registration_staff_signed_date }}</span>
                                </div>
                                <div class="relative bg-white border-2 border-slate-300 rounded-lg mb-2 shadow-inner">
                                    <canvas x-init="setupPad($el, 'canvas-reg-staff')" id="canvas-reg-staff" class="w-full h-44 sm:h-52 touch-none cursor-crosshair"></canvas>
                                    <button type="button" @click="clearSignature('reg-staff')" class="absolute top-2 right-2 px-2.5 py-1 rounded bg-white hover:bg-slate-100 border border-slate-300 text-xs text-slate-700 font-semibold shadow-xs">Hapus</button>
                                </div>
                                <div class="text-left">
                                    <label class="text-[10px] font-semibold text-slate-500">Nama Petugas / Perawat:</label>
                                    <input type="text" wire:model="registration_staff_signer_name" placeholder="Nama petugas pendaftaran" class="w-full px-2 py-1 text-xs border border-slate-300 rounded font-semibold text-slate-900">
                                </div>
                            </div>
                        </div>

                        <p class="text-[10px] text-slate-500 italic text-center pt-2">
                            Catatan: Formulir ini merupakan formulir pendaftaran pasien dan tidak menggantikan informed consent atau persetujuan tindakan medis yang diperlukan untuk tindakan tertentu.
                        </p>
                    </div>
                </div>

            <!-- ==================================================== -->
            <!-- STEP 2: GENERAL CONSENT (Hal 3 & 4 PDF)             -->
            <!-- ==================================================== -->
            @elseif($currentStep === 2)
                <div class="text-center pb-4 border-b-2 border-slate-900">
                    <img src="{{ asset('logo.svg') }}" alt="ONEW Clinic" class="h-12 w-auto mx-auto mb-2 object-contain">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-slate-800">Klinik Utama ONEW &bull; PT Permata Medikal Jaya</h3>
                    <p class="text-[11px] text-slate-500">Jl. Laksana I no 6, Kebayoran Baru, Jaksel 12180 &bull; +62 8119 6977 886 / +62 21382 506 94</p>
                    <h1 class="text-xl font-black uppercase tracking-tight text-slate-900 mt-3">
                        GENERAL CONSENT
                    </h1>
                    <span class="text-xs font-bold text-slate-600 tracking-wider block">FORMULIR PERSETUJUAN UMUM PASIEN</span>
                </div>

                <!-- Ringkasan Identitas Pasien -->
                <div class="mt-4 border border-slate-300 rounded-lg p-3 bg-slate-50 text-xs">
                    <h4 class="font-bold text-slate-800 uppercase tracking-wider mb-2 text-[11px]">Identitas Pasien</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1">
                        <div><span class="text-slate-500">Nama Lengkap:</span> <strong class="text-slate-900">{{ $name }}</strong></div>
                        <div><span class="text-slate-500">No. Rekam Medis:</span> <strong class="font-mono text-slate-900">{{ $manual_patient_code }}</strong></div>
                        <div><span class="text-slate-500">NIK / Paspor:</span> <span class="font-mono">{{ $nik_passport ?: '-' }}</span></div>
                        <div><span class="text-slate-500">Tanggal Lahir:</span> <span>{{ $date_of_birth ?: '-' }}</span></div>
                        <div><span class="text-slate-500">No. HP/WhatsApp:</span> <span class="font-mono">{{ $phone_number }}</span></div>
                        <div><span class="text-slate-500">Jenis Kelamin:</span> <span>{{ $gender }}</span></div>
                        <div class="sm:col-span-2"><span class="text-slate-500">Alamat:</span> <span>{{ $id_address ?: '-' }}</span></div>
                    </div>
                </div>

                <!-- 8 Poin General Consent -->
                <div class="mt-5 space-y-4 text-xs text-slate-700 leading-relaxed">
                    <div>
                        <h4 class="font-bold text-slate-900 mb-1">1. PERSETUJUAN PENDAFTARAN DAN PELAYANAN UMUM</h4>
                        <p class="text-justify">Saya bersedia didaftarkan sebagai pasien dan menerima penjelasan mengenai alur layanan, ketentuan administrasi, serta pelayanan awal yang sesuai kebutuhan, seperti wawancara riwayat kesehatan, pemeriksaan tanda vital, pemeriksaan fisik dasar, konsultasi, dan observasi noninvasif sesuai penilaian tenaga medis. Saya memahami bahwa setiap pemeriksaan dan terapi akan dijelaskan sesuai kebutuhan.</p>
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-900 mb-1">2. HAK DAN KEWAJIBAN PASIEN</h4>
                        <p class="text-justify">Saya telah memperoleh kesempatan untuk mengetahui hak saya, termasuk privasi dan kerahasiaan rekam medis. Saya berkewajiban memberikan data identitas dan riwayat kesehatan secara jujur dan benar, serta mematuhi tata tertib klinik.</p>
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-900 mb-1">3. REKAM MEDIS DAN PELINDUNGAN DATA PRIBADI</h4>
                        <p class="text-justify">Saya memahami klinik mengumpulkan data rekam medis elektronik demi kelancaran pengobatan. Kerahasiaan tetap dijaga sesuai undang-undang kesehatan.</p>
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-900 mb-1">4. BIAYA DAN ADMINISTRASI</h4>
                        <p class="text-justify">Saya memahami bahwa biaya konsultasi, tindakan medis penunjang, obat, dan tindakan estetika dapat bervariasi sesuai rencana tindakan.</p>
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-900 mb-1">5. KEADAAN DARURAT DAN RUJUKAN</h4>
                        <p class="text-justify">Bila terjadi kondisi darurat atau membutuhkan fasilitas rujukan lebih lanjut, tenaga medis berwenang mengambil tindakan pertolongan pertama yang diperlukan.</p>
                    </div>

                    <div>
                        <h4 class="font-bold text-slate-900 mb-1">6. KOMUNIKASI DAN DOKUMENTASI MEDIS</h4>
                        <p class="text-justify">Klinik dapat menghubungi nomor saya untuk keperluan pelayanan dan administrasi. Dokumentasi foto klinis (before/after) akan dijelaskan untuk pemantauan hasil medis.</p>
                    </div>

                    <!-- 7. Pilihan Pasien Form -->
                    <div class="p-4 border-2 border-slate-300 rounded-xl bg-slate-50 space-y-3">
                        <h4 class="font-bold text-slate-900 uppercase tracking-wider text-xs">7. Pilihan Pasien (Diisi Sesuai Kebutuhan)</h4>
                        
                        <div class="flex items-center justify-between gap-4 py-1 border-b border-slate-200">
                            <span>Pengingat jadwal dan komunikasi administrasi melalui WhatsApp:</span>
                            <div class="flex items-center gap-4">
                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio" name="gc_whatsapp_reminder" value="1" wire:model.live="gc_whatsapp_reminder" class="text-slate-900">
                                    <span>Ya</span>
                                </label>
                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio" name="gc_whatsapp_reminder" value="0" wire:model.live="gc_whatsapp_reminder" class="text-slate-900">
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-4 py-1 border-b border-slate-200">
                            <span>Informasi promosi layanan klinik (opsional; tidak memengaruhi pelayanan):</span>
                            <div class="flex items-center gap-4">
                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio" name="gc_promo_info" value="1" wire:model.live="gc_promo_info" class="text-slate-900">
                                    <span>Ya</span>
                                </label>
                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio" name="gc_promo_info" value="0" wire:model.live="gc_promo_info" class="text-slate-900">
                                    <span>Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-4 py-1 border-b border-slate-200">
                            <span>Pengungkapan data yang diperlukan untuk verifikasi klaim kepada penjamin:</span>
                            <div class="flex items-center gap-4">
                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio" name="gc_insurance_disclosure" value="Ya" wire:model.live="gc_insurance_disclosure" class="text-slate-900">
                                    <span>Ya</span>
                                </label>
                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio" name="gc_insurance_disclosure" value="Tidak" wire:model.live="gc_insurance_disclosure" class="text-slate-900">
                                    <span>Tidak</span>
                                </label>
                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio" name="gc_insurance_disclosure" value="N/A" wire:model.live="gc_insurance_disclosure" class="text-slate-900">
                                    <span>N/A</span>
                                </label>
                            </div>
                        </div>

                        <div class="py-1">
                            <div class="flex items-center justify-between gap-4 mb-2">
                                <span>Izin pemberian informasi kesehatan kepada orang yang saya tunjuk:</span>
                                <div class="flex items-center gap-4">
                                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                        <input type="radio" name="gc_third_party_info" value="1" wire:model.live="gc_third_party_info" class="text-slate-900">
                                        <span>Ya</span>
                                    </label>
                                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                        <input type="radio" name="gc_third_party_info" value="0" wire:model.live="gc_third_party_info" class="text-slate-900">
                                        <span>Tidak</span>
                                    </label>
                                </div>
                            </div>
                            @if($gc_third_party_info)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2 pt-2 border-t border-slate-200">
                                    <input type="text" wire:model="gc_third_party_name" placeholder="Nama / Hubungan pihak yang ditunjuk" class="px-2.5 py-1 border border-slate-300 rounded text-xs">
                                    <input type="text" wire:model="gc_third_party_phone" placeholder="No. Telepon / WhatsApp" class="px-2.5 py-1 border border-slate-300 rounded text-xs font-mono">
                                    <input type="text" wire:model="gc_third_party_notes" placeholder="Informasi yang boleh diberikan" class="sm:col-span-2 px-2.5 py-1 border border-slate-300 rounded text-xs">
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 8. Pernyataan dan Tanda Tangan General Consent -->
                    <div class="pt-4 border-t border-slate-300">
                        <h4 class="font-bold text-slate-900 uppercase tracking-wider text-xs mb-2">8. PERNYATAAN DAN PERSETUJUAN</h4>
                        <p class="mb-4">Saya menyatakan telah membaca atau mendapatkan penjelasan formulir ini dengan bahasa yang saya pahami dan memberikan persetujuan pendaftaran dan pelayanan umum.</p>

                        <div class="text-right text-xs text-slate-600 mb-3 font-mono">
                            Tempat: <strong>Jakarta</strong> &bull; Tanggal: <strong>{{ $gc_date }}</strong> &bull; Pukul: <strong>{{ $gc_time }} WIB</strong>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Kiri: Pasien / Wali -->
                            <div class="border border-slate-300 p-3 rounded-lg text-center bg-slate-50/50">
                                <span class="font-bold text-slate-800 block mb-1">PASIEN / ORANG TUA / WALI</span>
                                <div class="text-[10px] text-slate-500 mb-2">
                                    Hubungan: 
                                    <select wire:model="gc_patient_relation" class="text-[10px] py-0.5 border border-slate-300 rounded bg-white">
                                        <option value="Diri Sendiri">Diri Sendiri</option>
                                        <option value="Orang Tua">Orang Tua</option>
                                        <option value="Suami / Istri">Suami / Istri</option>
                                        <option value="Anak">Anak</option>
                                        <option value="Wali Sah">Wali Sah</option>
                                    </select>
                                </div>
                                <div class="relative bg-white border-2 border-slate-300 rounded-lg mb-2 shadow-inner">
                                    <canvas x-init="setupPad($el, 'canvas-gc-patient')" id="canvas-gc-patient" class="w-full h-44 sm:h-52 touch-none cursor-crosshair"></canvas>
                                    <button type="button" @click="clearSignature('gc-patient')" class="absolute top-2 right-2 px-2.5 py-1 rounded bg-white hover:bg-slate-100 border border-slate-300 text-xs text-slate-700 font-semibold shadow-xs">Hapus</button>
                                </div>
                                <input type="text" wire:model="gc_patient_signer_name" placeholder="Nama jelas penandatangan" class="w-full px-2 py-1 text-xs border border-slate-300 rounded font-semibold text-slate-900 text-center">
                            </div>

                            <!-- Kanan: Petugas Yang Menjelaskan -->
                            <div class="border border-slate-300 p-3 rounded-lg text-center bg-slate-50/50">
                                <span class="font-bold text-slate-800 block mb-1">PETUGAS YANG MEMBERIKAN PENJELASAN</span>
                                <div class="text-[10px] text-slate-500 mb-2 font-medium">Jabatan: Petugas Pendaftaran / Perawat</div>
                                <div class="relative bg-white border-2 border-slate-300 rounded-lg mb-2 shadow-inner">
                                    <canvas x-init="setupPad($el, 'canvas-gc-staff')" id="canvas-gc-staff" class="w-full h-44 sm:h-52 touch-none cursor-crosshair"></canvas>
                                    <button type="button" @click="clearSignature('gc-staff')" class="absolute top-2 right-2 px-2.5 py-1 rounded bg-white hover:bg-slate-100 border border-slate-300 text-xs text-slate-700 font-semibold shadow-xs">Hapus</button>
                                </div>
                                <input type="text" wire:model="gc_staff_name" placeholder="Nama petugas pendaftaran" class="w-full px-2 py-1 text-xs border border-slate-300 rounded font-semibold text-slate-900 text-center">
                            </div>
                        </div>
                    </div>
                </div>

            <!-- ==================================================== -->
            <!-- STEP 3: PERSETUJUAN TINDAKAN KEDOKTERAN (Hal 5 PDF) -->
            <!-- ==================================================== -->
            @elseif($currentStep === 3)
                <div class="text-center pb-4 border-b-2 border-slate-900">
                    <img src="{{ asset('logo.svg') }}" alt="ONEW Clinic" class="h-12 w-auto mx-auto mb-2 object-contain">
                    <h1 class="text-xl font-black uppercase tracking-tight text-slate-900">
                        PERSETUJUAN TINDAKAN KEDOKTERAN
                    </h1>
                    <span class="text-xs font-bold text-slate-600 tracking-wider block">INFORMED CONSENT RESMI KLINIS</span>
                </div>

                <!-- Bagian Pemberian Informasi -->
                <div class="mt-4 border border-slate-300 rounded-lg p-3.5 bg-slate-50 text-xs space-y-2">
                    <h4 class="font-black text-slate-900 uppercase tracking-wider text-[11px] mb-2">Pemberian Informasi</h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                        <span class="sm:col-span-4 font-semibold text-slate-700">Dokter Pelaksana Tindakan:</span>
                        <div class="sm:col-span-8">
                            <input type="text" wire:model="ic_doctor_name" placeholder="Nama dokter (contoh: dr. Cynthia, dr. Widya)" class="w-full px-2.5 py-1 border border-slate-300 rounded font-bold text-slate-900">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                        <span class="sm:col-span-4 font-semibold text-slate-700">Pemberi Informasi:</span>
                        <div class="sm:col-span-8">
                            <input type="text" wire:model="ic_info_provider" placeholder="Nama pemberi informasi medis" class="w-full px-2.5 py-1 border border-slate-300 rounded text-slate-900">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                        <span class="sm:col-span-4 font-semibold text-slate-700">Penerima Informasi / Persetujuan:</span>
                        <div class="sm:col-span-8">
                            <input type="text" wire:model="ic_recipient_name" placeholder="Nama pasien / wali penerima penjelasan" class="w-full px-2.5 py-1 border border-slate-300 rounded font-semibold text-slate-900">
                        </div>
                    </div>
                </div>

                <!-- Tabel 11 Poin Informasi Medis (Persis Lembar Fisik) -->
                <div class="mt-5 overflow-x-auto">
                    <table class="w-full text-xs border-collapse border border-slate-400">
                        <thead>
                            <tr class="bg-slate-900 text-white font-bold uppercase text-[10px] tracking-wider">
                                <th class="border border-slate-400 px-2 py-2 text-center w-10">No</th>
                                <th class="border border-slate-400 px-3 py-2 text-left w-48">Jenis Informasi</th>
                                <th class="border border-slate-400 px-3 py-2 text-left">Isi Informasi Medis</th>
                                <th class="border border-slate-400 px-2 py-2 text-center w-16">Tanda (✓)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-300 text-slate-800">
                            <tr>
                                <td class="border border-slate-400 text-center font-bold">1</td>
                                <td class="border border-slate-400 px-3 py-1.5 font-semibold">Diagnosis (WD & DD)</td>
                                <td class="border border-slate-400 p-1">
                                    <input type="text" wire:model="ic_diagnosis" placeholder="Diagnosis kerja & banding dokter" class="w-full px-2 py-1 text-xs border-0 focus:ring-0">
                                </td>
                                <td class="border border-slate-400 text-center"><input type="checkbox" wire:model="ic_diagnosis_checked" class="rounded"></td>
                            </tr>
                            <tr class="bg-slate-50/50">
                                <td class="border border-slate-400 text-center font-bold">2</td>
                                <td class="border border-slate-400 px-3 py-1.5 font-semibold">Dasar Diagnosis</td>
                                <td class="border border-slate-400 p-1">
                                    <input type="text" wire:model="ic_diagnosis_basis" class="w-full px-2 py-1 text-xs border-0 focus:ring-0">
                                </td>
                                <td class="border border-slate-400 text-center"><input type="checkbox" wire:model="ic_diagnosis_basis_checked" class="rounded"></td>
                            </tr>
                            <tr>
                                <td class="border border-slate-400 text-center font-bold">3</td>
                                <td class="border border-slate-400 px-3 py-1.5 font-semibold">Tindakan Kedokteran</td>
                                <td class="border border-slate-400 p-1">
                                    <input type="text" wire:model.live="ic_medical_procedure" placeholder="Tindakan medis yang ditentukan dokter..." class="w-full px-2 py-1 text-xs font-bold text-sky-900 border-0 focus:ring-0">
                                </td>
                                <td class="border border-slate-400 text-center"><input type="checkbox" wire:model="ic_medical_procedure_checked" class="rounded"></td>
                            </tr>
                            <tr class="bg-slate-50/50">
                                <td class="border border-slate-400 text-center font-bold">4</td>
                                <td class="border border-slate-400 px-3 py-1.5 font-semibold">Indikasi Tindakan</td>
                                <td class="border border-slate-400 p-1">
                                    <input type="text" wire:model="ic_indication" placeholder="Contoh: Secretome 1 / Peremajaan Kulit" class="w-full px-2 py-1 text-xs border-0 focus:ring-0">
                                </td>
                                <td class="border border-slate-400 text-center"><input type="checkbox" wire:model="ic_indication_checked" class="rounded"></td>
                            </tr>
                            <tr>
                                <td class="border border-slate-400 text-center font-bold">5</td>
                                <td class="border border-slate-400 px-3 py-1.5 font-semibold">Tata Cara</td>
                                <td class="border border-slate-400 p-1">
                                    <input type="text" wire:model="ic_procedure_steps" class="w-full px-2 py-1 text-xs border-0 focus:ring-0">
                                </td>
                                <td class="border border-slate-400 text-center"><input type="checkbox" wire:model="ic_procedure_steps_checked" class="rounded"></td>
                            </tr>
                            <tr class="bg-slate-50/50">
                                <td class="border border-slate-400 text-center font-bold">6</td>
                                <td class="border border-slate-400 px-3 py-1.5 font-semibold">Tujuan</td>
                                <td class="border border-slate-400 p-1">
                                    <input type="text" wire:model="ic_procedure_goal" class="w-full px-2 py-1 text-xs border-0 focus:ring-0">
                                </td>
                                <td class="border border-slate-400 text-center"><input type="checkbox" wire:model="ic_procedure_goal_checked" class="rounded"></td>
                            </tr>
                            <tr>
                                <td class="border border-slate-400 text-center font-bold">7</td>
                                <td class="border border-slate-400 px-3 py-1.5 font-semibold">Risiko</td>
                                <td class="border border-slate-400 p-1">
                                    <input type="text" wire:model="ic_risks" class="w-full px-2 py-1 text-xs border-0 focus:ring-0">
                                </td>
                                <td class="border border-slate-400 text-center"><input type="checkbox" wire:model="ic_risks_checked" class="rounded"></td>
                            </tr>
                            <tr class="bg-slate-50/50">
                                <td class="border border-slate-400 text-center font-bold">8</td>
                                <td class="border border-slate-400 px-3 py-1.5 font-semibold">Komplikasi</td>
                                <td class="border border-slate-400 p-1">
                                    <input type="text" wire:model="ic_complications" class="w-full px-2 py-1 text-xs border-0 focus:ring-0">
                                </td>
                                <td class="border border-slate-400 text-center"><input type="checkbox" wire:model="ic_complications_checked" class="rounded"></td>
                            </tr>
                            <tr>
                                <td class="border border-slate-400 text-center font-bold">9</td>
                                <td class="border border-slate-400 px-3 py-1.5 font-semibold">Prognosis</td>
                                <td class="border border-slate-400 p-1">
                                    <input type="text" wire:model="ic_prognosis" class="w-full px-2 py-1 text-xs border-0 focus:ring-0">
                                </td>
                                <td class="border border-slate-400 text-center"><input type="checkbox" wire:model="ic_prognosis_checked" class="rounded"></td>
                            </tr>
                            <tr class="bg-slate-50/50">
                                <td class="border border-slate-400 text-center font-bold">10</td>
                                <td class="border border-slate-400 px-3 py-1.5 font-semibold">Alternatif & Risiko</td>
                                <td class="border border-slate-400 p-1">
                                    <input type="text" wire:model="ic_alternatives_risks" class="w-full px-2 py-1 text-xs border-0 focus:ring-0">
                                </td>
                                <td class="border border-slate-400 text-center"><input type="checkbox" wire:model="ic_alternatives_risks_checked" class="rounded"></td>
                            </tr>
                            <tr>
                                <td class="border border-slate-400 text-center font-bold">11</td>
                                <td class="border border-slate-400 px-3 py-1.5 font-semibold">Lain-lain</td>
                                <td class="border border-slate-400 p-1">
                                    <input type="text" wire:model="ic_other_notes" class="w-full px-2 py-1 text-xs border-0 focus:ring-0">
                                </td>
                                <td class="border border-slate-400 text-center"><input type="checkbox" wire:model="ic_other_notes_checked" class="rounded"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Bagian Tanda Tangan Pernyataan Penjelasan -->
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="border border-slate-300 p-3 rounded bg-slate-50/50 text-center">
                        <p class="text-[11px] text-justify mb-2">Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas secara benar dan jelas:</p>
                        <div class="relative bg-white border-2 border-slate-300 rounded-lg mb-2 shadow-inner">
                            <canvas x-init="setupPad($el, 'canvas-ic-doctor')" id="canvas-ic-doctor" class="w-full h-44 sm:h-52 touch-none cursor-crosshair"></canvas>
                            <button type="button" @click="clearSignature('ic-doctor')" class="absolute top-2 right-2 px-2.5 py-1 rounded bg-white hover:bg-slate-100 border border-slate-300 text-xs text-slate-700 font-semibold shadow-xs">Hapus</button>
                        </div>
                        <span class="text-[10px] text-slate-500 font-semibold block">Tanda tangan Dokter Pelaksana</span>
                    </div>

                    <div class="border border-slate-300 p-3 rounded bg-slate-50/50 text-center">
                        <p class="text-[11px] text-justify mb-2">Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana di atas dan memahaminya:</p>
                        <div class="relative bg-white border-2 border-slate-300 rounded-lg mb-2 shadow-inner">
                            <canvas x-init="setupPad($el, 'canvas-ic-recipient')" id="canvas-ic-recipient" class="w-full h-44 sm:h-52 touch-none cursor-crosshair"></canvas>
                            <button type="button" @click="clearSignature('ic-recipient')" class="absolute top-2 right-2 px-2.5 py-1 rounded bg-white hover:bg-slate-100 border border-slate-300 text-xs text-slate-700 font-semibold shadow-xs">Hapus</button>
                        </div>
                        <span class="text-[10px] text-slate-500 font-semibold block">Tanda tangan Pasien / Wali</span>
                    </div>
                </div>

                <!-- Bagian Bawah: Pernyataan Persetujuan Tindakan Medis -->
                <div class="mt-6 border-t-2 border-slate-900 pt-4 text-xs text-slate-800 space-y-3">
                    <h3 class="font-black text-center uppercase tracking-widest text-slate-900 text-sm">PERSETUJUAN TINDAKAN KEDOKTERAN</h3>
                    @php
                        $calculatedAge = $ic_patient_age ?: ($date_of_birth ? \Carbon\Carbon::parse($date_of_birth)->age : '');
                        $resolvedAddress = $id_address ?: ($domicile_address ?: ($address ?? ''));
                        $resolvedProcedure = $this->getDoctorActionsSummary();
                        $signerName = $ic_recipient_name ?: $name;
                    @endphp
                    <p class="leading-relaxed text-xs sm:text-sm">
                        Yang bertandatangan di bawah ini, saya, nama <strong class="text-slate-950 font-bold underline">{{ $signerName ?: 'Pasien' }}</strong>, umur: <strong class="text-slate-950 font-bold underline">{{ $calculatedAge ?: '-' }}</strong> tahun, alamat: <strong class="text-slate-950 font-bold underline">{{ $resolvedAddress ?: '-' }}</strong>, dengan ini menyatakan persetujuan untuk dilakukannya tindakan <strong class="text-sky-950 font-black underline">{{ $resolvedProcedure ?: 'Tindakan Medis Estetika' }}</strong> terhadap <strong class="text-slate-950 font-bold underline">{{ $name ?: 'Pasien' }}</strong>.
                    </p>
                    <p class="text-[11px] text-slate-600 text-justify">
                        Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan. Saya juga menyadari bahwa ilmu kedokteran bukanlah ilmu pasti, melainkan sangat bergantung kepada izin Tuhan Yang Maha Esa.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <!-- Yang Menyatakan -->
                        <div class="border border-slate-300 p-3 rounded bg-white text-center">
                            <span class="font-bold text-slate-900 block mb-2">Yang Menyatakan (Pasien / Wali)</span>
                            <div class="relative bg-white border-2 border-slate-300 rounded-lg mb-2 shadow-inner">
                                <canvas x-init="setupPad($el, 'canvas-ic-recipient-stmt')" id="canvas-ic-recipient-stmt" class="w-full h-44 sm:h-52 touch-none cursor-crosshair"></canvas>
                                <button type="button" @click="clearSignature('canvas-ic-recipient-stmt')" class="absolute top-2 right-2 px-2.5 py-1 rounded bg-white hover:bg-slate-100 border border-slate-300 text-xs text-slate-700 font-semibold shadow-xs">Hapus</button>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-900">{{ $ic_recipient_name ?: $name }}</span>
                        </div>

                        <!-- Saksi / Perawat -->
                        <div class="border border-slate-300 p-3 rounded bg-white text-center">
                            <span class="font-bold text-slate-900 block mb-2">Saksi (Perawat / Petugas)</span>
                            <div class="relative bg-white border-2 border-slate-300 rounded-lg mb-2 shadow-inner">
                                <canvas x-init="setupPad($el, 'canvas-ic-witness')" id="canvas-ic-witness" class="w-full h-44 sm:h-52 touch-none cursor-crosshair"></canvas>
                                <button type="button" @click="clearSignature('ic-witness')" class="absolute top-2 right-2 px-2.5 py-1 rounded bg-white hover:bg-slate-100 border border-slate-300 text-xs text-slate-700 font-semibold shadow-xs">Hapus</button>
                            </div>
                            <input type="text" wire:model="ic_witness_name" placeholder="Nama saksi / perawat" class="w-full px-2 py-1 text-center text-xs font-semibold text-slate-900 border border-slate-300 rounded">
                        </div>
                    </div>
                </div>

            <!-- ==================================================== -->
            <!-- STEP 4: FOTO BEFORE 5 SISI WAJAH                   -->
            <!-- ==================================================== -->
            @elseif($currentStep === 4)
                <div class="text-center pb-4 border-b-2 border-slate-900">
                    <span class="text-xs font-bold uppercase tracking-widest text-sky-700 bg-sky-50 px-3 py-1 rounded-full border border-sky-200">Tugas Perawat</span>
                    <h1 class="text-xl font-black uppercase tracking-tight text-slate-900 mt-2">
                        Dokumentasi Foto Sebelum Tindakan (Foto Before)
                    </h1>
                    <p class="text-xs text-slate-500 mt-1">Pengambilan foto klinis 5 sisi wajah pasien menggunakan kamera tablet atau unggah file foto.</p>
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5">
                    <!-- 1. Depan -->
                    <div x-data="photoUploader('temp_photo_before_front', '{{ $temp_photo_before_front ? $temp_photo_before_front->temporaryUrl() : ($photo_before_front_path ? \App\Models\Visit::formatPublicUrl($photo_before_front_path) : '') }}')"
                         wire:key="uploader-before-front"
                         class="border-2 border-slate-200 rounded-xl p-3 bg-slate-50 text-center flex flex-col justify-between transition-all">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">1. Tampak Depan (0°)</span>
                            <div class="relative min-h-[160px] flex items-center justify-center">
                                <template x-if="previewUrl">
                                    <div class="w-full relative group">
                                        <img :src="previewUrl" alt="Tampak Depan" class="h-40 w-full object-cover rounded-lg shadow-xs mb-2 transition-transform duration-200">
                                        <div x-show="isUploading" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] rounded-lg flex flex-col items-center justify-center text-white text-[11px] font-medium gap-1.5 p-2">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                            <span>Mengunggah...</span>
                                        </div>
                                        <div x-show="!isUploading && (isSuccess || previewUrl)" class="absolute bottom-3 right-1.5 bg-emerald-500/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                            ✓ Terpasang
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!previewUrl" class="h-40 w-full flex flex-col items-center justify-center border border-dashed border-slate-300 rounded-lg bg-white mb-2 text-slate-400">
                                    <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    <span class="text-[10px]">Wajah Depan</span>
                                </div>
                            </div>
                            <div x-show="errorMessage" x-text="errorMessage" class="text-[10px] text-rose-500 font-medium mb-1"></div>
                        </div>
                        <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span x-text="previewUrl ? 'Ambil Ulang' : 'Ambil / Buka Kamera'"></span>
                            <input type="file" accept="image/*" capture="environment" @change="handleFileSelect($event)" class="hidden">
                        </label>
                    </div>

                    <!-- 2. Serong Kanan -->
                    <div x-data="photoUploader('temp_photo_before_right_tilt', '{{ $temp_photo_before_right_tilt ? $temp_photo_before_right_tilt->temporaryUrl() : ($photo_before_right_tilt_path ? \App\Models\Visit::formatPublicUrl($photo_before_right_tilt_path) : '') }}')"
                         wire:key="uploader-before-right-tilt"
                         class="border-2 border-slate-200 rounded-xl p-3 bg-slate-50 text-center flex flex-col justify-between transition-all">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">2. Serong Kanan (45°)</span>
                            <div class="relative min-h-[160px] flex items-center justify-center">
                                <template x-if="previewUrl">
                                    <div class="w-full relative group">
                                        <img :src="previewUrl" alt="Serong Kanan" class="h-40 w-full object-cover rounded-lg shadow-xs mb-2 transition-transform duration-200">
                                        <div x-show="isUploading" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] rounded-lg flex flex-col items-center justify-center text-white text-[11px] font-medium gap-1.5 p-2">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                            <span>Mengunggah...</span>
                                        </div>
                                        <div x-show="!isUploading && (isSuccess || previewUrl)" class="absolute bottom-3 right-1.5 bg-emerald-500/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                            ✓ Terpasang
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!previewUrl" class="h-40 w-full flex flex-col items-center justify-center border border-dashed border-slate-300 rounded-lg bg-white mb-2 text-slate-400">
                                    <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    <span class="text-[10px]">Serong Kanan</span>
                                </div>
                            </div>
                            <div x-show="errorMessage" x-text="errorMessage" class="text-[10px] text-rose-500 font-medium mb-1"></div>
                        </div>
                        <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span x-text="previewUrl ? 'Ambil Ulang' : 'Ambil / Buka Kamera'"></span>
                            <input type="file" accept="image/*" capture="environment" @change="handleFileSelect($event)" class="hidden">
                        </label>
                    </div>

                    <!-- 3. Serong Kiri -->
                    <div x-data="photoUploader('temp_photo_before_left_tilt', '{{ $temp_photo_before_left_tilt ? $temp_photo_before_left_tilt->temporaryUrl() : ($photo_before_left_tilt_path ? \App\Models\Visit::formatPublicUrl($photo_before_left_tilt_path) : '') }}')"
                         wire:key="uploader-before-left-tilt"
                         class="border-2 border-slate-200 rounded-xl p-3 bg-slate-50 text-center flex flex-col justify-between transition-all">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">3. Serong Kiri (45°)</span>
                            <div class="relative min-h-[160px] flex items-center justify-center">
                                <template x-if="previewUrl">
                                    <div class="w-full relative group">
                                        <img :src="previewUrl" alt="Serong Kiri" class="h-40 w-full object-cover rounded-lg shadow-xs mb-2 transition-transform duration-200">
                                        <div x-show="isUploading" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] rounded-lg flex flex-col items-center justify-center text-white text-[11px] font-medium gap-1.5 p-2">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                            <span>Mengunggah...</span>
                                        </div>
                                        <div x-show="!isUploading && (isSuccess || previewUrl)" class="absolute bottom-3 right-1.5 bg-emerald-500/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                            ✓ Terpasang
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!previewUrl" class="h-40 w-full flex flex-col items-center justify-center border border-dashed border-slate-300 rounded-lg bg-white mb-2 text-slate-400">
                                    <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    <span class="text-[10px]">Serong Kiri</span>
                                </div>
                            </div>
                            <div x-show="errorMessage" x-text="errorMessage" class="text-[10px] text-rose-500 font-medium mb-1"></div>
                        </div>
                        <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span x-text="previewUrl ? 'Ambil Ulang' : 'Ambil / Buka Kamera'"></span>
                            <input type="file" accept="image/*" capture="environment" @change="handleFileSelect($event)" class="hidden">
                        </label>
                    </div>

                    <!-- 4. Tampak Kanan (Samping Kanan) -->
                    <div x-data="photoUploader('temp_photo_before_right_side', '{{ $temp_photo_before_right_side ? $temp_photo_before_right_side->temporaryUrl() : ($photo_before_right_side_path ? \App\Models\Visit::formatPublicUrl($photo_before_right_side_path) : '') }}')"
                         wire:key="uploader-before-right-side"
                         class="border-2 border-slate-200 rounded-xl p-3 bg-slate-50 text-center flex flex-col justify-between transition-all">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">4. Tampak Kanan (90°)</span>
                            <div class="relative min-h-[160px] flex items-center justify-center">
                                <template x-if="previewUrl">
                                    <div class="w-full relative group">
                                        <img :src="previewUrl" alt="Tampak Kanan" class="h-40 w-full object-cover rounded-lg shadow-xs mb-2 transition-transform duration-200">
                                        <div x-show="isUploading" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] rounded-lg flex flex-col items-center justify-center text-white text-[11px] font-medium gap-1.5 p-2">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                            <span>Mengunggah...</span>
                                        </div>
                                        <div x-show="!isUploading && (isSuccess || previewUrl)" class="absolute bottom-3 right-1.5 bg-emerald-500/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                            ✓ Terpasang
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!previewUrl" class="h-40 w-full flex flex-col items-center justify-center border border-dashed border-slate-300 rounded-lg bg-white mb-2 text-slate-400">
                                    <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    <span class="text-[10px]">Tampak Kanan</span>
                                </div>
                            </div>
                            <div x-show="errorMessage" x-text="errorMessage" class="text-[10px] text-rose-500 font-medium mb-1"></div>
                        </div>
                        <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span x-text="previewUrl ? 'Ambil Ulang' : 'Ambil / Buka Kamera'"></span>
                            <input type="file" accept="image/*" capture="environment" @change="handleFileSelect($event)" class="hidden">
                        </label>
                    </div>

                    <!-- 5. Samping Kiri -->
                    <div x-data="photoUploader('temp_photo_before_left_side', '{{ $temp_photo_before_left_side ? $temp_photo_before_left_side->temporaryUrl() : ($photo_before_left_side_path ? \App\Models\Visit::formatPublicUrl($photo_before_left_side_path) : '') }}')"
                         wire:key="uploader-before-left-side"
                         class="border-2 border-slate-200 rounded-xl p-3 bg-slate-50 text-center flex flex-col justify-between transition-all">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">5. Samping Kiri (90°)</span>
                            <div class="relative min-h-[160px] flex items-center justify-center">
                                <template x-if="previewUrl">
                                    <div class="w-full relative group">
                                        <img :src="previewUrl" alt="Samping Kiri" class="h-40 w-full object-cover rounded-lg shadow-xs mb-2 transition-transform duration-200">
                                        <div x-show="isUploading" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] rounded-lg flex flex-col items-center justify-center text-white text-[11px] font-medium gap-1.5 p-2">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                            <span>Mengunggah...</span>
                                        </div>
                                        <div x-show="!isUploading && (isSuccess || previewUrl)" class="absolute bottom-3 right-1.5 bg-emerald-500/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                            ✓ Terpasang
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!previewUrl" class="h-40 w-full flex flex-col items-center justify-center border border-dashed border-slate-300 rounded-lg bg-white mb-2 text-slate-400">
                                    <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    <span class="text-[10px]">Samping Kiri</span>
                                </div>
                            </div>
                            <div x-show="errorMessage" x-text="errorMessage" class="text-[10px] text-rose-500 font-medium mb-1"></div>
                        </div>
                        <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span x-text="previewUrl ? 'Ambil Ulang' : 'Ambil / Buka Kamera'"></span>
                            <input type="file" accept="image/*" capture="environment" @change="handleFileSelect($event)" class="hidden">
                        </label>
                    </div>
                </div>

            <!-- ==================================================== -->
            <!-- STEP 5: TINDAKAN & BHP TINDAKAN HARIAN (Hal 6 PDF) -->
            <!-- ==================================================== -->
            @elseif($currentStep === 5)
                <div class="text-center pb-4 border-b-2 border-slate-900">
                    <h1 class="text-2xl font-black uppercase tracking-tight text-slate-900">
                        BHP TINDAKAN HARIAN
                    </h1>
                    <span class="text-xs font-bold text-slate-500 tracking-wider block">KLINIK UTAMA ONEW</span>
                </div>

                <!-- Header Metadata Lembar BHP -->
                <div class="mt-4 border-2 border-slate-900 rounded-xl p-3.5 bg-slate-50 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="font-bold text-slate-700 uppercase">NAMA PASIEN:</span>
                        <strong class="text-slate-900 font-bold block text-sm mt-0.5">{{ $name }}</strong>
                    </div>
                    <div>
                        <span class="font-bold text-slate-700 uppercase">PERAWAT / TERAPIS:</span>
                        <input type="text" wire:model="bhp_therapist_nurse" class="w-full mt-0.5 px-2 py-1 border border-slate-300 rounded font-semibold text-slate-900">
                    </div>
                    <div>
                        <span class="font-bold text-slate-700 uppercase">DOKTER:</span>
                        <input type="text" wire:model="bhp_doctor_name" class="w-full mt-0.5 px-2 py-1 border border-slate-300 rounded font-semibold text-slate-900">
                    </div>
                    <div>
                        <span class="font-bold text-slate-700 uppercase">TANGGAL:</span>
                        <strong class="text-slate-900 block font-mono text-sm mt-0.5">{{ $bhp_date }}</strong>
                    </div>
                </div>

                <!-- Two-Column Side-by-Side Table: Kiri (TINDAKAN) & Kanan (BAHAN & QT) -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
                    <!-- Kolom Kiri: TINDAKAN -->
                    <div class="md:col-span-5 border-2 border-slate-900 rounded-xl overflow-hidden bg-white">
                        <div class="bg-slate-900 text-white font-black text-center py-2 text-xs uppercase tracking-wider">
                            TINDAKAN
                        </div>
                        <div class="p-3 space-y-2">
                            @foreach($treatmentRows as $idx => $tRow)
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-slate-400 w-5 text-right">{{ $idx + 1 }}.</span>
                                    <input type="text"
                                           wire:model="treatmentRows.{{ $idx }}.action"
                                           placeholder="Tindakan / Dosis..."
                                           class="flex-1 px-2.5 py-1.5 border border-slate-300 rounded text-xs font-semibold text-slate-900 focus:ring-1 focus:ring-slate-900 focus:outline-none">
                                    <button type="button" wire:click="removeTreatmentRow({{ $idx }})" class="text-rose-500 hover:text-rose-700 p-1 font-bold text-sm">&times;</button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addTreatmentRow" class="w-full mt-2 py-1.5 rounded-lg border border-dashed border-slate-300 hover:border-slate-800 text-slate-700 hover:bg-slate-50 text-xs font-semibold flex items-center justify-center gap-1">
                                <span>+ Tambah Baris Tindakan</span>
                            </button>
                        </div>
                    </div>

                    <!-- Kolom Kanan: BAHAN & QT (Tabel 30 Bahan Resmi) -->
                    <div class="md:col-span-7 border-2 border-slate-900 rounded-xl overflow-hidden bg-white">
                        <div class="bg-slate-900 text-white font-black flex items-center justify-between px-4 py-2 text-xs uppercase tracking-wider">
                            <span>BAHAN</span>
                            <span>QT (Quantity)</span>
                        </div>
                        <div class="max-h-[580px] overflow-y-auto divide-y divide-slate-200">
                            @foreach($consumables as $item)
                                <div class="flex items-center justify-between p-2.5 hover:bg-slate-50 text-xs transition-colors">
                                    <div>
                                        <span class="font-bold text-slate-900 text-[11px] block">{{ $item->name }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $item->unit }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <input type="number"
                                               min="0"
                                               wire:model="selectedBhpQuantities.{{ $item->id }}"
                                               class="w-16 px-2 py-1 text-center font-mono font-bold text-xs border border-slate-300 rounded focus:ring-1 focus:ring-slate-900 focus:outline-none">
                                        <span class="text-[10px] text-slate-400 font-medium w-8 text-left">{{ $item->unit }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Tambah Bahan Baku Baru / Ad-hoc -->
                        <div class="p-3 bg-slate-50 border-t border-slate-200 flex items-center gap-2">
                            <input type="text" wire:model="newCustomBhpName" placeholder="Nama bahan baru..." class="flex-1 px-2.5 py-1 text-xs border border-slate-300 rounded uppercase">
                            <input type="number" min="1" wire:model="newCustomBhpQty" class="w-14 px-2 py-1 text-xs border border-slate-300 rounded text-center">
                            <button type="button" wire:click="addCustomBhp" class="px-3 py-1 bg-slate-900 text-white rounded text-xs font-semibold whitespace-nowrap">Tambah</button>
                        </div>
                    </div>
                </div>

            <!-- ==================================================== -->
            <!-- STEP 6: FOTO AFTER 5 SISI WAJAH                    -->
            <!-- ==================================================== -->
            @elseif($currentStep === 6)
                <div class="text-center pb-4 border-b-2 border-slate-900">
                    <span class="text-xs font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">Tugas Perawat / Dokter</span>
                    <h1 class="text-xl font-black uppercase tracking-tight text-slate-900 mt-2">
                        Dokumentasi Foto Setelah Tindakan (Foto After)
                    </h1>
                    <p class="text-xs text-slate-500 mt-1">Pengambilan dokumentasi 5 sisi wajah pasien setelah perawatan selesai.</p>
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5">
                    <!-- 1. Depan -->
                    <div x-data="photoUploader('temp_photo_after_front', '{{ $temp_photo_after_front ? $temp_photo_after_front->temporaryUrl() : ($photo_after_front_path ? \App\Models\Visit::formatPublicUrl($photo_after_front_path) : '') }}')"
                         wire:key="uploader-after-front"
                         class="border-2 border-slate-200 rounded-xl p-3 bg-slate-50 text-center flex flex-col justify-between transition-all">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">1. Tampak Depan (0°)</span>
                            <div class="relative min-h-[160px] flex items-center justify-center">
                                <template x-if="previewUrl">
                                    <div class="w-full relative group">
                                        <img :src="previewUrl" alt="Tampak Depan" class="h-40 w-full object-cover rounded-lg shadow-xs mb-2 transition-transform duration-200">
                                        <div x-show="isUploading" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] rounded-lg flex flex-col items-center justify-center text-white text-[11px] font-medium gap-1.5 p-2">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                            <span>Mengunggah...</span>
                                        </div>
                                        <div x-show="!isUploading && (isSuccess || previewUrl)" class="absolute bottom-3 right-1.5 bg-emerald-500/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                            ✓ Terpasang
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!previewUrl" class="h-40 w-full flex flex-col items-center justify-center border border-dashed border-slate-300 rounded-lg bg-white mb-2 text-slate-400">
                                    <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    <span class="text-[10px]">Wajah Depan</span>
                                </div>
                            </div>
                            <div x-show="errorMessage" x-text="errorMessage" class="text-[10px] text-rose-500 font-medium mb-1"></div>
                        </div>
                        <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span x-text="previewUrl ? 'Ambil Ulang' : 'Ambil / Buka Kamera'"></span>
                            <input type="file" accept="image/*" capture="environment" @change="handleFileSelect($event)" class="hidden">
                        </label>
                    </div>

                    <!-- 2. Serong Kanan -->
                    <div x-data="photoUploader('temp_photo_after_right_tilt', '{{ $temp_photo_after_right_tilt ? $temp_photo_after_right_tilt->temporaryUrl() : ($photo_after_right_tilt_path ? \App\Models\Visit::formatPublicUrl($photo_after_right_tilt_path) : '') }}')"
                         wire:key="uploader-after-right-tilt"
                         class="border-2 border-slate-200 rounded-xl p-3 bg-slate-50 text-center flex flex-col justify-between transition-all">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">2. Serong Kanan (45°)</span>
                            <div class="relative min-h-[160px] flex items-center justify-center">
                                <template x-if="previewUrl">
                                    <div class="w-full relative group">
                                        <img :src="previewUrl" alt="Serong Kanan" class="h-40 w-full object-cover rounded-lg shadow-xs mb-2 transition-transform duration-200">
                                        <div x-show="isUploading" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] rounded-lg flex flex-col items-center justify-center text-white text-[11px] font-medium gap-1.5 p-2">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                            <span>Mengunggah...</span>
                                        </div>
                                        <div x-show="!isUploading && (isSuccess || previewUrl)" class="absolute bottom-3 right-1.5 bg-emerald-500/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                            ✓ Terpasang
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!previewUrl" class="h-40 w-full flex flex-col items-center justify-center border border-dashed border-slate-300 rounded-lg bg-white mb-2 text-slate-400">
                                    <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    <span class="text-[10px]">Serong Kanan</span>
                                </div>
                            </div>
                            <div x-show="errorMessage" x-text="errorMessage" class="text-[10px] text-rose-500 font-medium mb-1"></div>
                        </div>
                        <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span x-text="previewUrl ? 'Ambil Ulang' : 'Ambil / Buka Kamera'"></span>
                            <input type="file" accept="image/*" capture="environment" @change="handleFileSelect($event)" class="hidden">
                        </label>
                    </div>

                    <!-- 3. Serong Kiri -->
                    <div x-data="photoUploader('temp_photo_after_left_tilt', '{{ $temp_photo_after_left_tilt ? $temp_photo_after_left_tilt->temporaryUrl() : ($photo_after_left_tilt_path ? \App\Models\Visit::formatPublicUrl($photo_after_left_tilt_path) : '') }}')"
                         wire:key="uploader-after-left-tilt"
                         class="border-2 border-slate-200 rounded-xl p-3 bg-slate-50 text-center flex flex-col justify-between transition-all">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">3. Serong Kiri (45°)</span>
                            <div class="relative min-h-[160px] flex items-center justify-center">
                                <template x-if="previewUrl">
                                    <div class="w-full relative group">
                                        <img :src="previewUrl" alt="Serong Kiri" class="h-40 w-full object-cover rounded-lg shadow-xs mb-2 transition-transform duration-200">
                                        <div x-show="isUploading" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] rounded-lg flex flex-col items-center justify-center text-white text-[11px] font-medium gap-1.5 p-2">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                            <span>Mengunggah...</span>
                                        </div>
                                        <div x-show="!isUploading && (isSuccess || previewUrl)" class="absolute bottom-3 right-1.5 bg-emerald-500/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                            ✓ Terpasang
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!previewUrl" class="h-40 w-full flex flex-col items-center justify-center border border-dashed border-slate-300 rounded-lg bg-white mb-2 text-slate-400">
                                    <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    <span class="text-[10px]">Serong Kiri</span>
                                </div>
                            </div>
                            <div x-show="errorMessage" x-text="errorMessage" class="text-[10px] text-rose-500 font-medium mb-1"></div>
                        </div>
                        <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span x-text="previewUrl ? 'Ambil Ulang' : 'Ambil / Buka Kamera'"></span>
                            <input type="file" accept="image/*" capture="environment" @change="handleFileSelect($event)" class="hidden">
                        </label>
                    </div>

                    <!-- 4. Tampak Kanan (Samping Kanan) -->
                    <div x-data="photoUploader('temp_photo_after_right_side', '{{ $temp_photo_after_right_side ? $temp_photo_after_right_side->temporaryUrl() : ($photo_after_right_side_path ? \App\Models\Visit::formatPublicUrl($photo_after_right_side_path) : '') }}')"
                         wire:key="uploader-after-right-side"
                         class="border-2 border-slate-200 rounded-xl p-3 bg-slate-50 text-center flex flex-col justify-between transition-all">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">4. Tampak Kanan (90°)</span>
                            <div class="relative min-h-[160px] flex items-center justify-center">
                                <template x-if="previewUrl">
                                    <div class="w-full relative group">
                                        <img :src="previewUrl" alt="Tampak Kanan" class="h-40 w-full object-cover rounded-lg shadow-xs mb-2 transition-transform duration-200">
                                        <div x-show="isUploading" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] rounded-lg flex flex-col items-center justify-center text-white text-[11px] font-medium gap-1.5 p-2">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                            <span>Mengunggah...</span>
                                        </div>
                                        <div x-show="!isUploading && (isSuccess || previewUrl)" class="absolute bottom-3 right-1.5 bg-emerald-500/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                            ✓ Terpasang
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!previewUrl" class="h-40 w-full flex flex-col items-center justify-center border border-dashed border-slate-300 rounded-lg bg-white mb-2 text-slate-400">
                                    <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    <span class="text-[10px]">Tampak Kanan</span>
                                </div>
                            </div>
                            <div x-show="errorMessage" x-text="errorMessage" class="text-[10px] text-rose-500 font-medium mb-1"></div>
                        </div>
                        <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span x-text="previewUrl ? 'Ambil Ulang' : 'Ambil / Buka Kamera'"></span>
                            <input type="file" accept="image/*" capture="environment" @change="handleFileSelect($event)" class="hidden">
                        </label>
                    </div>

                    <!-- 5. Samping Kiri -->
                    <div x-data="photoUploader('temp_photo_after_left_side', '{{ $temp_photo_after_left_side ? $temp_photo_after_left_side->temporaryUrl() : ($photo_after_left_side_path ? \App\Models\Visit::formatPublicUrl($photo_after_left_side_path) : '') }}')"
                         wire:key="uploader-after-left-side"
                         class="border-2 border-slate-200 rounded-xl p-3 bg-slate-50 text-center flex flex-col justify-between transition-all">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">5. Samping Kiri (90°)</span>
                            <div class="relative min-h-[160px] flex items-center justify-center">
                                <template x-if="previewUrl">
                                    <div class="w-full relative group">
                                        <img :src="previewUrl" alt="Samping Kiri" class="h-40 w-full object-cover rounded-lg shadow-xs mb-2 transition-transform duration-200">
                                        <div x-show="isUploading" class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] rounded-lg flex flex-col items-center justify-center text-white text-[11px] font-medium gap-1.5 p-2">
                                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                            <span>Mengunggah...</span>
                                        </div>
                                        <div x-show="!isUploading && (isSuccess || previewUrl)" class="absolute bottom-3 right-1.5 bg-emerald-500/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow">
                                            ✓ Terpasang
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!previewUrl" class="h-40 w-full flex flex-col items-center justify-center border border-dashed border-slate-300 rounded-lg bg-white mb-2 text-slate-400">
                                    <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    <span class="text-[10px]">Samping Kiri</span>
                                </div>
                            </div>
                            <div x-show="errorMessage" x-text="errorMessage" class="text-[10px] text-rose-500 font-medium mb-1"></div>
                        </div>
                        <label class="cursor-pointer px-3 py-1.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span x-text="previewUrl ? 'Ambil Ulang' : 'Ambil / Buka Kamera'"></span>
                            <input type="file" accept="image/*" capture="environment" @change="handleFileSelect($event)" class="hidden">
                        </label>
                    </div>
                </div>

            <!-- ==================================================== -->
            <!-- STEP 7: RINGKASAN KUNJUNGAN & SELESAI               -->
            <!-- ==================================================== -->
            @elseif($currentStep === 7)
                <div class="text-center py-6">
                    <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-4 shadow-apple">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Alur Rekam Medis Selesai & Tersimpan</h1>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1.5">
                        Seluruh berkas pendaftaran, general consent, persetujuan tindakan medis, dokumentasi foto before & after, serta daftar BHP harian telah berhasil tersimpan ke sistem ONEW Clinic.
                    </p>

                    <div class="mt-6 border border-slate-200 rounded-xl p-4 bg-slate-50 text-left max-w-lg mx-auto text-xs space-y-2">
                        <div class="flex justify-between border-b border-slate-200 pb-1.5">
                            <span class="text-slate-500">No. Rekam Medis:</span>
                            <strong class="font-mono text-slate-900">{{ $manual_patient_code }}</strong>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-1.5">
                            <span class="text-slate-500">NO. ID:</span>
                            <strong class="font-mono text-slate-900">{{ $inbody_id ?: '-' }}</strong>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-1.5">
                            <span class="text-slate-500">Nama Pasien:</span>
                            <strong class="text-slate-900">{{ $name }}</strong>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-1.5">
                            <span class="text-slate-500">Dokter & Perawat:</span>
                            <span class="text-slate-800">{{ $bhp_doctor_name ?: '-' }} / {{ $bhp_therapist_nurse ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Status Kunjungan:</span>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold text-[10px]">Completed</span>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                        <button type="button" wire:click="goToStep(1)"
                                class="w-full sm:w-auto px-5 py-2.5 rounded-full border border-sky-300 bg-sky-50 text-sky-800 font-semibold text-xs shadow-2xs hover:bg-sky-100 transition-all flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Edit / Lengkapi Data Formulir</span>
                        </button>

                        @if($visitId)
                            <a href="{{ route('reports.visit.pdf', $visitId) }}" target="_blank"
                               class="w-full sm:w-auto px-5 py-2.5 rounded-full bg-slate-900 text-white font-semibold text-xs shadow-apple flex items-center justify-center gap-2 hover:bg-slate-800 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span>Unduh / Cetak Berkas PDF</span>
                            </a>
                        @endif

                        <button type="button" wire:click="resetNewPatient"
                                class="w-full sm:w-auto px-5 py-2.5 rounded-full border border-slate-300 bg-white text-slate-800 font-semibold text-xs shadow-2xs hover:bg-slate-50 transition-all">
                            + Pendaftaran Pasien Baru
                        </button>
                    </div>

                    @if($existingVisit && !empty($existingVisit->edit_logs))
                        <div class="mt-8 border border-slate-200 rounded-xl p-4 bg-white text-left max-w-lg mx-auto text-xs space-y-2">
                            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider flex items-center gap-1.5 border-b border-slate-200 pb-2">
                                <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Riwayat Log Pembaruan Form (Audit Trail)</span>
                            </h4>
                            <div class="max-h-48 overflow-y-auto space-y-2 divide-y divide-slate-100 pr-1">
                                @foreach(array_reverse($existingVisit->edit_logs) as $log)
                                    <div class="pt-2 text-[11px]">
                                        <div class="flex items-center justify-between text-slate-500">
                                            <span class="font-bold text-slate-800">{{ $log['editor_role'] ?? 'Petugas' }}: {{ $log['editor_name'] ?? '-' }}</span>
                                            <span class="text-[10px] text-slate-400">{{ $log['formatted_time'] ?? '' }}</span>
                                        </div>
                                        <div class="text-slate-600 font-medium mt-0.5">{{ $log['action'] ?? 'Pembaruan data' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    <!-- Sticky Bottom Navigation Bar (Go Back & Continue) -->
    <div class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-xl border-t border-slate-200/90 px-4 py-3 shadow-apple-elevated no-print">
        <div class="max-w-4xl mx-auto flex items-center justify-between gap-4">
            <!-- Tombol Go Back -->
            <div>
                @if($currentStep > 1)
                    <button type="button"
                            wire:click="goBack"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-2xs transition-all active:scale-95">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        <span>Go Back (Kembali)</span>
                    </button>
                @endif
            </div>

            <!-- Current Step Info -->
            <div class="hidden sm:block text-center">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Langkah {{ $currentStep }} dari 6</span>
                <span class="text-xs font-semibold text-slate-800">
                    @if($currentStep === 1) Formulir Pendaftaran Pasien
                    @elseif($currentStep === 2) General Consent (Persetujuan Umum)
                    @elseif($currentStep === 3) Persetujuan Tindakan Kedokteran
                    @elseif($currentStep === 4) Foto Before (5 Sisi Wajah)
                    @elseif($currentStep === 5) Tindakan & BHP Harian
                    @elseif($currentStep === 6) Foto After (5 Sisi Wajah)
                    @else Kunjungan Selesai
                    @endif
                </span>
            </div>

            <!-- Tombol Continue / Simpan -->
            <div>
                @if($currentStep < 6)
                    <button type="button"
                            @click="submitStepWithSignatures()"
                            class="inline-flex items-center gap-1.5 px-6 py-2.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs shadow-apple transition-all active:scale-95">
                        <span>Continue (Lanjutkan)</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                @elseif($currentStep === 6)
                    <button type="button"
                            @click="submitStepWithSignatures()"
                            class="inline-flex items-center gap-1.5 px-6 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-apple transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Simpan & Selesaikan Rekam Medis</span>
                    </button>
                @else
                    <button type="button"
                            wire:click="resetNewPatient"
                            class="inline-flex items-center gap-1.5 px-6 py-2.5 rounded-full bg-slate-900 text-white font-semibold text-xs shadow-apple transition-all active:scale-95">
                        <span>+ Pasien Baru</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function formFlowApp() {
    return {
        signaturePads: {},
        init() {
            this.$nextTick(() => {
                this.initCanvases();
            });

            // Re-initialize canvases when Livewire updates step or navigates
            document.addEventListener('livewire:navigated', () => {
                this.$nextTick(() => this.initCanvases());
            });

            if (window.Livewire) {
                Livewire.hook('morph.updated', () => {
                    this.$nextTick(() => this.initCanvases());
                });
            }
        },

        getInitialSig(id) {
            try {
                switch(id) {
                    case 'canvas-reg-patient': return @this.get('registration_patient_signature');
                    case 'canvas-reg-staff': return @this.get('registration_staff_signature');
                    case 'canvas-gc-patient': return @this.get('gc_patient_signature');
                    case 'canvas-gc-staff': return @this.get('gc_staff_signature');
                    case 'canvas-ic-doctor': return @this.get('ic_doctor_signature');
                    case 'canvas-ic-recipient':
                    case 'canvas-ic-recipient-stmt': return @this.get('ic_recipient_signature');
                    case 'canvas-ic-witness': return @this.get('ic_witness_signature');
                    default: return null;
                }
            } catch(e) {
                return null;
            }
        },

        syncSignatureToLivewire(id) {
            const pad = this.signaturePads[id];
            if (!pad || pad.isEmpty()) return;
            const dataUrl = pad.toDataURL('image/png');
            try {
                switch(id) {
                    case 'canvas-reg-patient': @this.set('registration_patient_signature', dataUrl, false); break;
                    case 'canvas-reg-staff': @this.set('registration_staff_signature', dataUrl, false); break;
                    case 'canvas-gc-patient': @this.set('gc_patient_signature', dataUrl, false); break;
                    case 'canvas-gc-staff': @this.set('gc_staff_signature', dataUrl, false); break;
                    case 'canvas-ic-doctor': @this.set('ic_doctor_signature', dataUrl, false); break;
                    case 'canvas-ic-recipient':
                    case 'canvas-ic-recipient-stmt': @this.set('ic_recipient_signature', dataUrl, false); break;
                    case 'canvas-ic-witness': @this.set('ic_witness_signature', dataUrl, false); break;
                }
            } catch(e) {}
        },

        setupPad(el, id) {
            this.$nextTick(() => {
                if (!el) return;
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const rect = el.getBoundingClientRect();
                const width = rect.width || el.offsetWidth || 340;
                const height = rect.height || el.offsetHeight || 192;

                el.width = width * ratio;
                el.height = height * ratio;
                const ctx = el.getContext('2d');
                ctx.scale(ratio, ratio);

                if (this.signaturePads[id]) {
                    try { this.signaturePads[id].off(); } catch(e){}
                }

                if (window.SignaturePad) {
                    const pad = new SignaturePad(el, {
                        backgroundColor: 'rgba(255, 255, 255, 0)',
                        penColor: '#0f172a',
                        minWidth: 1.5,
                        maxWidth: 3.5
                    });
                    this.signaturePads[id] = pad;

                    // Restore existing signature if previously signed or loaded
                    const sigData = this.getInitialSig(id);
                    if (sigData && typeof sigData === 'string' && sigData.startsWith('data:image')) {
                        try {
                            pad.fromDataURL(sigData, { ratio: ratio, width: width, height: height });
                        } catch(e) {
                            console.warn('Could not restore signature on ' + id, e);
                        }
                    }

                    // Auto sync to Livewire immediately when user finishes signature stroke
                    pad.addEventListener('endStroke', () => {
                        this.syncSignatureToLivewire(id);
                    });
                }
            });
        },

        initCanvases() {
            const canvasIds = [
                'canvas-reg-patient', 'canvas-reg-staff',
                'canvas-gc-patient', 'canvas-gc-staff',
                'canvas-ic-doctor', 'canvas-ic-recipient', 'canvas-ic-recipient-stmt', 'canvas-ic-witness'
            ];

            canvasIds.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    this.setupPad(el, id);
                }
            });
        },

        clearSignature(target) {
            const map = {
                'reg-patient': 'canvas-reg-patient',
                'reg-staff': 'canvas-reg-staff',
                'gc-patient': 'canvas-gc-patient',
                'gc-staff': 'canvas-gc-staff',
                'ic-doctor': 'canvas-ic-doctor',
                'ic-recipient': 'canvas-ic-recipient',
                'ic-witness': 'canvas-ic-witness',
                'canvas-ic-recipient-stmt': 'canvas-ic-recipient-stmt'
            };
            const id = map[target] || target;
            if (this.signaturePads[id]) {
                this.signaturePads[id].clear();
            }
            try {
                switch(id) {
                    case 'canvas-reg-patient': @this.set('registration_patient_signature', null, false); break;
                    case 'canvas-reg-staff': @this.set('registration_staff_signature', null, false); break;
                    case 'canvas-gc-patient': @this.set('gc_patient_signature', null, false); break;
                    case 'canvas-gc-staff': @this.set('gc_staff_signature', null, false); break;
                    case 'canvas-ic-doctor': @this.set('ic_doctor_signature', null, false); break;
                    case 'canvas-ic-recipient':
                    case 'canvas-ic-recipient-stmt': @this.set('ic_recipient_signature', null, false); break;
                    case 'canvas-ic-witness': @this.set('ic_witness_signature', null, false); break;
                }
            } catch(e) {}
        },

        submitStepWithSignatures() {
            const getSig = (id) => {
                const pad = this.signaturePads[id];
                return (pad && !pad.isEmpty()) ? pad.toDataURL('image/png') : null;
            };

            const regPat = getSig('canvas-reg-patient');
            if (regPat) @this.set('registration_patient_signature', regPat, false);

            const regStaff = getSig('canvas-reg-staff');
            if (regStaff) @this.set('registration_staff_signature', regStaff, false);

            const gcPat = getSig('canvas-gc-patient');
            if (gcPat) @this.set('gc_patient_signature', gcPat, false);

            const gcStaff = getSig('canvas-gc-staff');
            if (gcStaff) @this.set('gc_staff_signature', gcStaff, false);

            const icDoc = getSig('canvas-ic-doctor');
            if (icDoc) @this.set('ic_doctor_signature', icDoc, false);

            const icRec = getSig('canvas-ic-recipient') || getSig('canvas-ic-recipient-stmt');
            if (icRec) @this.set('ic_recipient_signature', icRec, false);

            const icWit = getSig('canvas-ic-witness');
            if (icWit) @this.set('ic_witness_signature', icWit, false);

            @this.continue();
        }
    };
}
</script>
