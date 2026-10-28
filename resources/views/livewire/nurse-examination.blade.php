<div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                <span>Alur Pelayanan</span>
                <span>&rsaquo;</span>
                <span class="text-sky-600">Langkah 2: Perawat & Pemeriksaan Awal</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pemeriksaan Awal & Dokumentasi Foto Before</h1>
            <p class="text-sm text-slate-500 mt-0.5">Penetapan dokter jaga, asisten, dan pengambilan foto klinis awal sebelum tindakan medis.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 self-start">
            &larr; Dashboard Kunjungan
        </a>
    </div>

    <!-- Main Grid: Queue on Left, Form on Right -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Antrian Pasien Perawat (Left Column) -->
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <span>Antrian Pasien Perawat</span>
                        <span class="px-2 py-0.5 rounded-full text-xs bg-sky-100 text-sky-800 font-mono">{{ $waitingVisits->count() }}</span>
                    </h3>
                </div>

                <div class="mt-4 space-y-2 max-h-[500px] overflow-y-auto pr-1">
                    @forelse($waitingVisits as $item)
                        <button type="button"
                                wire:click="selectVisit({{ $item->id }})"
                                class="w-full text-left p-3.5 rounded-2xl border transition-all flex flex-col gap-1.5 {{ $selectedVisitId === $item->id ? 'border-sky-500 bg-sky-50/50 shadow-xs' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-sm">{{ $item->patient?->name }}</span>
                                <span class="font-mono text-[10px] px-1.5 py-0.5 rounded bg-white text-slate-600 border border-slate-200">{{ $item->patient?->patient_code }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-400">Jam: {{ $item->created_at->format('H:i') }}</span>
                                @if($item->consent_signed)
                                    <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Consent ✓</span>
                                @else
                                    <span class="text-[11px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">Perlu Consent</span>
                                @endif
                            </div>
                        </button>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs">
                            Tidak ada antrian pasien yang menunggu perawat.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Form Pemeriksaan Perawat (Right Column) -->
        <div class="lg:col-span-8">
            @if($currentVisit)
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                    <!-- Patient Summary Banner -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-sky-600 text-white flex items-center justify-center font-bold text-lg">
                                {{ strtoupper(substr($currentVisit->patient->name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900">{{ $currentVisit->patient->name }}</h2>
                                <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500">
                                    <span class="font-mono font-bold text-slate-700">{{ $currentVisit->patient->patient_code }}</span>
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
                                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
                                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    <div>
                                        <span class="font-bold">Consent Ditandatangani</span>
                                        <div class="text-[10px] text-emerald-600">{{ $currentVisit->consent_signed_at?->format('d/m/Y H:i') }} WIB</div>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('kiosk.show', $currentVisit) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-amber-100 text-amber-800 text-xs font-bold hover:bg-amber-200 transition-colors">
                                    <span>📱 Buka Kiosk TTD Pasien</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Examination Form -->
                    <form wire:submit="saveExamination" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Dokter Jaga -->
                            <div>
                                <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">
                                    Pilih Dokter Pemeriksa Jaga <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model="doctor_id"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all">
                                    <option value="">-- Pilih Dokter Jaga --</option>
                                    @foreach($doctors as $doc)
                                        <option value="{{ $doc->id }}">{{ $doc->name }} ({{ $doc->email }})</option>
                                    @endforeach
                                </select>
                                @error('doctor_id') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- Asisten Jaga -->
                            <div>
                                <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">
                                    Nama Perawat / Asisten Jaga <span class="text-rose-500">*</span>
                                </label>
                                <input wire:model="assistant_name"
                                       type="text"
                                       placeholder="Contoh: Ns. Ratna Dewi, S.Kep"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all">
                                @error('assistant_name') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- HTML5 Camera Module (Foto Before) -->
                        <div class="pt-4 border-t border-slate-100">
                            <label class="block text-xs font-semibold uppercase text-slate-700 tracking-wider mb-1">
                                Ambil Foto Klinis Pasien (Foto Before)
                            </label>
                            <p class="text-xs text-slate-400 mb-3">
                                Gunakan kamera tablet / smartphone langsung dengan atribut HTML5 camera capture atau unggah foto wajah/area perawatan pasien.
                            </p>

                            <div class="flex flex-col sm:flex-row items-start gap-6">
                                <!-- Camera File Input Box -->
                                <div class="w-full sm:w-1/2">
                                    <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 hover:border-sky-500 rounded-2xl p-6 bg-slate-50/60 hover:bg-sky-50/20 cursor-pointer transition-all text-center">
                                        <div class="w-12 h-12 rounded-full bg-white shadow-xs flex items-center justify-center text-sky-600 mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700">📸 Buka Kamera / Unggah Foto</span>
                                        <span class="text-xs text-slate-400 mt-1">HTML5 capture="environment" (Kamera Belakang)</span>
                                        <input type="file"
                                               wire:model="photo_before"
                                               accept="image/*"
                                               capture="environment"
                                               class="hidden">
                                    </label>
                                    @error('photo_before') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                                    
                                    <div wire:loading wire:target="photo_before" class="text-xs text-sky-600 mt-2 font-medium flex items-center gap-1.5">
                                        <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                        <span>Memproses foto kamera...</span>
                                    </div>
                                </div>

                                <!-- Photo Preview Box -->
                                <div class="w-full sm:w-1/2">
                                    <div class="border border-slate-200 rounded-2xl p-3 bg-slate-50/50 flex flex-col items-center justify-center min-h-[180px]">
                                        @if($photo_before)
                                            <div class="text-center">
                                                <img src="{{ $photo_before->temporaryUrl() }}" alt="Preview Foto Before" class="h-44 w-auto object-cover rounded-xl shadow-xs mx-auto">
                                                <span class="inline-block mt-2 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Foto Baru Terpilih</span>
                                            </div>
                                        @elseif($currentVisit->photo_before_path)
                                            <div class="text-center">
                                                <img src="{{ Storage::url($currentVisit->photo_before_path) }}" alt="Foto Before Sebelumnya" class="h-44 w-auto object-cover rounded-xl shadow-xs mx-auto">
                                                <span class="inline-block mt-2 text-xs font-medium text-slate-500">Foto Tersimpan Sebelumnya</span>
                                            </div>
                                        @else
                                            <div class="text-center text-slate-300 py-6">
                                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span class="text-xs">Preview foto before akan muncul di sini</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Bar -->
                        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button type="submit"
                                    class="px-6 py-3 rounded-xl font-bold text-white bg-clinic-primary hover:bg-clinic-primary-dark shadow-sm text-sm transition-all flex items-center gap-2">
                                <span>Simpan & Teruskan ke Ruang Dokter &rarr;</span>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center text-slate-400">
                    <p class="font-medium text-slate-700">Pilih pasien dari antrian di sebelah kiri</p>
                    <p class="text-xs text-slate-400 mt-1">Perawat akan mengisi dokter pemeriksa jaga dan mendokumentasikan Foto Before.</p>
                </div>
            @endif
        </div>
    </div>
</div>
