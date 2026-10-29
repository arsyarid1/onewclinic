<div class="w-full">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-1.5">
                <span>Alur Pelayanan</span>
                <span>/</span>
                <span class="text-slate-900">Registrasi & General Consent</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Registrasi Pasien, Persetujuan & Pemeriksaan Awal</h1>
            <p class="text-sm text-slate-500 mt-1">Lengkapi identitas pasien, tanda tangan persetujuan umum (General Consent), dan dokumentasi foto sebelum tindakan.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-4 py-2 rounded-full border border-slate-200/80 bg-white hover:bg-slate-50 shadow-2xs self-start transition-all active:scale-[0.98]">
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Mode Selector Tabs -->
    <div class="bg-slate-200/60 p-1 rounded-full flex max-w-sm mb-8 border border-slate-200/50">
        <button type="button"
                wire:click="$set('mode', 'new')"
                class="flex-1 py-2 rounded-full text-xs font-semibold transition-all duration-200 {{ $mode === 'new' ? 'bg-white text-slate-950 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
            Pasien Baru
        </button>
        <button type="button"
                wire:click="$set('mode', 'search')"
                class="flex-1 py-2 rounded-full text-xs font-semibold transition-all duration-200 {{ $mode === 'search' ? 'bg-white text-slate-950 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
            Pasien Terdaftar
        </button>
    </div>

    <!-- Main Registration & Consent Card (Full Width) -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-apple p-6 sm:p-10">
        <form wire:submit="registerAndSubmitVisit">
            @if($mode === 'search' && !$selectedPatient)
                <!-- Search Box for Existing Patient -->
                <div class="mb-8 pb-8 border-b border-slate-100">
                    <h2 class="text-base font-semibold text-slate-900 mb-1">Cari Data Pasien Terdaftar</h2>
                    <p class="text-xs text-slate-400 mb-4">Ketik nama pasien, nomor telepon/WhatsApp, nomor rekam medis, atau NIK.</p>

                    <div class="relative max-w-xl">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input wire:model.live.debounce.250ms="searchQuery"
                               type="text"
                               placeholder="Ketik minimal 2 karakter..."
                               class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                    </div>

                    @if($searchResults->isNotEmpty())
                        <div class="mt-3 max-w-xl rounded-2xl border border-slate-200/80 divide-y divide-slate-100 overflow-hidden shadow-2xs">
                            @foreach($searchResults as $item)
                                <div class="p-3.5 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-slate-900 text-sm">{{ $item->name }}</span>
                                            <span class="font-mono text-xs px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ $item->patient_code }}</span>
                                        </div>
                                        <div class="text-xs text-slate-400 mt-0.5 flex items-center gap-2">
                                            <span>{{ $item->phone_number }}</span>
                                            @if($item->nik) <span>&bull; NIK: {{ $item->nik }}</span> @endif
                                        </div>
                                    </div>
                                    <button type="button"
                                            wire:click="selectExistingPatient({{ $item->id }})"
                                            class="h-8 px-4 rounded-full text-xs font-semibold text-white bg-slate-900 hover:bg-black transition-all">
                                        Pilih
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @error('selectedPatientId') <span class="text-xs text-rose-500 mt-2 block font-medium">{{ $message }}</span> @enderror
                </div>
            @endif

            @if($mode === 'search' && $selectedPatient)
                <!-- Selected Patient Banner -->
                <div class="mb-8 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-semibold uppercase text-slate-400 tracking-wider">Pasien Terdaftar</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <h3 class="text-base font-bold text-slate-900">{{ $selectedPatient->name }}</h3>
                            <span class="font-mono text-xs px-2 py-0.5 rounded bg-white text-slate-700 border border-slate-200 font-medium">{{ $selectedPatient->patient_code }}</span>
                        </div>
                    </div>
                    <button type="button" wire:click="resetSelection" class="text-xs text-slate-500 hover:text-slate-900 px-3 py-1.5 rounded-full border border-slate-200 bg-white">
                        Ganti Pasien
                    </button>
                </div>
            @endif

            <!-- Section 1: Data Identitas Pasien -->
            <div class="space-y-6">
                <div class="pb-3 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">1. Identitas Pasien</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Data diri pasien untuk pencatatan rekam medis elektronik.</p>
                    </div>
                    @if($mode === 'new')
                        <span class="px-3 py-1 rounded-full text-xs font-mono font-medium bg-slate-100 text-slate-700 border border-slate-200/80">
                            {{ $clinic->clinic_code ?? 'ONEW' }}{{ date('y') }}-SEKUENSIAL
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Nama Lengkap Pasien <span class="text-rose-500">*</span></label>
                        <input wire:model="name"
                               type="text"
                               placeholder="Nama sesuai KTP/identitas"
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        @error('name') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">NIK / Nomor Identitas</label>
                        <input wire:model="nik"
                               type="text"
                               maxlength="20"
                               placeholder="16 digit NIK"
                               class="w-full font-mono px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        @error('nik') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Tempat Lahir</label>
                        <input wire:model="birth_place"
                               type="text"
                               placeholder="Kota kelahiran"
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        @error('birth_place') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input wire:model="date_of_birth"
                               type="date"
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        @error('date_of_birth') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center justify-center gap-2 h-11 rounded-2xl border cursor-pointer transition-all {{ $gender === 'female' ? 'border-slate-900 bg-slate-900 text-white font-semibold' : 'border-slate-200 hover:border-slate-300 bg-slate-50 text-slate-700' }}">
                                <input type="radio" wire:model="gender" value="female" class="hidden">
                                <span class="text-xs">Perempuan</span>
                            </label>
                            <label class="flex items-center justify-center gap-2 h-11 rounded-2xl border cursor-pointer transition-all {{ $gender === 'male' ? 'border-slate-900 bg-slate-900 text-white font-semibold' : 'border-slate-200 hover:border-slate-300 bg-slate-50 text-slate-700' }}">
                                <input type="radio" wire:model="gender" value="male" class="hidden">
                                <span class="text-xs">Laki-laki</span>
                            </label>
                        </div>
                        @error('gender') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- No HP / WhatsApp -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">No. HP / WhatsApp <span class="text-rose-500">*</span></label>
                        <input wire:model="phone_number"
                               type="tel"
                               placeholder="08123456789"
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        @error('phone_number') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email (Opsional) -->
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Email (Opsional)</label>
                        <input wire:model="email"
                               type="email"
                               placeholder="alamat@email.com"
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        @error('email') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-3">
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Alamat Tinggal / Domisili</label>
                        <textarea wire:model="address"
                                  rows="2"
                                  placeholder="Nama jalan, gedung, perumahan, kelurahan, kota..."
                                  class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all"></textarea>
                        @error('address') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kontak Darurat (Nama) -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Kontak Darurat (Nama)</label>
                        <input wire:model="emergency_contact_name"
                               type="text"
                               placeholder="Nama kontak darurat"
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        @error('emergency_contact_name') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Hubungan -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Hubungan</label>
                        <input wire:model="emergency_contact_relation"
                               type="text"
                               placeholder="Contoh: Suami / Istri / Orang Tua / Saudara"
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        @error('emergency_contact_relation') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- No. Kontak Darurat -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">No. Kontak Darurat</label>
                        <input wire:model="emergency_contact_phone"
                               type="tel"
                               placeholder="08198765432"
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        @error('emergency_contact_phone') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Pilihan Pasien Tambahan -->
                <div class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 cursor-pointer">
                        <input type="checkbox" wire:model="whatsapp_reminder" class="rounded text-slate-900 focus:ring-slate-900 w-4 h-4">
                        <span class="text-xs text-slate-700">Pengingat jadwal & komunikasi administrasi melalui WhatsApp</span>
                    </label>
                    <label class="flex items-center gap-3 p-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 cursor-pointer">
                        <input type="checkbox" wire:model="promo_consent" class="rounded text-slate-900 focus:ring-slate-900 w-4 h-4">
                        <span class="text-xs text-slate-700">Informasi promosi layanan klinik (opsional)</span>
                    </label>
                </div>
            </div>

            <!-- Section 2: Pemeriksaan Awal & Dokter Pemeriksa -->
            <div class="space-y-6 mt-10 pt-8 border-t border-slate-200/80">
                <div class="pb-3 border-b border-slate-100">
                    <h2 class="text-base font-semibold text-slate-900">2. Pemeriksaan Awal & Tim Medis</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Penugasan dokter pemeriksa, perawat jaga, dan dokumentasi foto sebelum tindakan.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Dokter Pemeriksa Jaga <span class="text-rose-500">*</span></label>
                        <select wire:model="doctor_id"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                            <option value="">-- Pilih Dokter Jaga --</option>
                            @foreach($doctors as $doc)
                                <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                            @endforeach
                        </select>
                        @error('doctor_id') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Nama Perawat / Asisten Jaga <span class="text-rose-500">*</span></label>
                        <input wire:model="assistant_name"
                               type="text"
                               placeholder="Nama perawat pendamping"
                               class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        @error('assistant_name') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Foto Before Module -->
                <div class="pt-4 border-t border-slate-100">
                    <label class="block text-xs font-semibold uppercase text-slate-700 tracking-wider mb-1">
                        Dokumentasi Foto Sebelum Tindakan (Foto Before)
                    </label>
                    <p class="text-xs text-slate-400 mb-3">Ambil foto klinis area perawatan pasien menggunakan kamera tablet atau unggah file foto.</p>

                    <div class="flex flex-col sm:flex-row items-start gap-5">
                        <div class="w-full sm:w-1/2">
                            <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300/80 hover:border-slate-900 rounded-3xl p-6 bg-slate-50/50 hover:bg-slate-50 cursor-pointer transition-all text-center">
                                <svg class="w-7 h-7 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="text-xs font-semibold text-slate-800">Ambil Foto / Buka Kamera</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Kamera Belakang HTML5</span>
                                <input type="file" wire:model="photo_before" accept="image/*" capture="environment" class="hidden">
                            </label>
                            @error('photo_before') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="photo_before" class="text-xs text-slate-600 mt-2 font-medium flex items-center gap-1.5">
                                <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <span>Memproses foto...</span>
                            </div>
                        </div>

                        <div class="w-full sm:w-1/2">
                            <div class="border border-slate-200 rounded-3xl p-3 bg-slate-50/50 flex flex-col items-center justify-center min-h-[160px]">
                                @if($photo_before)
                                    <img src="{{ $photo_before->temporaryUrl() }}" alt="Preview Foto Before" class="h-36 w-auto object-cover rounded-2xl shadow-apple mx-auto">
                                    <span class="inline-block mt-2 text-[10px] font-semibold text-slate-700 bg-white px-2 py-0.5 rounded-full border border-slate-200">Foto Siap Disimpan</span>
                                @else
                                    <div class="text-center text-slate-300 py-6">
                                        <svg class="w-7 h-7 mx-auto text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs text-slate-400">Preview foto akan muncul di sini</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Dokumen General Consent & Tanda Tangan Pasien -->
            <div class="space-y-6 mt-10 pt-8 border-t border-slate-200/80">
                <div class="pb-3 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">3. Persetujuan Umum (General Consent) & Tanda Tangan</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Formulir Persetujuan Umum Pasien Klinik Utama ONEW.</p>
                    </div>
                    <span class="text-xs text-slate-500 font-mono">FORMULIR PERSETUJUAN RESMI</span>
                </div>

                <!-- Dokumen General Consent Resmi (8 Poin Lengkap) -->
                <div class="p-6 rounded-3xl bg-slate-50/70 border border-slate-200/80 max-h-72 overflow-y-auto text-xs leading-relaxed text-slate-700 space-y-4">
                    <div>
                        <h3 class="font-bold text-slate-900 uppercase">1. Persetujuan Pendaftaran dan Pelayanan Umum</h3>
                        <p class="mt-1">Saya bersedia didaftarkan sebagai pasien dan menerima penjelasan mengenai alur layanan, ketentuan administrasi, serta pelayanan awal yang sesuai kebutuhan, seperti wawancara riwayat kesehatan, pemeriksaan tanda vital, pemeriksaan fisik dasar, konsultasi, dan observasi noninvasif sesuai penilaian tenaga medis. Saya memahami bahwa setiap pemeriksaan, pemberian obat, dan rencana terapi akan dijelaskan sesuai kebutuhan. Tindakan yang memerlukan persetujuan khusus, termasuk tindakan invasif atau berisiko, injeksi, penggunaan laser, anestesi, dan pembedahan, tidak otomatis disetujui melalui formulir ini dan memerlukan informed consent tersendiri sesuai ketentuan.</p>
                    </div>

                    <div>
                        <h3 class="font-bold text-slate-900 uppercase">2. Hak dan Kewajiban Pasien</h3>
                        <p class="mt-1">Saya telah memperoleh kesempatan untuk mengetahui hak saya, termasuk mendapatkan informasi yang dapat dipahami tentang kondisi dan rencana pelayanan, menanyakan alternatif dan biaya, memperoleh privasi serta kerahasiaan rekam medis, menyampaikan keluhan, dan menerima atau menolak tindakan sesuai ketentuan yang berlaku. Saya berkewajiban memberikan data identitas dan riwayat kesehatan secara benar, menginformasikan alergi/obat yang digunakan, mengikuti aturan keselamatan klinik, menghormati petugas dan pasien lain, serta memenuhi kewajiban pembayaran untuk layanan yang telah disetujui.</p>
                    </div>

                    <div>
                        <h3 class="font-bold text-slate-900 uppercase">3. Rekam Medis dan Pelindungan Data Pribadi</h3>
                        <p class="mt-1">Saya memahami bahwa klinik mengumpulkan dan mengolah data identitas, kontak, riwayat kesehatan, hasil pemeriksaan, diagnosis, terapi, dan informasi pembayaran untuk pendaftaran, pelayanan, pencatatan rekam medis elektronik, administrasi, penagihan, serta pemenuhan kewajiban hukum. Akses dibatasi kepada pihak berwenang sesuai kebutuhan dan kerahasiaan tetap dijaga. Data dapat disampaikan kepada tenaga kesehatan atau fasilitas penerima rujukan dan sistem pelaporan/integrasi kesehatan yang diwajibkan peraturan, sebatas diperlukan. Pengungkapan kepada pihak lain, termasuk penjamin/asuransi, dilaksanakan sesuai dasar hukum atau persetujuan yang berlaku. Foto atau rekaman untuk publikasi/promosi tidak termasuk dalam persetujuan ini.</p>
                    </div>

                    <div>
                        <h3 class="font-bold text-slate-900 uppercase">4. Biaya dan Administrasi</h3>
                        <p class="mt-1">Saya memahami bahwa biaya konsultasi, pemeriksaan penunjang, obat, dan tindakan tambahan dapat berbeda. Klinik akan menyampaikan informasi biaya sebelum layanan diberikan, sepanjang keadaan memungkinkan. Saya berhak mengajukan pertanyaan dan menyetujui atau menolak layanan tambahan sesuai ketentuan medis dan hukum.</p>
                    </div>

                    <div>
                        <h3 class="font-bold text-slate-900 uppercase">5. Keadaan Darurat dan Rujukan</h3>
                        <p class="mt-1">Apabila terjadi kondisi darurat yang mengancam nyawa atau keselamatan, saya memahami bahwa tenaga medis dapat memberikan pertolongan segera sesuai kewenangan dan ketentuan yang berlaku. Bila pelayanan yang dibutuhkan tidak tersedia, klinik dapat menyarankan atau mengatur rujukan ke fasilitas yang sesuai, dengan pemberitahuan kepada saya/keluarga sejauh memungkinkan.</p>
                    </div>

                    <div>
                        <h3 class="font-bold text-slate-900 uppercase">6. Komunikasi dan Dokumentasi Medis</h3>
                        <p class="mt-1">Klinik dapat menghubungi nomor kontak saya untuk keperluan pelayanan dan administrasi. Penggunaan WhatsApp dan pengiriman promosi mengikuti pilihan saya. Informasi medis sensitif hanya disampaikan dengan memperhatikan verifikasi identitas dan kerahasiaan. Dokumentasi foto/video klinis, jika diperlukan, akan dijelaskan dan dimintakan persetujuan sesuai tujuan penggunaannya; penggunaan untuk promosi memerlukan persetujuan terpisah.</p>
                    </div>

                    <div>
                        <h3 class="font-bold text-slate-900 uppercase">7. Pilihan Pasien</h3>
                        <p class="mt-1">Pengingat jadwal dan komunikasi administrasi melalui WhatsApp, promosi layanan klinik (opsional), verifikasi klaim penjamin, dan penunjukan penerima informasi medis.</p>
                    </div>

                    <div>
                        <h3 class="font-bold text-slate-900 uppercase">8. Pernyataan dan Persetujuan</h3>
                        <p class="mt-1">Saya menyatakan telah membaca atau mendapatkan penjelasan formulir ini dengan bahasa yang saya pahami, memiliki kesempatan bertanya, dan memahami bahwa persetujuan umum ini tidak menggantikan persetujuan tindakan kedokteran khusus. Saya memberikan persetujuan untuk pendaftaran dan pelayanan umum sebagaimana dijelaskan di atas.</p>
                    </div>
                </div>

                <!-- Checkbox Persetujuan -->
                <div>
                    <label class="flex items-start gap-3.5 p-4 rounded-2xl border border-slate-200 bg-slate-50/60 hover:bg-slate-50 cursor-pointer select-none">
                        <input type="checkbox" wire:model="agreementChecked" class="mt-0.5 rounded-md text-slate-900 focus:ring-slate-900 w-4 h-4 cursor-pointer">
                        <span class="text-xs text-slate-900 font-medium">
                            Saya telah membaca, memahami, dan menyetujui seluruh ketentuan Dokumen Persetujuan Umum (General Consent) di atas.
                        </span>
                    </label>
                    @error('agreementChecked') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Digital Signature Pad Canvas (Langsung di Tablet/Layar) -->
                <div wire:ignore x-data="inlineSignaturePad()" x-init="initSignature()" class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
                            Bubuhkan Tanda Tangan Pasien / Wali di Sini <span class="text-rose-500">*</span>
                        </label>
                        <button type="button"
                                @click="clearSignature()"
                                class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-3.5 py-1.5 rounded-full border border-slate-200 bg-white hover:bg-slate-100 transition-colors active:scale-[0.97]">
                            Bersihkan Tanda Tangan
                        </button>
                    </div>

                    <div class="relative w-full border-2 border-slate-200 hover:border-slate-300 rounded-2xl bg-white overflow-hidden shadow-inner transition-colors">
                        <canvas x-ref="canvas"
                                class="w-full bg-white cursor-crosshair block"
                                style="height: 200px; touch-action: none !important;"></canvas>

                        <!-- Apple-style signature baseline indicator -->
                        <div class="absolute bottom-6 left-8 right-8 border-b border-dashed border-slate-200 pointer-events-none flex justify-between items-center text-[10px] text-slate-300 font-mono select-none">
                            <span>Tanda Tangan Pasien / Wali</span>
                            <span>✕</span>
                        </div>

                        <div x-show="isEmpty"
                             class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center text-slate-300 text-xs gap-1.5 select-none">
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            <span>Bubuhkan tanda tangan di sini menggunakan jari atau pena stylus</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="mt-10 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-400">
                    Menyimpan form ini akan langsung mendaftarkan kunjungan dan mengarahkannya ke Ruang Pemeriksaan Dokter.
                </p>

                <button type="submit"
                        @click="syncSignature()"
                        class="w-full sm:w-auto h-12 px-8 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-xs tracking-wide transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                    <span>Simpan Registrasi & Teruskan ke Pemeriksaan Dokter</span>
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function inlineSignaturePad() {
    return {
        pad: null,
        isEmpty: true,
        resizeObserver: null,

        initSignature() {
            this.$nextTick(() => {
                const canvas = this.$refs.canvas;
                if (!canvas) return;

                const SigPadClass = window.SignaturePad?.default || window.SignaturePad;
                if (!SigPadClass) return;

                // 1. Inisialisasi SignaturePad terlebih dahulu
                this.pad = new SigPadClass(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: '#0f172a',
                    minWidth: 1.8,
                    maxWidth: 4.0,
                    throttle: 0,
                    velocityFilterWeight: 0.7
                });

                // 2. Fungsi resize dengan getBoundingClientRect yang presisi dan DPI scaling
                const resizeCanvas = () => {
                    if (!canvas || !this.pad) return;

                    const rect = canvas.getBoundingClientRect();
                    const width = rect.width;
                    const height = rect.height;
                    if (width <= 0 || height <= 0) return;

                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    const targetWidth = Math.round(width * ratio);
                    const targetHeight = Math.round(height * ratio);

                    // Hindari loop jika dimensi sudah presisi
                    if (canvas.width === targetWidth && canvas.height === targetHeight) {
                        return;
                    }

                    const data = this.pad.toData();

                    canvas.width = targetWidth;
                    canvas.height = targetHeight;

                    const ctx = canvas.getContext('2d');
                    ctx.scale(ratio, ratio);

                    this.pad.clear();
                    if (data && data.length > 0) {
                        this.pad.fromData(data);
                    }
                };

                // Panggil resize pada frame render berikutnya saat layout stabil
                requestAnimationFrame(() => resizeCanvas());

                // Pasang ResizeObserver agar akurat saat orientasi tablet berputar atau zoom
                if (window.ResizeObserver) {
                    this.resizeObserver = new ResizeObserver(() => {
                        resizeCanvas();
                    });
                    this.resizeObserver.observe(canvas);
                } else {
                    window.addEventListener('resize', resizeCanvas);
                }

                this.pad.addEventListener('endStroke', () => {
                    this.isEmpty = this.pad.isEmpty();
                });
            });
        },

        clearSignature() {
            if (this.pad) {
                this.pad.clear();
                this.isEmpty = true;
                @this.set('signatureData', '', false);
            }
        },

        syncSignature() {
            if (this.pad && !this.pad.isEmpty()) {
                const data = this.pad.toDataURL('image/png');
                @this.set('signatureData', data, false);
            }
        }
    };
}
</script>
