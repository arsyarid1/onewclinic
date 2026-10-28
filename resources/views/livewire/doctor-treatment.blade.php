<div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                <span>Alur Pelayanan</span>
                <span>&rsaquo;</span>
                <span class="text-sky-600">Langkah 3: Dokter & Tindakan Medis</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Ruang Tindakan Dokter & Sinkronisasi Trustmedis</h1>
            <p class="text-sm text-slate-500 mt-0.5">Pilih tindakan estetika, input catatan klinis, dokumentasi Foto After, dan trigger sinkronisasi rekam medis Trustmedis.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 self-start">
            &larr; Dashboard Kunjungan
        </a>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Antrian Pasien Ruang Dokter (Left Column) -->
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <span>Antrian Pasien Dokter</span>
                        <span class="px-2 py-0.5 rounded-full text-xs bg-indigo-100 text-indigo-800 font-mono">{{ $waitingVisits->count() }}</span>
                    </h3>
                </div>

                <div class="mt-4 space-y-2 max-h-[500px] overflow-y-auto pr-1">
                    @forelse($waitingVisits as $item)
                        <button type="button"
                                wire:click="selectVisit({{ $item->id }})"
                                class="w-full text-left p-3.5 rounded-2xl border transition-all flex flex-col gap-1.5 {{ $selectedVisitId === $item->id ? 'border-indigo-500 bg-indigo-50/50 shadow-xs' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-sm">{{ $item->patient?->name }}</span>
                                <span class="font-mono text-[10px] px-1.5 py-0.5 rounded bg-white text-slate-600 border border-slate-200">{{ $item->patient?->patient_code }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-400">Dr: {{ $item->doctor?->name ?? 'Belum Ditentukan' }}</span>
                                <span class="text-[11px] font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200">
                                    {{ $item->status === 'in_treatment' ? 'Sedang Ditangani' : 'Menunggu' }}
                                </span>
                            </div>
                        </button>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs">
                            Tidak ada antrian pasien menunggu dokter saat ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Treatment Form & Clinical Notes (Right Column) -->
        <div class="lg:col-span-8">
            @if($currentVisit)
                <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                    <!-- Patient Summary Banner -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg">
                                {{ strtoupper(substr($currentVisit->patient->name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900">{{ $currentVisit->patient->name }}</h2>
                                <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500">
                                    <span class="font-mono font-bold text-slate-700">{{ $currentVisit->patient->patient_code }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $currentVisit->patient->gender === 'female' ? 'Perempuan' : 'Laki-laki' }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $currentVisit->patient->date_of_birth ? $currentVisit->patient->date_of_birth->age . ' Thn' : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor & Assistant Info -->
                        <div class="text-left sm:text-right text-xs text-slate-500">
                            <div><strong class="text-slate-800">Dokter:</strong> {{ $currentVisit->doctor?->name ?? 'dr. Alana Wijaya' }}</div>
                            @if($currentVisit->assistant_name)
                                <div><strong class="text-slate-800">Asisten:</strong> {{ $currentVisit->assistant_name }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Clinical Photo Comparison (Before & After Preview) -->
                    <div class="mb-6 p-4 rounded-2xl bg-slate-50/70 border border-slate-200">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3 flex items-center justify-between">
                            <span>Komparasi Foto Klinis (Before & After)</span>
                            <span class="text-slate-400 font-normal">Dokumentasi Rekam Medis</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Foto Before -->
                            <div class="border border-slate-200 bg-white rounded-xl p-3 text-center">
                                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-2">Foto Before (Awal)</span>
                                @if($currentVisit->photo_before_path)
                                    <img src="{{ Storage::url($currentVisit->photo_before_path) }}" alt="Foto Before" class="h-44 w-auto object-cover rounded-lg mx-auto shadow-xs">
                                @else
                                    <div class="h-44 flex flex-col items-center justify-center bg-slate-50 rounded-lg text-slate-400 text-xs">
                                        <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>Belum ada foto before</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Foto After (Input / Preview) -->
                            <div class="border border-slate-200 bg-white rounded-xl p-3 text-center">
                                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-2">Foto After (Hasil Tindakan)</span>
                                @if($photo_after)
                                    <img src="{{ $photo_after->temporaryUrl() }}" alt="Preview Foto After" class="h-44 w-auto object-cover rounded-lg mx-auto shadow-xs">
                                    <span class="inline-block mt-2 text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Foto Baru Terpilih</span>
                                @elseif($currentVisit->photo_after_path)
                                    <img src="{{ Storage::url($currentVisit->photo_after_path) }}" alt="Foto After Sebelumnya" class="h-44 w-auto object-cover rounded-lg mx-auto shadow-xs">
                                @else
                                    <label class="h-44 flex flex-col items-center justify-center border-2 border-dashed border-slate-300 hover:border-indigo-500 rounded-lg bg-slate-50 hover:bg-indigo-50/20 cursor-pointer text-slate-400 hover:text-indigo-600 transition-all">
                                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                        <span class="text-xs font-bold">📸 Ambil Foto After</span>
                                        <span class="text-[10px] text-slate-400 mt-0.5">Kamera Belakang HTML5</span>
                                        <input type="file" wire:model="photo_after" accept="image/*" capture="environment" class="hidden">
                                    </label>
                                @endif

                                @if($photo_after || $currentVisit->photo_after_path)
                                    <div class="mt-2">
                                        <label class="text-[11px] font-semibold text-indigo-600 hover:underline cursor-pointer">
                                            <span>Ambil Ulang Foto After</span>
                                            <input type="file" wire:model="photo_after" accept="image/*" capture="environment" class="hidden">
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div wire:loading wire:target="photo_after" class="text-xs text-indigo-600 mt-2 font-medium flex items-center justify-center gap-1.5">
                            <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            <span>Memproses Foto After...</span>
                        </div>
                    </div>

                    <!-- Checklist Tindakan Perawatan & Catatan Klinis -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-700">
                                Pilih Tindakan Perawatan Pasien <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-xs text-slate-400">Pilih satu atau lebih tindakan</span>
                        </div>

                        <div class="space-y-3">
                            @foreach($treatments as $treatment)
                                @php
                                    $isSelected = in_array((string) $treatment->id, $selectedTreatments);
                                @endphp
                                <div class="p-4 rounded-2xl border transition-all {{ $isSelected ? 'border-indigo-500 bg-indigo-50/30' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                                    <div class="flex items-center justify-between cursor-pointer" wire:click="toggleTreatment({{ $treatment->id }})">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox"
                                                   {{ $isSelected ? 'checked' : '' }}
                                                   class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer">
                                            <div>
                                                <span class="font-bold text-slate-900 text-sm">{{ $treatment->name }}</span>
                                                <span class="text-xs text-slate-400 ml-2">({{ $treatment->category ?? 'Estetika' }})</span>
                                            </div>
                                        </div>
                                        <div class="font-mono font-bold text-xs text-slate-700">
                                            Rp {{ number_format($treatment->price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <!-- Catatan Klinis Khusus Tindakan Ini -->
                                    @if($isSelected)
                                        <div class="mt-3 pt-3 border-t border-indigo-100">
                                            <label class="block text-[11px] font-semibold text-indigo-950 uppercase tracking-wider mb-1">
                                                Catatan Klinis Dokter / Pengaturan Dosis / Area Tindakan:
                                            </label>
                                            <input type="text"
                                                   wire:model="treatmentNotes.{{ $treatment->id }}"
                                                   placeholder="Contoh: Glabella 4U, Frontalis 6U. Reaksi hiperemis minimal, kompres dingin..."
                                                   class="w-full px-3 py-2 rounded-xl border border-indigo-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @error('selectedTreatments') <span class="text-xs text-rose-500 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Action Submit Bar -->
                    <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs text-slate-500 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Aksi ini akan menyusun payload EHR dan mentrigger Sinkronisasi ke Trustmedis Gateway.</span>
                        </div>

                        <button type="button"
                                wire:click="completeVisitAndSync"
                                wire:loading.attr="disabled"
                                class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm text-sm transition-all flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="completeVisitAndSync">
                                🚀 Selesaikan Kunjungan & Sync Trustmedis
                            </span>
                            <span wire:loading wire:target="completeVisitAndSync" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <span>Menyinkronkan ke Trustmedis...</span>
                            </span>
                        </button>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center text-slate-400">
                    <p class="font-medium text-slate-700">Pilih pasien dari antrian dokter di sebelah kiri</p>
                    <p class="text-xs text-slate-400 mt-1">Dokter akan mencatat tindakan, catatan klinis, foto after, dan menyelesaikan alur rekam medis.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Success Modal After Visit Completed & Synced -->
    @if($showSuccessModal && $completedVisitId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
            <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full text-center shadow-2xl border border-slate-200">
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>

                <h3 class="text-xl font-bold text-slate-900">Kunjungan Berhasil Diselesaikan!</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Rekam medis kunjungan #{{ $completedVisitId }} telah disimpan dan disinkronisasi ke mock server Trustmedis.
                </p>

                <div class="my-5 p-4 rounded-2xl bg-emerald-50/50 border border-emerald-200 text-left text-xs space-y-1.5 text-emerald-900">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Status EHR:</span>
                        <span class="font-bold text-emerald-700">SYNCED TO TRUSTMEDIS</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Timestamp:</span>
                        <span class="font-mono text-slate-700">{{ date('d M Y H:i:s') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Gateway Response:</span>
                        <span class="font-mono text-emerald-700 font-bold">200 OK (Payload Generated)</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('reports.visit.pdf', $completedVisitId) }}"
                       target="_blank"
                       class="w-full sm:w-auto px-5 py-3 rounded-xl font-bold text-white bg-slate-900 hover:bg-black shadow-xs text-xs transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>📄 Cetak Rekap Kunjungan (PDF)</span>
                    </a>

                    <button type="button"
                            wire:click="$set('showSuccessModal', false)"
                            class="w-full sm:w-auto px-5 py-3 rounded-xl font-semibold text-slate-700 hover:bg-slate-100 text-xs">
                        Tutup & Kembali
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
