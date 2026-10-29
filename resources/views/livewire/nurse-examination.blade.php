<div>
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-1.5">
                <span>Alur Pelayanan</span>
                <span>/</span>
                <span class="text-slate-900">Pemeriksaan Awal Perawat</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Pemeriksaan Awal & Dokumentasi Foto</h1>
            <p class="text-sm text-slate-500 mt-1">Penetapan dokter pemeriksa, asisten jaga, dan pengambilan foto klinis awal sebelum tindakan.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-4 py-2 rounded-full border border-slate-200/80 bg-white hover:bg-slate-50 shadow-2xs self-start transition-all active:scale-[0.98]">
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Main Tablet Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Antrian Pasien Perawat (Left Column) -->
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-apple">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-900 text-sm">
                        Antrian Pasien Perawat
                    </h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 font-mono font-medium">{{ $waitingVisits->count() }}</span>
                </div>

                <div class="mt-4 space-y-2 max-h-[540px] overflow-y-auto pr-1">
                    @forelse($waitingVisits as $item)
                        <button type="button"
                                wire:click="selectVisit({{ $item->id }})"
                                class="w-full text-left p-4 rounded-2xl border transition-all flex flex-col gap-2 {{ $selectedVisitId === $item->id ? 'border-slate-900 bg-slate-50/80 shadow-xs' : 'border-slate-200/70 hover:border-slate-300 hover:bg-slate-50/50' }}">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-sm tracking-tight">{{ $item->patient?->name }}</span>
                                <span class="font-mono text-[10px] px-2 py-0.5 rounded-md bg-white text-slate-600 border border-slate-200">{{ $item->patient?->patient_code }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-400">Jam: {{ $item->created_at->format('H:i') }} WIB</span>
                                @if($item->consent_signed)
                                    <span class="text-[11px] font-medium text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">Persetujuan Lengkap</span>
                                @else
                                    <span class="text-[11px] font-medium text-amber-800 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200/60">Perlu Tanda Tangan</span>
                                @endif
                            </div>
                        </button>
                    @empty
                        <div class="py-12 text-center text-slate-400 text-xs">
                            Tidak ada antrian pasien menunggu perawat saat ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Form Pemeriksaan Perawat (Right Column) -->
        <div class="lg:col-span-8">
            @if($currentVisit)
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-9 shadow-apple">
                    <!-- Patient Summary Pill -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200/70 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-bold text-base shadow-2xs">
                                {{ strtoupper(substr($currentVisit->patient->name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900 tracking-tight">{{ $currentVisit->patient->name }}</h2>
                                <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                                    <span class="font-mono font-medium text-slate-700">{{ $currentVisit->patient->patient_code }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $currentVisit->patient->gender === 'female' ? 'Perempuan' : 'Laki-laki' }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $currentVisit->patient->phone_number }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Consent Status Pill -->
                        <div>
                            @if($currentVisit->consent_signed)
                                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-800 text-xs shadow-2xs">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <div>
                                        <span class="font-semibold">General Consent Terverifikasi</span>
                                        <div class="text-[10px] text-slate-400">{{ $currentVisit->consent_signed_at?->format('d/m/Y H:i') }} WIB</div>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('kiosk.show', $currentVisit) }}" target="_blank" class="h-9 px-4 rounded-full bg-slate-900 text-white text-xs font-semibold inline-flex items-center gap-1.5 shadow-xs transition-all active:scale-[0.98]">
                                    <span>Buka Tablet Pasien</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Examination Form -->
                    <form wire:submit="saveExamination" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Dokter Jaga -->
                            <div>
                                <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                                    Dokter Pemeriksa <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model="doctor_id"
                                        class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                                    <option value="">-- Pilih Dokter Jaga --</option>
                                    @foreach($doctors as $doc)
                                        <option value="{{ $doc->id }}">{{ $doc->name }} ({{ $doc->email }})</option>
                                    @endforeach
                                </select>
                                @error('doctor_id') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- Asisten Jaga -->
                            <div>
                                <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                                    Perawat / Asisten Jaga <span class="text-rose-500">*</span>
                                </label>
                                <input wire:model="assistant_name"
                                       type="text"
                                       placeholder="Nama asisten perawat"
                                       class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                                @error('assistant_name') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- HTML5 Camera Module (Foto Before) -->
                        <div class="pt-6 border-t border-slate-100">
                            <label class="block text-xs font-semibold uppercase text-slate-700 tracking-wider mb-1">
                                Dokumentasi Foto Awal (Foto Before)
                            </label>
                            <p class="text-xs text-slate-400 mb-4">
                                Ambil foto klinis area perawatan pasien menggunakan kamera belakang tablet secara langsung.
                            </p>

                            <div class="flex flex-col sm:flex-row items-start gap-6">
                                <!-- Camera File Input Box -->
                                <div class="w-full sm:w-1/2">
                                    <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300/80 hover:border-slate-900 rounded-3xl p-7 bg-slate-50/60 hover:bg-slate-50 cursor-pointer transition-all text-center">
                                        <div class="w-12 h-12 rounded-2xl bg-white shadow-2xs flex items-center justify-center text-slate-700 mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-800">Ambil Foto / Buka Kamera</span>
                                        <span class="text-[11px] text-slate-400 mt-1">Kamera Belakang HTML5</span>
                                        <input type="file"
                                               wire:model="photo_before"
                                               accept="image/*"
                                               capture="environment"
                                               class="hidden">
                                    </label>
                                    @error('photo_before') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror

                                    <div wire:loading wire:target="photo_before" class="text-xs text-slate-600 mt-2 font-medium flex items-center gap-1.5">
                                        <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                        <span>Memproses foto kamera...</span>
                                    </div>
                                </div>

                                <!-- Photo Preview Box -->
                                <div class="w-full sm:w-1/2">
                                    <div class="border border-slate-200/90 rounded-3xl p-3 bg-slate-50/50 flex flex-col items-center justify-center min-h-[190px]">
                                        @if($photo_before)
                                            <div class="text-center">
                                                <img src="{{ $photo_before->temporaryUrl() }}" alt="Preview Foto Before" class="h-44 w-auto object-cover rounded-2xl shadow-apple mx-auto">
                                                <span class="inline-block mt-2 text-[10px] font-semibold text-slate-700 bg-white px-2.5 py-0.5 rounded-full border border-slate-200">Foto Baru Terpilih</span>
                                            </div>
                                        @elseif($currentVisit->photo_before_path)
                                            <div class="text-center">
                                                <img src="{{ Storage::url($currentVisit->photo_before_path) }}" alt="Foto Before Sebelumnya" class="h-44 w-auto object-cover rounded-2xl shadow-apple mx-auto">
                                                <span class="inline-block mt-2 text-[10px] font-medium text-slate-400">Foto Tersimpan</span>
                                            </div>
                                        @else
                                            <div class="text-center text-slate-300 py-6">
                                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span class="text-xs text-slate-400">Preview foto akan muncul di sini</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Bar (Apple Pill Button) -->
                        <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                            <button type="submit"
                                    class="h-12 px-7 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-xs tracking-wide transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center gap-2">
                                <span>Simpan & Teruskan ke Ruang Dokter</span>
                                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center text-slate-400 shadow-apple">
                    <p class="font-semibold text-slate-800 text-sm">Pilih Pasien dari Antrian</p>
                    <p class="text-xs text-slate-400 mt-1">Pilih pasien pada daftar di sebelah kiri untuk mengisi dokter pemeriksa dan mendokumentasikan foto awal.</p>
                </div>
            @endif
        </div>
    </div>
</div>
