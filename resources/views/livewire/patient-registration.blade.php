<div class="max-w-3xl mx-auto">
    <!-- Breadcrumb & Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-1.5">
                <span>Alur Pelayanan</span>
                <span>/</span>
                <span class="text-slate-900">Registrasi Pasien</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Pendaftaran & Persetujuan Pasien</h1>
            <p class="text-sm text-slate-500 mt-1">Daftarkan data pasien baru atau cari rekam medis yang sudah terdaftar.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-4 py-2 rounded-full border border-slate-200/80 bg-white hover:bg-slate-50 shadow-2xs transition-all active:scale-[0.98]">
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Apple-Style Segmented Mode Tabs -->
    <div class="bg-slate-200/60 p-1 rounded-full flex max-w-sm mx-auto mb-8 border border-slate-200/50">
        <button type="button"
                wire:click="$set('mode', 'new')"
                class="flex-1 py-2.5 rounded-full text-xs font-semibold transition-all duration-200 {{ $mode === 'new' ? 'bg-white text-slate-950 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
            Pasien Baru
        </button>
        <button type="button"
                wire:click="$set('mode', 'search')"
                class="flex-1 py-2.5 rounded-full text-xs font-semibold transition-all duration-200 {{ $mode === 'search' ? 'bg-white text-slate-950 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
            Pasien Terdaftar
        </button>
    </div>

    <!-- Main Registration Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-apple p-6 sm:p-10">
        <form wire:submit="registerAndOpenConsent">
            @if($mode === 'new')
                <!-- Form Pasien Baru -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Form Identitas Pasien Baru</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Nomor rekam medis di-generate secara otomatis dan sekuensial.</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-mono font-medium bg-slate-100 text-slate-700 border border-slate-200/80">
                            {{ $clinic->clinic_code ?? 'ONEW' }}{{ date('y') }}-SEKUENSIAL
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Nama Lengkap -->
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Nama Lengkap Pasien <span class="text-rose-500">*</span></label>
                            <input wire:model="name"
                                   type="text"
                                   placeholder="Nama sesuai identitas"
                                   class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                            @error('name') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Nomor Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                            <input wire:model="phone_number"
                                   type="tel"
                                   placeholder="08123456789"
                                   class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                            @error('phone_number') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Jenis Kelamin <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center justify-center gap-2 h-12 rounded-2xl border cursor-pointer transition-all {{ $gender === 'female' ? 'border-slate-900 bg-slate-900 text-white font-semibold' : 'border-slate-200 hover:border-slate-300 bg-slate-50 text-slate-700' }}">
                                    <input type="radio" wire:model="gender" value="female" class="hidden">
                                    <span class="text-xs">Perempuan</span>
                                </label>
                                <label class="flex items-center justify-center gap-2 h-12 rounded-2xl border cursor-pointer transition-all {{ $gender === 'male' ? 'border-slate-900 bg-slate-900 text-white font-semibold' : 'border-slate-200 hover:border-slate-300 bg-slate-50 text-slate-700' }}">
                                    <input type="radio" wire:model="gender" value="male" class="hidden">
                                    <span class="text-xs">Laki-laki</span>
                                </label>
                            </div>
                            @error('gender') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Tanggal Lahir <span class="text-rose-500">*</span></label>
                            <input wire:model="date_of_birth"
                                   type="date"
                                   class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                            @error('date_of_birth') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">Alamat Domisili</label>
                            <textarea wire:model="address"
                                      rows="2"
                                      placeholder="Nama jalan, gedung, atau domisili saat ini..."
                                      class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all"></textarea>
                            @error('address') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

            @else
                <!-- Form Cari Pasien Terdaftar -->
                <div class="space-y-6">
                    <div class="pb-4 border-b border-slate-100">
                        <h2 class="text-base font-semibold text-slate-900">Pencarian Rekam Medis Pasien</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Masukkan nama lengkap, nomor telepon, atau nomor rekam medis pasien.</p>
                    </div>

                    @if(!$selectedPatient)
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input wire:model.live.debounce.250ms="searchQuery"
                                   type="text"
                                   placeholder="Ketik minimal 2 karakter pencarian..."
                                   class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        </div>

                        <!-- Hasil Pencarian -->
                        @if($searchResults->isNotEmpty())
                            <div class="rounded-2xl border border-slate-200/80 divide-y divide-slate-100 overflow-hidden shadow-2xs">
                                @foreach($searchResults as $item)
                                    <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-slate-900 text-sm">{{ $item->name }}</span>
                                                <span class="font-mono text-xs px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">{{ $item->patient_code }}</span>
                                            </div>
                                            <div class="text-xs text-slate-400 mt-1 flex items-center gap-3">
                                                <span>{{ $item->phone_number }}</span>
                                                <span>&bull;</span>
                                                <span>{{ $item->date_of_birth?->format('d M Y') }}</span>
                                                <span>&bull;</span>
                                                <span>{{ $item->gender === 'female' ? 'Perempuan' : 'Laki-laki' }}</span>
                                            </div>
                                        </div>
                                        <button type="button"
                                                wire:click="selectExistingPatient({{ $item->id }})"
                                                class="h-9 px-4 rounded-full text-xs font-semibold text-white bg-slate-900 hover:bg-black transition-all active:scale-[0.97]">
                                            Pilih
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @elseif(strlen(trim($searchQuery)) >= 2)
                            <div class="p-8 text-center bg-slate-50/60 rounded-2xl border border-slate-200/80 text-slate-500 text-xs">
                                Tidak ditemukan data pasien dengan kata kunci "<span class="font-semibold text-slate-800">{{ $searchQuery }}</span>".
                                <div class="mt-3">
                                    <button type="button" wire:click="$set('mode', 'new')" class="text-slate-900 font-semibold underline text-xs">
                                        Daftarkan sebagai Pasien Baru
                                    </button>
                                </div>
                            </div>
                        @endif

                    @else
                        <!-- Card Pasien Terpilih -->
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/90 flex items-start justify-between">
                            <div>
                                <span class="text-[10px] font-semibold uppercase text-slate-400 tracking-wider">Pasien Terpilih</span>
                                <h3 class="text-base font-bold text-slate-900 mt-1">{{ $selectedPatient->name }}</h3>
                                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-slate-600">
                                    <span class="font-mono font-medium bg-white px-2 py-0.5 rounded border border-slate-200 text-slate-800">{{ $selectedPatient->patient_code }}</span>
                                    <span>{{ $selectedPatient->phone_number }}</span>
                                    <span>{{ $selectedPatient->date_of_birth?->format('d F Y') }}</span>
                                    <span>{{ $selectedPatient->gender === 'female' ? 'Perempuan' : 'Laki-laki' }}</span>
                                </div>
                                @if($selectedPatient->address)
                                    <p class="text-xs text-slate-400 mt-2">{{ $selectedPatient->address }}</p>
                                @endif
                            </div>
                            <button type="button" wire:click="resetSelection" class="text-xs text-slate-400 hover:text-slate-700 p-2 font-medium">
                                Ganti
                            </button>
                        </div>
                    @endif

                    @error('selectedPatientId') <span class="text-xs text-rose-500 block font-medium">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Submit Button Bar (Apple Pill Style) -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-400">
                    Setelah registrasi, tablet kiosk persetujuan pasien dapat langsung dibuka.
                </p>

                <button type="submit"
                        class="w-full sm:w-auto h-12 px-7 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-xs tracking-wide transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                    <span>Buka Mode Tablet Pasien</span>
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>
