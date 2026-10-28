<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb & Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                <span>Alur Pelayanan</span>
                <span>&rsaquo;</span>
                <span class="text-sky-600">Langkah 1: Resepsionis</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Registrasi Pasien & Buka Kiosk Consent</h1>
            <p class="text-sm text-slate-500 mt-0.5">Daftarkan pasien baru atau cari pasien lama untuk memulai kunjungan hari ini.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <!-- Mode Selector Tabs -->
    <div class="bg-slate-100 p-1.5 rounded-2xl flex max-w-md mx-auto mb-6 border border-slate-200">
        <button type="button"
                wire:click="$set('mode', 'new')"
                class="flex-1 py-2 rounded-xl text-sm font-semibold transition-all {{ $mode === 'new' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
            ➕ Pasien Baru
        </button>
        <button type="button"
                wire:click="$set('mode', 'search')"
                class="flex-1 py-2 rounded-xl text-sm font-semibold transition-all {{ $mode === 'search' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
            🔍 Pasien Lama (Cari)
        </button>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs p-6 sm:p-8">
        <form wire:submit="registerAndOpenConsent">
            @if($mode === 'new')
                <!-- Form Pasien Baru -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Form Identitas Pasien Baru</h2>
                            <p class="text-xs text-slate-400">Nomor Rekam Medis (Format: {{ $clinic->clinic_code ?? 'ONEW' }}{{ date('y') }}-XXXXXX) akan digenerate otomatis secara sekuensial.</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold bg-sky-50 text-sky-700 border border-sky-200">
                            {{ $clinic->clinic_code ?? 'ONEW' }}{{ date('y') }}-AUTO
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">Nama Lengkap Pasien <span class="text-rose-500">*</span></label>
                            <input wire:model="name"
                                   type="text"
                                   placeholder="Contoh: Aurelia Clarissa"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all">
                            @error('name') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nomor Telepon / WA -->
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">Nomor Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                            <input wire:model="phone_number"
                                   type="tel"
                                   placeholder="08123456789"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all">
                            @error('phone_number') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">Jenis Kelamin <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 cursor-pointer transition-all hover:bg-slate-50 {{ $gender === 'female' ? 'border-sky-500 bg-sky-50/30 font-semibold text-sky-900' : 'text-slate-700' }}">
                                    <input type="radio" wire:model="gender" value="female" class="text-sky-600 focus:ring-sky-500">
                                    <span class="text-sm">Perempuan</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 cursor-pointer transition-all hover:bg-slate-50 {{ $gender === 'male' ? 'border-sky-500 bg-sky-50/30 font-semibold text-sky-900' : 'text-slate-700' }}">
                                    <input type="radio" wire:model="gender" value="male" class="text-sky-600 focus:ring-sky-500">
                                    <span class="text-sm">Laki-laki</span>
                                </label>
                            </div>
                            @error('gender') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">Tanggal Lahir <span class="text-rose-500">*</span></label>
                            <input wire:model="date_of_birth"
                                   type="date"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all">
                            @error('date_of_birth') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">Alamat Tinggal / Domisili</label>
                            <textarea wire:model="address"
                                      rows="2"
                                      placeholder="Nama jalan, perumahan, kelurahan, kota..."
                                      class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all"></textarea>
                            @error('address') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

            @else
                <!-- Form Cari Pasien Lama -->
                <div class="space-y-6">
                    <div class="pb-4 border-b border-slate-100">
                        <h2 class="text-base font-bold text-slate-900">Pencarian Data Pasien Terdaftar</h2>
                        <p class="text-xs text-slate-400">Masukkan nama lengkap, nomor telepon / WA, atau Kode Rekam Medis pasien.</p>
                    </div>

                    @if(!$selectedPatient)
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input wire:model.live.debounce.250ms="searchQuery"
                                   type="text"
                                   placeholder="Ketik minimal 2 karakter (misal: Aurelia, 0812, atau ONEW26)..."
                                   class="w-full pl-12 pr-4 py-3.5 rounded-2xl border border-slate-300 bg-slate-50/60 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all">
                        </div>

                        <!-- Hasil Pencarian Dropdown/List -->
                        @if($searchResults->isNotEmpty())
                            <div class="rounded-2xl border border-slate-200 divide-y divide-slate-100 overflow-hidden shadow-xs">
                                @foreach($searchResults as $item)
                                    <div class="p-4 flex items-center justify-between hover:bg-sky-50/50 transition-colors">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-900">{{ $item->name }}</span>
                                                <span class="font-mono text-xs px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold">{{ $item->patient_code }}</span>
                                            </div>
                                            <div class="text-xs text-slate-500 mt-1 flex items-center gap-3">
                                                <span>📞 {{ $item->phone_number }}</span>
                                                <span>🎂 {{ $item->date_of_birth?->format('d M Y') }}</span>
                                                <span class="capitalize">{{ $item->gender === 'female' ? 'Wanita' : 'Pria' }}</span>
                                            </div>
                                        </div>
                                        <button type="button"
                                                wire:click="selectExistingPatient({{ $item->id }})"
                                                class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-clinic-primary hover:bg-clinic-primary-dark shadow-xs transition-all">
                                            Pilih Pasien
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @elseif(strlen(trim($searchQuery)) >= 2)
                            <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200 text-slate-500 text-sm">
                                Tidak ditemukan pasien dengan kata kunci "<span class="font-semibold text-slate-800">{{ $searchQuery }}</span>".
                                <div class="mt-2">
                                    <button type="button" wire:click="$set('mode', 'new')" class="text-sky-600 font-semibold hover:underline text-xs">
                                        &rarr; Daftarkan sebagai Pasien Baru
                                    </button>
                                </div>
                            </div>
                        @endif

                    @else
                        <!-- Card Pasien Terpilih -->
                        <div class="p-5 rounded-2xl bg-sky-50/60 border border-sky-200 flex items-start justify-between">
                            <div>
                                <span class="text-xs font-semibold uppercase text-sky-700 tracking-wider">Pasien Terpilih</span>
                                <h3 class="text-lg font-bold text-slate-900 mt-0.5">{{ $selectedPatient->name }}</h3>
                                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-slate-600">
                                    <span class="font-mono font-bold bg-white px-2 py-0.5 rounded border border-sky-200 text-sky-800">{{ $selectedPatient->patient_code }}</span>
                                    <span>📞 {{ $selectedPatient->phone_number }}</span>
                                    <span>🎂 {{ $selectedPatient->date_of_birth?->format('d F Y') }}</span>
                                    <span class="capitalize">{{ $selectedPatient->gender === 'female' ? 'Perempuan' : 'Laki-laki' }}</span>
                                </div>
                                @if($selectedPatient->address)
                                    <p class="text-xs text-slate-500 mt-2">🏠 {{ $selectedPatient->address }}</p>
                                @endif
                            </div>
                            <button type="button" wire:click="resetSelection" class="text-xs text-slate-400 hover:text-rose-600 p-2">
                                Ganti Pasien
                            </button>
                        </div>
                    @endif

                    @error('selectedPatientId') <span class="text-xs text-rose-500 block font-medium">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Submit Button Bar -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-500 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>Setelah registrasi, tablet kiosk pasien akan langsung dibuka untuk tanda tangan consent.</span>
                </div>

                <button type="submit"
                        class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold text-white bg-clinic-primary hover:bg-clinic-primary-dark shadow-sm text-sm transition-all flex items-center justify-center gap-2">
                    <span>📱 Buka Mode Pasien (General Consent)</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>
