<div>
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-1.5">
                <span>Alur Pelayanan</span>
                <span>/</span>
                <span class="text-slate-900">Langkah 3: Pemeriksaan Medis</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Pemeriksaan & Tindakan Medis</h1>
            <p class="text-sm text-slate-500 mt-1">Pilih tindakan estetika, dokumentasikan foto akhir, dan selesaikan kunjungan pasien.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-4 py-2 rounded-full border border-slate-200/80 bg-white hover:bg-slate-50 shadow-2xs self-start transition-all active:scale-[0.98]">
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Main Tablet Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Antrian Pasien Dokter (Left Column) -->
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-apple">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-900 text-sm">
                        Antrian Pasien Dokter
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
                                <span class="text-slate-400">Dr: {{ $item->doctor?->name ?? 'Belum Ditentukan' }}</span>
                                <span class="text-[11px] font-medium text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-full">
                                    {{ $item->status === 'in_treatment' ? 'Dalam Tindakan' : 'Menunggu' }}
                                </span>
                            </div>
                        </button>
                    @empty
                        <div class="py-12 text-center text-slate-400 text-xs">
                            Tidak ada pasien yang menunggu di ruang dokter saat ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Treatment Form & Clinical Workspace (Right Column) -->
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
                                    <span>{{ $currentVisit->patient->date_of_birth ? $currentVisit->patient->date_of_birth->age . ' Tahun' : '—' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor & Assistant Info -->
                        <div class="text-left sm:text-right text-xs text-slate-500">
                            <div><span class="text-slate-400">Dokter:</span> <strong class="text-slate-900 font-semibold">{{ $currentVisit->doctor?->name ?? 'dr. Alana Wijaya' }}</strong></div>
                            @if($currentVisit->assistant_name)
                                <div class="mt-0.5"><span class="text-slate-400">Asisten:</span> {{ $currentVisit->assistant_name }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Clinical Photo Comparison (Before & After Preview) -->
                    <div class="mb-8 p-5 sm:p-6 rounded-3xl bg-slate-50/70 border border-slate-200/70">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-700 mb-4 flex items-center justify-between">
                            <span>Komparasi Foto Klinis</span>
                            <span class="text-slate-400 font-normal">Dokumentasi Rekam Medis</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Foto Before -->
                            <div class="border border-slate-200/90 bg-white rounded-2xl p-4 text-center shadow-2xs">
                                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block mb-3">Foto Awal (Sebelum Tindakan)</span>
                                @if($currentVisit->photo_before_path)
                                    <img src="{{ Storage::url($currentVisit->photo_before_path) }}" alt="Foto Before" class="h-44 w-auto object-cover rounded-xl mx-auto shadow-apple">
                                @else
                                    <div class="h-44 flex flex-col items-center justify-center bg-slate-50 rounded-xl text-slate-400 text-xs">
                                        <svg class="w-7 h-7 text-slate-300 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>Belum ada foto awal</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Foto After -->
                            <div class="border border-slate-200/90 bg-white rounded-2xl p-4 text-center shadow-2xs">
                                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block mb-3">Foto Akhir (Hasil Tindakan)</span>
                                @if($photo_after)
                                    <img src="{{ $photo_after->temporaryUrl() }}" alt="Preview Foto After" class="h-44 w-auto object-cover rounded-xl mx-auto shadow-apple">
                                    <span class="inline-block mt-2 text-[10px] font-semibold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-full border border-slate-200">Foto Baru Terpilih</span>
                                @elseif($currentVisit->photo_after_path)
                                    <img src="{{ Storage::url($currentVisit->photo_after_path) }}" alt="Foto After Sebelumnya" class="h-44 w-auto object-cover rounded-xl mx-auto shadow-apple">
                                @else
                                    <label class="h-44 flex flex-col items-center justify-center border-2 border-dashed border-slate-300/80 hover:border-slate-900 rounded-xl bg-slate-50 hover:bg-slate-50/80 cursor-pointer text-slate-500 transition-all">
                                        <svg class="w-7 h-7 mb-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                        <span class="text-xs font-semibold text-slate-800">Ambil Foto Akhir</span>
                                        <span class="text-[10px] text-slate-400 mt-0.5">Kamera Belakang HTML5</span>
                                        <input type="file" wire:model="photo_after" accept="image/*" capture="environment" class="hidden">
                                    </label>
                                @endif

                                @if($photo_after || $currentVisit->photo_after_path)
                                    <div class="mt-2.5">
                                        <label class="text-[11px] font-medium text-slate-600 hover:text-slate-900 underline cursor-pointer">
                                            <span>Ambil Ulang Foto</span>
                                            <input type="file" wire:model="photo_after" accept="image/*" capture="environment" class="hidden">
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div wire:loading wire:target="photo_after" class="text-xs text-slate-600 mt-2 font-medium flex items-center justify-center gap-1.5">
                            <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            <span>Memproses foto akhir...</span>
                        </div>
                    </div>

                    <!-- Checklist Tindakan & Catatan Klinis -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
                                Tindakan Medis yang Diberikan <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-xs text-slate-400">Pilih satu atau lebih tindakan</span>
                        </div>

                        <div class="space-y-3">
                            @foreach($treatments as $treatment)
                                @php
                                    $isSelected = in_array((string) $treatment->id, $selectedTreatments);
                                @endphp
                                <div class="p-4 sm:p-5 rounded-2xl border transition-all {{ $isSelected ? 'border-slate-900 bg-slate-50/80 shadow-2xs' : 'border-slate-200/80 hover:border-slate-300 bg-white' }}">
                                    <div class="flex items-center justify-between cursor-pointer" wire:click="toggleTreatment({{ $treatment->id }})">
                                        <div class="flex items-center gap-3.5">
                                            <input type="checkbox"
                                                   {{ $isSelected ? 'checked' : '' }}
                                                   class="rounded-md text-slate-900 focus:ring-slate-900 w-4 h-4 cursor-pointer">
                                            <div>
                                                <span class="font-bold text-slate-900 text-sm">{{ $treatment->name }}</span>
                                                <span class="text-xs text-slate-400 ml-2">({{ $treatment->category ?? 'Estetika' }})</span>
                                            </div>
                                        </div>
                                        <div class="font-mono font-medium text-xs text-slate-700">
                                            Rp {{ number_format($treatment->price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <!-- Catatan Klinis Khusus Tindakan Ini -->
                                    @if($isSelected)
                                        <div class="mt-3.5 pt-3.5 border-t border-slate-200/70">
                                            <label class="block text-[11px] font-semibold text-slate-600 uppercase tracking-wider mb-1.5">
                                                Catatan Klinis Dokter / Pengaturan Dosis / Area Tindakan:
                                            </label>
                                            <input type="text"
                                                   wire:model="treatmentNotes.{{ $treatment->id }}"
                                                   placeholder="Deskripsi parameter tindakan klinis..."
                                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 transition-all">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @error('selectedTreatments') <span class="text-xs text-rose-500 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Action Submit Bar (Apple Pill Button) -->
                    <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-xs text-slate-400">
                            Menyelesaikan pemeriksaan akan memperbarui rekam medis dan memindahkan pasien ke status Kunjungan Selesai.
                        </p>

                        <button type="button"
                                wire:click="completeVisitAndSync"
                                wire:loading.attr="disabled"
                                class="w-full sm:w-auto h-12 px-7 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-xs tracking-wide transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="completeVisitAndSync">
                                Selesaikan Kunjungan
                            </span>
                            <span wire:loading wire:target="completeVisitAndSync" class="flex items-center gap-2">
                                <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <span>Menyimpan...</span>
                            </span>
                        </button>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center text-slate-400 shadow-apple">
                    <p class="font-semibold text-slate-800 text-sm">Pilih Pasien dari Antrian</p>
                    <p class="text-xs text-slate-400 mt-1">Pilih pasien pada daftar di sebelah kiri untuk mencatat tindakan medis dan menyelesaikan alur.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Apple HIG Completion Modal -->
    @if($showSuccessModal && $completedVisitId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-md p-4">
            <div class="bg-white rounded-3xl p-7 sm:p-10 max-w-md w-full text-center shadow-apple-elevated border border-slate-200">
                <div class="w-16 h-16 rounded-full bg-slate-900 text-white flex items-center justify-center mx-auto mb-5 shadow-apple">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>

                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Kunjungan Berhasil Diselesaikan</h3>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                    Rekam medis kunjungan #{{ $completedVisitId }} telah dicatat dan kunjungan dinyatakan selesai.
                </p>

                <div class="my-6 p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-left text-xs space-y-2 text-slate-600">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Status Kunjungan:</span>
                        <span class="font-semibold text-emerald-700">Selesai (Completed)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Waktu Selesai:</span>
                        <span class="font-mono text-slate-700">{{ date('d M Y H:i:s') }}</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('reports.visit.pdf', $completedVisitId) }}"
                       target="_blank"
                       class="w-full sm:w-auto h-11 px-6 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-xs transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Cetak Ringkasan Medis (PDF)</span>
                    </a>

                    <button type="button"
                            wire:click="$set('showSuccessModal', false)"
                            class="w-full sm:w-auto h-11 px-5 rounded-full font-semibold text-slate-600 hover:bg-slate-100 text-xs">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
