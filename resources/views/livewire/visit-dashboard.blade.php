<div>
    <!-- Apple Bento Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-8 border-b border-slate-200/60 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Alur Rekam Medis & Kunjungan</h1>
            <p class="text-sm text-slate-500 mt-1">
                Pemantauan alur pasien dari registrasi, persetujuan tablet, pemeriksaan perawat, hingga tindakan klinis dokter.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('patients.register') }}"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-xs tracking-wide transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Registrasi Pasien Baru</span>
            </a>
        </div>
    </div>

    <!-- Apple-Style Bento Grid Metric Tiles -->
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-3.5 sm:gap-4 my-8">
        <!-- Tile 1: Hari Ini -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/70 shadow-apple flex flex-col justify-between">
            <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">Kunjungan Hari Ini</span>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-bold text-slate-900 tracking-tight">{{ $counts['total_today'] }}</span>
                <span class="text-[11px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-medium">Hari ini</span>
            </div>
        </div>

        <!-- Tile 2: Perlu Consent -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/70 shadow-apple flex flex-col justify-between">
            <span class="text-[11px] font-semibold uppercase text-amber-600/90 tracking-wider">Menunggu Tanda Tangan</span>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-bold text-slate-900 tracking-tight">{{ $counts['registered'] }}</span>
                <span class="text-[11px] px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-medium border border-amber-200/60">Tablet</span>
            </div>
        </div>

        <!-- Tile 3: Siap Perawat -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/70 shadow-apple flex flex-col justify-between">
            <span class="text-[11px] font-semibold uppercase text-blue-600/90 tracking-wider">Pemeriksaan Perawat</span>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-bold text-slate-900 tracking-tight">{{ $counts['consent_signed'] }}</span>
                <span class="text-[11px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-medium border border-blue-200/60">Foto Awal</span>
            </div>
        </div>

        <!-- Tile 4: Ruang Dokter -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/70 shadow-apple flex flex-col justify-between">
            <span class="text-[11px] font-semibold uppercase text-indigo-600/90 tracking-wider">Ruang Dokter</span>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-bold text-slate-900 tracking-tight">{{ $counts['waiting_doctor'] }}</span>
                <span class="text-[11px] px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-medium border border-indigo-200/60">Tindakan</span>
            </div>
        </div>

        <!-- Tile 5: Selesai -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/70 shadow-apple flex flex-col justify-between">
            <span class="text-[11px] font-semibold uppercase text-emerald-600/90 tracking-wider">Kunjungan Selesai</span>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-bold text-slate-900 tracking-tight">{{ $counts['completed'] }}</span>
                <span class="text-[11px] px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-medium border border-emerald-200/60">Selesai</span>
            </div>
        </div>

        <!-- Tile 6: Trustmedis -->
        <div class="bg-white p-5 rounded-3xl border border-slate-200/70 shadow-apple flex flex-col justify-between">
            <span class="text-[11px] font-semibold uppercase text-sky-600/90 tracking-wider">Sinkronisasi EHR</span>
            <div class="mt-4 flex items-baseline justify-between">
                <span class="text-3xl font-bold text-slate-900 tracking-tight">{{ $counts['synced'] }}</span>
                <span class="text-[11px] px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 font-medium border border-sky-200/60">Tersinkron</span>
            </div>
        </div>
    </div>

    <!-- Apple Segmented Filter Bar -->
    <div class="bg-white p-3 sm:p-4 rounded-3xl border border-slate-200/70 shadow-apple mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Segmented Control Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 text-xs font-medium">
                <button wire:click="$set('statusFilter', 'all')"
                        class="px-4 py-2 rounded-full whitespace-nowrap transition-all duration-200 {{ $statusFilter === 'all' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Semua Kunjungan ({{ $visits->total() }})
                </button>
                <button wire:click="$set('statusFilter', 'registered')"
                        class="px-4 py-2 rounded-full whitespace-nowrap transition-all duration-200 {{ $statusFilter === 'registered' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Menunggu Tanda Tangan
                </button>
                <button wire:click="$set('statusFilter', 'consent_signed')"
                        class="px-4 py-2 rounded-full whitespace-nowrap transition-all duration-200 {{ $statusFilter === 'consent_signed' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Pemeriksaan Awal
                </button>
                <button wire:click="$set('statusFilter', 'waiting_doctor')"
                        class="px-4 py-2 rounded-full whitespace-nowrap transition-all duration-200 {{ $statusFilter === 'waiting_doctor' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Ruang Tindakan Dokter
                </button>
                <button wire:click="$set('statusFilter', 'completed')"
                        class="px-4 py-2 rounded-full whitespace-nowrap transition-all duration-200 {{ $statusFilter === 'completed' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Selesai
                </button>
            </div>

            <!-- Apple-Style Rounded Search Input -->
            <div class="relative w-full md:w-80">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Cari pasien, kode RM, atau no telepon..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/70 border border-slate-200/80 rounded-full text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all">
            </div>
        </div>
    </div>

    <!-- Apple-Style Clean Data Table (Tablet Touch Friendly) -->
    <div class="bg-white rounded-3xl border border-slate-200/70 shadow-apple overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-200/60">
                    <tr>
                        <th class="py-4 px-6">Identitas Pasien</th>
                        <th class="py-4 px-5">Waktu Kunjungan</th>
                        <th class="py-4 px-5">Status Alur</th>
                        <th class="py-4 px-5">Tenaga Medis</th>
                        <th class="py-4 px-5">Dokumentasi Foto</th>
                        <th class="py-4 px-5">Sinkronisasi EHR</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($visits as $visit)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Identitas Pasien -->
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-900 text-sm tracking-tight">{{ $visit->patient?->name ?? 'Pasien' }}</div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="font-mono text-xs px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-medium">
                                        {{ $visit->patient?->patient_code }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $visit->patient?->phone_number }}</span>
                                </div>
                            </td>

                            <!-- Waktu Kunjungan -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                <div class="text-slate-800 font-medium text-xs">{{ $visit->created_at->translatedFormat('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">{{ $visit->created_at->format('H:i') }} WIB</div>
                            </td>

                            <!-- Status Alur Pelayanan -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                @if($visit->status === 'registered')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Menunggu Tanda Tangan
                                    </span>
                                @elseif($visit->status === 'consent_signed')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-800 border border-blue-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Pemeriksaan Perawat
                                    </span>
                                @elseif($visit->status === 'waiting_doctor')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-800 border border-indigo-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        Ruang Tindakan Dokter
                                    </span>
                                @elseif($visit->status === 'in_treatment')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-800 border border-purple-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                                        Sedang Ditangani Dokter
                                    </span>
                                @elseif($visit->status === 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Selesai
                                    </span>
                                @endif
                            </td>

                            <!-- Tenaga Medis -->
                            <td class="py-4 px-5">
                                <div class="text-xs">
                                    <div class="font-medium text-slate-800">{{ $visit->doctor?->name ?? 'Belum Ditentukan' }}</div>
                                    @if($visit->assistant_name)
                                        <div class="text-slate-400 mt-0.5">Asisten: {{ $visit->assistant_name }}</div>
                                    @endif
                                </div>
                            </td>

                            <!-- Foto Klinis Status -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                <div class="flex items-center gap-1.5 text-xs">
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-medium {{ $visit->photo_before_path ? 'bg-slate-100 text-slate-800 border border-slate-200/80' : 'bg-slate-50 text-slate-400' }}">
                                        Foto Awal {{ $visit->photo_before_path ? '✓' : '—' }}
                                    </span>
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-medium {{ $visit->photo_after_path ? 'bg-slate-100 text-slate-800 border border-slate-200/80' : 'bg-slate-50 text-slate-400' }}">
                                        Foto Akhir {{ $visit->photo_after_path ? '✓' : '—' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Trustmedis Sync Status -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                @if($visit->trustmedis_sync_status === 'synced')
                                    <div class="flex items-center gap-1.5 text-xs text-emerald-800 font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Tersinkronisasi</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $visit->trustmedis_synced_at?->format('H:i d/m/y') }}</div>
                                @elseif($visit->trustmedis_sync_status === 'failed')
                                    <div class="flex items-center gap-1.5 text-xs text-rose-800 font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span>Gagal</span>
                                    </div>
                                    <button wire:click="triggerSync({{ $visit->id }})" class="text-[11px] text-slate-600 hover:text-slate-900 underline mt-0.5">Sync Ulang</button>
                                @else
                                    <span class="text-xs px-2.5 py-1 rounded-md bg-slate-50 text-slate-400 font-medium">Pending</span>
                                @endif
                            </td>

                            <!-- Tablet-Friendly Action Button (Min 40px Tap Target) -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    @if($visit->status === 'registered')
                                        <a href="{{ route('kiosk.show', $visit) }}"
                                           target="_blank"
                                           class="h-9 px-4 rounded-full text-xs font-semibold text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 transition-all inline-flex items-center gap-1.5 active:scale-[0.97]">
                                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            <span>Mode Tablet Pasien</span>
                                        </a>
                                    @elseif($visit->status === 'consent_signed')
                                        <a href="{{ route('nurse.examination', ['visit_id' => $visit->id]) }}"
                                           class="h-9 px-4 rounded-full text-xs font-semibold text-white bg-slate-900 hover:bg-black transition-all inline-flex items-center gap-1.5 active:scale-[0.97]">
                                            <span>Pemeriksaan Perawat</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    @elseif(in_array($visit->status, ['waiting_doctor', 'in_treatment']))
                                        <a href="{{ route('doctor.treatment', ['visit_id' => $visit->id]) }}"
                                           class="h-9 px-4 rounded-full text-xs font-semibold text-white bg-slate-900 hover:bg-black transition-all inline-flex items-center gap-1.5 active:scale-[0.97]">
                                            <span>Ruang Tindakan</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    @elseif($visit->status === 'completed')
                                        <a href="{{ route('reports.visit.pdf', $visit) }}"
                                           target="_blank"
                                           class="h-9 px-4 rounded-full text-xs font-semibold text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 transition-all inline-flex items-center gap-1.5 active:scale-[0.97]">
                                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span>Cetak Ringkasan Medis</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-slate-400">
                                <div class="max-w-sm mx-auto">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <p class="font-semibold text-slate-800 text-sm">Belum Ada Kunjungan Pasien</p>
                                    <p class="text-xs text-slate-400 mt-1">Daftarkan pasien melalui menu Registrasi untuk memulai alur rekam medis.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visits->hasPages())
            <div class="p-4 border-t border-slate-200/60">
                {{ $visits->links() }}
            </div>
        @endif
    </div>
</div>
