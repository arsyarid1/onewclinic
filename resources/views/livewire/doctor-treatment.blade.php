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
            <p class="text-sm text-slate-500 mt-1">Diagnosis klinis, persetujuan tindakan (informed consent), checklist perawatan & bahan habis pakai (BHP).</p>
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
                        Antrian Pasien di Ruang Tindakan
                    </h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 font-mono font-medium">{{ $waitingVisits->count() }}</span>
                </div>

                <div class="mt-4 space-y-2 max-h-[640px] overflow-y-auto pr-1">
                    @forelse($waitingVisits as $item)
                        <button type="button"
                                wire:click="selectVisit({{ $item->id }})"
                                class="w-full text-left p-4 rounded-2xl border transition-all flex flex-col gap-2 {{ $selectedVisitId === $item->id ? 'border-slate-900 bg-slate-50/80 shadow-xs' : 'border-slate-200/70 hover:border-slate-300 hover:bg-slate-50/50' }}">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-sm tracking-tight">{{ $item->patient?->name }}</span>
                                <span class="font-mono text-[10px] px-2 py-0.5 rounded-md bg-white text-slate-600 border border-slate-200">{{ $item->patient?->patient_code }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-400 truncate max-w-[150px]">Dr: {{ $item->doctor?->name ?? 'dr. Alana Wijaya' }}</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $item->status === 'in_treatment' ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $item->status === 'in_treatment' ? 'Dalam Tindakan' : 'Siap Diperiksa' }}
                                </span>
                            </div>
                        </button>
                    @empty
                        <div class="py-12 text-center text-slate-400 text-xs">
                            Tidak ada pasien yang menunggu di ruang pemeriksaan saat ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Treatment Form & Clinical Workspace (Right Column) -->
        <div class="lg:col-span-8">
            @if($currentVisit)
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-9 shadow-apple space-y-8">
                    <!-- Patient Summary Pill -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-bold text-base shadow-2xs shrink-0">
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

                        <!-- Doctor & Assistant Info + Informed Consent Trigger -->
                        <div class="flex flex-col sm:items-end gap-2 text-xs text-slate-500">
                            <div><span class="text-slate-400">Dokter:</span> <strong class="text-slate-900 font-semibold">{{ $currentVisit->doctor?->name ?? 'dr. Alana Wijaya' }}</strong></div>
                            @if($currentVisit->assistant_name)
                                <div><span class="text-slate-400">Perawat/Asisten:</span> <strong class="text-slate-800">{{ $currentVisit->assistant_name }}</strong></div>
                            @endif

                            <!-- Informed Consent Button -->
                            <button type="button"
                                    wire:click="openConsentModal"
                                    class="mt-1 px-4 py-2 rounded-full font-semibold text-xs border transition-all inline-flex items-center gap-2 {{ $hasExistingConsent ? 'border-emerald-300 bg-emerald-50 text-emerald-800 hover:bg-emerald-100' : 'border-slate-900 bg-slate-900 text-white hover:bg-black shadow-apple active:scale-[0.98]' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span>{{ $hasExistingConsent ? 'Persetujuan Tindakan (Sudah Ditandatangani ✓)' : 'Isi Persetujuan Tindakan Kedokteran' }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- 1. DIAGNOSIS MEDIS DOKTER (WD & DD) -->
                    <div class="p-5 sm:p-6 rounded-3xl bg-slate-50/70 border border-slate-200/70 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-800">
                                1. Diagnosis Medis Dokter
                            </h3>
                            <span class="text-[11px] text-slate-400 font-medium">Rekam Medis Klinis</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Working Diagnosis (WD) -->
                            <div>
                                <label class="block text-[11px] font-semibold uppercase text-slate-600 tracking-wider mb-1.5">
                                    Working Diagnosis (WD) / Diagnosis Kerja <span class="text-rose-500">*</span>
                                </label>
                                <input type="text"
                                       wire:model="diagnosis_wd"
                                       placeholder="Misal: Acne Vulgaris Grade II, Melasma Epidermal..."
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 transition-all">
                            </div>

                            <!-- Differential Diagnosis (DD) -->
                            <div>
                                <label class="block text-[11px] font-semibold uppercase text-slate-600 tracking-wider mb-1.5">
                                    Differential Diagnosis (DD) / Diagnosis Banding
                                </label>
                                <input type="text"
                                       wire:model="diagnosis_dd"
                                       placeholder="Misal: Folikulitis, Post-Inflammatory Hyperpigmentation..."
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-slate-600 tracking-wider mb-1.5">
                                Catatan Klinis / Evaluasi Tambahan Dokter
                            </label>
                            <input type="text"
                                   wire:model="doctorNotes"
                                   placeholder="Catatan anamnesis klinis, alergi obat, atau rekomendasi perawatan lanjutan..."
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 transition-all">
                        </div>
                    </div>

                    <!-- 2. KOMPARASI FOTO KLINIS (BEFORE & AFTER) -->
                    <div class="p-5 sm:p-6 rounded-3xl bg-slate-50/70 border border-slate-200/70">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-800">
                                2. Dokumentasi Foto Klinis
                            </h3>
                            <span class="text-[11px] text-slate-500">Perawat / Dokter dapat mengambil Foto After</span>
                        </div>

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
                                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block mb-3">Foto Akhir (Setelah Tindakan - Tugas Perawat/Dokter)</span>
                                @if($photo_after)
                                    <img src="{{ $photo_after->temporaryUrl() }}" alt="Preview Foto After" class="h-44 w-auto object-cover rounded-xl mx-auto shadow-apple">
                                    <span class="inline-block mt-2 text-[10px] font-semibold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-full border border-slate-200">Foto Baru Terpilih</span>
                                @elseif($currentVisit->photo_after_path)
                                    <img src="{{ Storage::url($currentVisit->photo_after_path) }}" alt="Foto After Sebelumnya" class="h-44 w-auto object-cover rounded-xl mx-auto shadow-apple">
                                @else
                                    <label class="h-44 flex flex-col items-center justify-center border-2 border-dashed border-slate-300/80 hover:border-slate-900 rounded-xl bg-slate-50 hover:bg-slate-50/80 cursor-pointer text-slate-500 transition-all">
                                        <svg class="w-7 h-7 mb-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                        <span class="text-xs font-semibold text-slate-800">Ambil Foto Akhir (After)</span>
                                        <span class="text-[10px] text-slate-400 mt-0.5">Kamera Tablet / File Upload</span>
                                        <input type="file" wire:model="photo_after" accept="image/*" capture="environment" class="hidden">
                                    </label>
                                @endif

                                @if($photo_after || $currentVisit->photo_after_path)
                                    <div class="mt-2.5">
                                        <label class="text-[11px] font-medium text-slate-600 hover:text-slate-900 underline cursor-pointer">
                                            <span>Ambil Ulang Foto Akhir</span>
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

                    <!-- 3. TINDAKAN MEDIS (TREATMENTS CHECKLIST) -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-800">
                                3. Tindakan Medis yang Diberikan <span class="text-rose-500">*</span>
                            </h3>
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
                                                Catatan Parameter / Dosis Tindakan:
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

                    <!-- 4. BHP TINDAKAN HARIAN (BAHAN BAKU & BARANG HABIS PAKAI) -->
                    <div class="p-5 sm:p-6 rounded-3xl bg-slate-50/70 border border-slate-200/70 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-800">
                                    4. BHP Tindakan Harian (Bahan Habis Pakai)
                                </h3>
                                <p class="text-[11px] text-slate-500 mt-0.5">Catat bahan baku medis yang digunakan dan jumlah kuantitasnya (Form BHP ONEW)</p>
                            </div>
                            <span class="text-xs font-mono font-semibold px-2.5 py-1 rounded-full bg-white text-slate-700 border border-slate-200">
                                {{ count($selectedConsumables) }} Bahan Dipakai
                            </span>
                        </div>

                        <!-- Table Grid of Consumables -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-[420px] overflow-y-auto pr-1">
                            @foreach($consumables as $item)
                                @php
                                    $isUsed = isset($selectedConsumables[$item->id]);
                                    $qty = $selectedConsumables[$item->id] ?? 0;
                                @endphp
                                <div class="p-3 rounded-2xl border transition-all flex flex-col justify-between gap-2 {{ $isUsed ? 'border-slate-900 bg-white shadow-2xs' : 'border-slate-200/80 bg-white/60 hover:border-slate-300' }}">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <span class="font-bold text-xs text-slate-900 block leading-tight">{{ $item->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono">{{ $item->unit }}</span>
                                        </div>
                                        <button type="button"
                                                wire:click="toggleConsumable({{ $item->id }}, {{ $item->default_quantity }})"
                                                class="text-[10px] font-semibold px-2 py-0.5 rounded-md border transition-all {{ $isUsed ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200' }}">
                                            {{ $isUsed ? 'Dipakai ✓' : '+ Pilih' }}
                                        </button>
                                    </div>

                                    @if($isUsed)
                                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                            <span class="text-[11px] text-slate-500 font-medium">Quantity (QT):</span>
                                            <div class="flex items-center gap-1.5">
                                                <button type="button"
                                                        wire:click="decrementConsumable({{ $item->id }})"
                                                        class="w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 flex items-center justify-center font-bold text-xs transition-colors">
                                                    -
                                                </button>
                                                <input type="number"
                                                       wire:model="selectedConsumables.{{ $item->id }}"
                                                       min="1"
                                                       class="w-12 text-center py-1 text-xs font-mono font-bold rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none">
                                                <button type="button"
                                                        wire:click="incrementConsumable({{ $item->id }})"
                                                        class="w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 flex items-center justify-center font-bold text-xs transition-colors">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Submit Bar (Apple Pill Button) -->
                    <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-xs text-slate-400">
                            Menyelesaikan pemeriksaan akan memfinalisasi rekam medis pasien dan menandai kunjungan selesai.
                        </p>

                        <button type="button"
                                wire:click="completeVisitAndSync"
                                wire:loading.attr="disabled"
                                class="w-full sm:w-auto h-12 px-8 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-xs tracking-wide transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="completeVisitAndSync">
                                Selesaikan Kunjungan Pasien
                            </span>
                            <span wire:loading wire:target="completeVisitAndSync" class="flex items-center gap-2">
                                <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <span>Menyimpan Rekam Medis...</span>
                            </span>
                        </button>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center text-slate-400 shadow-apple">
                    <p class="font-semibold text-slate-800 text-sm">Pilih Pasien dari Antrian</p>
                    <p class="text-xs text-slate-400 mt-1">Pilih pasien pada daftar di sebelah kiri untuk mencatat diagnosis medis, tindakan, dan bahan baku yang digunakan.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL PERSETUJUAN TINDAKAN KEDOKTERAN (INFORMED CONSENT RESMI ONEW) -->
    @if($showConsentModal && $currentVisit)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto">
            <div class="bg-white rounded-3xl max-w-4xl w-full p-6 sm:p-9 shadow-apple-elevated border border-slate-200 my-8 max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('icon.png') }}" alt="ONEW" class="h-9 w-auto object-contain">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 tracking-tight leading-none">
                                Formulir Persetujuan Tindakan Kedokteran
                            </h2>
                            <p class="text-[11px] text-slate-400 mt-1">Informed Consent & Pemberian Informasi Medis Klinis ONEW</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeConsentModal" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-base transition-colors">
                        &times;
                    </button>
                </div>

                <!-- Modal Body Form -->
                <div class="mt-6 space-y-6 text-xs text-slate-700">
                    <!-- Bagian 1: Identitas Pemberian Informasi -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[10px] font-semibold uppercase text-slate-500 tracking-wider mb-1">
                                Dokter Pelaksana Tindakan:
                            </label>
                            <input type="text" wire:model="consent_information_provider" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white font-medium text-slate-900 text-xs">
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold uppercase text-slate-500 tracking-wider mb-1">
                                Penerima Informasi / Pasien:
                            </label>
                            <input type="text" wire:model="consent_recipient_name" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white font-medium text-slate-900 text-xs">
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold uppercase text-slate-500 tracking-wider mb-1">
                                Tindakan Kedokteran yang Disetujui:
                            </label>
                            <input type="text" wire:model="consent_action_name" placeholder="Misal: Laser Rejuvenation / Peeling..." class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white font-semibold text-slate-900 text-xs">
                        </div>
                    </div>

                    <!-- Bagian 2: Tabel 11 Poin Informasi Medis (Sesuai Lampiran Resmi) -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
                        <div class="bg-slate-100 px-4 py-2.5 font-bold text-slate-900 uppercase tracking-wider text-[11px] flex justify-between items-center">
                            <span>Tabel Pemberian Informasi Tindakan Kedokteran</span>
                            <span class="text-[10px] text-slate-500 font-normal">Tanda (✓) Memahami</span>
                        </div>
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-[10px] uppercase text-slate-500 border-b border-slate-200">
                                    <th class="py-2 px-3 w-10 text-center">No</th>
                                    <th class="py-2 px-4 w-44">Jenis Informasi</th>
                                    <th class="py-2 px-4">Isi Informasi Medis</th>
                                    <th class="py-2 px-3 w-20 text-center">Tanda (✓)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="py-2.5 px-3 text-center font-mono">1</td>
                                    <td class="py-2.5 px-4 font-semibold text-slate-900">Diagnosis (WD & DD)</td>
                                    <td class="py-2.5 px-4"><input type="text" wire:model="info_diagnosis" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs"></td>
                                    <td class="py-2.5 px-3 text-center"><input type="checkbox" wire:model="info_diagnosis_checked" class="rounded text-slate-900 w-4 h-4"></td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 text-center font-mono">2</td>
                                    <td class="py-2.5 px-4 font-semibold text-slate-900">Dasar Diagnosis</td>
                                    <td class="py-2.5 px-4"><input type="text" wire:model="info_diagnosis_basis" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs"></td>
                                    <td class="py-2.5 px-3 text-center"><input type="checkbox" wire:model="info_diagnosis_basis_checked" class="rounded text-slate-900 w-4 h-4"></td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 text-center font-mono">3</td>
                                    <td class="py-2.5 px-4 font-semibold text-slate-900">Tindakan Kedokteran</td>
                                    <td class="py-2.5 px-4"><input type="text" wire:model="info_medical_procedure" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs"></td>
                                    <td class="py-2.5 px-3 text-center"><input type="checkbox" wire:model="info_medical_procedure_checked" class="rounded text-slate-900 w-4 h-4"></td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 text-center font-mono">4</td>
                                    <td class="py-2.5 px-4 font-semibold text-slate-900">Indikasi Tindakan</td>
                                    <td class="py-2.5 px-4"><input type="text" wire:model="info_indication" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs"></td>
                                    <td class="py-2.5 px-3 text-center"><input type="checkbox" wire:model="info_indication_checked" class="rounded text-slate-900 w-4 h-4"></td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 text-center font-mono">5</td>
                                    <td class="py-2.5 px-4 font-semibold text-slate-900">Tata Cara</td>
                                    <td class="py-2.5 px-4"><input type="text" wire:model="info_procedure_steps" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs"></td>
                                    <td class="py-2.5 px-3 text-center"><input type="checkbox" wire:model="info_procedure_steps_checked" class="rounded text-slate-900 w-4 h-4"></td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 text-center font-mono">6</td>
                                    <td class="py-2.5 px-4 font-semibold text-slate-900">Tujuan</td>
                                    <td class="py-2.5 px-4"><input type="text" wire:model="info_procedure_goal" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs"></td>
                                    <td class="py-2.5 px-3 text-center"><input type="checkbox" wire:model="info_procedure_goal_checked" class="rounded text-slate-900 w-4 h-4"></td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 text-center font-mono">7</td>
                                    <td class="py-2.5 px-4 font-semibold text-slate-900">Risiko</td>
                                    <td class="py-2.5 px-4"><input type="text" wire:model="info_risks" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs"></td>
                                    <td class="py-2.5 px-3 text-center"><input type="checkbox" wire:model="info_risks_checked" class="rounded text-slate-900 w-4 h-4"></td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 text-center font-mono">8</td>
                                    <td class="py-2.5 px-4 font-semibold text-slate-900">Komplikasi</td>
                                    <td class="py-2.5 px-4"><input type="text" wire:model="info_complications" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs"></td>
                                    <td class="py-2.5 px-3 text-center"><input type="checkbox" wire:model="info_complications_checked" class="rounded text-slate-900 w-4 h-4"></td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 text-center font-mono">9</td>
                                    <td class="py-2.5 px-4 font-semibold text-slate-900">Prognosis</td>
                                    <td class="py-2.5 px-4"><input type="text" wire:model="info_prognosis" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs"></td>
                                    <td class="py-2.5 px-3 text-center"><input type="checkbox" wire:model="info_prognosis_checked" class="rounded text-slate-900 w-4 h-4"></td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 text-center font-mono">10</td>
                                    <td class="py-2.5 px-4 font-semibold text-slate-900">Alternatif & Risiko</td>
                                    <td class="py-2.5 px-4"><input type="text" wire:model="info_alternatives_risks" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs"></td>
                                    <td class="py-2.5 px-3 text-center"><input type="checkbox" wire:model="info_alternatives_risks_checked" class="rounded text-slate-900 w-4 h-4"></td>
                                </tr>
                                <tr>
                                    <td class="py-2.5 px-3 text-center font-mono">11</td>
                                    <td class="py-2.5 px-4 font-semibold text-slate-900">Lain-lain</td>
                                    <td class="py-2.5 px-4"><input type="text" wire:model="info_other_notes" placeholder="Catatan opsional..." class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs"></td>
                                    <td class="py-2.5 px-3 text-center"><input type="checkbox" wire:model="info_other_notes_checked" class="rounded text-slate-900 w-4 h-4"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Bagian 3: Pernyataan Hukum Persetujuan Tindakan Kedokteran -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-2.5 text-xs text-slate-600 leading-relaxed">
                        <p class="font-semibold text-slate-900 uppercase tracking-wide text-[11px]">
                            Pernyataan dan Persetujuan Pasien / Wali:
                        </p>
                        <p>
                            Yang bertandatangan di bawah ini, saya menyatakan telah menerima, membaca, dan memahami penjelasan mengenai rencana tindakan medis, manfaat, risiko, serta kemungkinan komplikasi yang dapat terjadi. Saya menyadari bahwa ilmu kedokteran bukanlah ilmu pasti, sehingga keberhasilan tindakan kedokteran merupakan ikhtiar medis optimal dengan izin Tuhan Yang Maha Esa.
                        </p>
                    </div>

                    <!-- Bagian 4: Digital Signature Canvas untuk Pasien/Wali -->
                    <div wire:ignore x-data="procedureConsentSignaturePad()" x-init="initConsentSig()" class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-semibold uppercase tracking-wider text-slate-800">
                                Tanda Tangan Pasien / Penerima Persetujuan <span class="text-rose-500">*</span>
                            </label>
                            <button type="button" @click="clearConsentSig()" class="text-[11px] font-semibold text-slate-600 hover:text-slate-900 px-3 py-1 rounded-full border border-slate-200 bg-white hover:bg-slate-100 transition-colors">
                                Bersihkan Tanda Tangan
                            </button>
                        </div>

                        <div class="relative w-full border-2 border-slate-200 rounded-2xl bg-white overflow-hidden shadow-inner">
                            <canvas x-ref="consentCanvas" class="w-full bg-white cursor-crosshair block" style="height: 160px; touch-action: none !important;"></canvas>
                            <div class="absolute bottom-5 left-8 right-8 border-b border-dashed border-slate-200 pointer-events-none flex justify-between items-center text-[10px] text-slate-300 font-mono select-none">
                                <span>Tanda Tangan Pasien / Wali</span>
                                <span>✕</span>
                            </div>
                            <div x-show="isEmpty" class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center text-slate-300 text-xs gap-1 select-none">
                                <span>Tanda tangani menggunakan jari atau pena stylus tablet</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer Actions -->
                <div class="mt-8 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <button type="button" wire:click="closeConsentModal" class="w-full sm:w-auto h-11 px-6 rounded-full font-semibold text-slate-600 hover:bg-slate-100 text-xs transition-colors">
                        Batal
                    </button>

                    <button type="button"
                            @click="window.syncProcedureConsent()"
                            class="w-full sm:w-auto h-11 px-8 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-xs tracking-wide transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Simpan & Tandatangani Persetujuan</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

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

<script>
function procedureConsentSignaturePad() {
    return {
        pad: null,
        isEmpty: true,
        initConsentSig() {
            this.$nextTick(() => {
                const canvas = this.$refs.consentCanvas;
                if (!canvas) return;

                const SigPadClass = window.SignaturePad?.default || window.SignaturePad;
                if (!SigPadClass) return;

                this.pad = new SigPadClass(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: '#0f172a',
                    minWidth: 1.8,
                    maxWidth: 4.0,
                    throttle: 0,
                    velocityFilterWeight: 0.7
                });

                const resizeCanvas = () => {
                    if (!canvas || !this.pad) return;
                    const rect = canvas.getBoundingClientRect();
                    if (rect.width <= 0 || rect.height <= 0) return;

                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    const targetW = Math.round(rect.width * ratio);
                    const targetH = Math.round(rect.height * ratio);

                    if (canvas.width === targetW && canvas.height === targetH) return;

                    const data = this.pad.toData();
                    canvas.width = targetW;
                    canvas.height = targetH;
                    const ctx = canvas.getContext('2d');
                    ctx.scale(ratio, ratio);

                    this.pad.clear();
                    if (data && data.length > 0) {
                        this.pad.fromData(data);
                    }
                };

                requestAnimationFrame(() => resizeCanvas());

                window.syncProcedureConsent = () => {
                    if (this.pad && !this.pad.isEmpty()) {
                        const data = this.pad.toDataURL('image/png');
                        @this.set('patientConsentSignature', data, false);
                    }
                    @this.call('saveInformedConsent');
                };

                this.pad.addEventListener('endStroke', () => {
                    this.isEmpty = this.pad.isEmpty();
                });
            });
        },
        clearConsentSig() {
            if (this.pad) {
                this.pad.clear();
                this.isEmpty = true;
                @this.set('patientConsentSignature', '', false);
            }
        }
    };
}
</script>
