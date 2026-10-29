<div>
    <!-- Apple Bento Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-8 border-b border-slate-200/60 gap-4">
        <div>
            <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-1.5">
                <span>Alur Pelayanan</span>
                <span>/</span>
                <span class="text-slate-900">1. Kunjungan Hari Ini</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Kunjungan & Rekam Medis Pasien</h1>
            <p class="text-sm text-slate-500 mt-1">
                Alur pelayanan klinik: Registrasi & Consent, Pemeriksaan Medis, dan Rekap Rekam Medis Selesai.
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

    <!-- Apple-Style Bento Grid Metric Tiles (Clean 3-Card Architecture) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-8">
        <!-- Tile 1: Total Kunjungan Hari Ini -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-apple flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">Kunjungan Hari Ini</span>
                <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-medium">Hari ini</span>
            </div>
            <div class="mt-4">
                <span class="text-4xl font-bold text-slate-900 tracking-tight">{{ $counts['total_today'] }}</span>
                <p class="text-xs text-slate-400 mt-1">Total kunjungan terdaftar hari ini</p>
            </div>
        </div>

        <!-- Tile 2: Menunggu Pemeriksaan Dokter -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-apple flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase text-slate-500 tracking-wider">Menunggu Pemeriksaan</span>
                <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-medium">Antrian</span>
            </div>
            <div class="mt-4">
                <span class="text-4xl font-bold text-slate-900 tracking-tight">{{ $counts['waiting'] }}</span>
                <p class="text-xs text-slate-400 mt-1">Pasien siap di ruang pemeriksaan</p>
            </div>
        </div>

        <!-- Tile 3: Kunjungan Selesai -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-apple flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase text-slate-500 tracking-wider">Kunjungan Selesai</span>
                <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-medium">Selesai</span>
            </div>
            <div class="mt-4">
                <span class="text-4xl font-bold text-slate-900 tracking-tight">{{ $counts['completed'] }}</span>
                <p class="text-xs text-slate-400 mt-1">Tindakan selesai & rekap rekam medis siap</p>
            </div>
        </div>
    </div>

    <!-- Apple Segmented Filter Bar -->
    <div class="bg-white p-3 sm:p-4 rounded-3xl border border-slate-200/70 shadow-apple mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Segmented Control Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 text-xs font-medium">
                <button wire:click="$set('statusFilter', 'all')"
                        class="px-5 py-2.5 rounded-full whitespace-nowrap transition-all duration-200 {{ $statusFilter === 'all' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Semua Kunjungan ({{ $visits->total() }})
                </button>
                <button wire:click="$set('statusFilter', 'waiting')"
                        class="px-5 py-2.5 rounded-full whitespace-nowrap transition-all duration-200 {{ $statusFilter === 'waiting' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Menunggu Pemeriksaan ({{ $counts['waiting'] }})
                </button>
                <button wire:click="$set('statusFilter', 'completed')"
                        class="px-5 py-2.5 rounded-full whitespace-nowrap transition-all duration-200 {{ $statusFilter === 'completed' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Selesai ({{ $counts['completed'] }})
                </button>
            </div>

            <!-- Apple-Style Rounded Search Input -->
            <div class="relative w-full md:w-80">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Cari pasien, kode RM, NIK, atau telp..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/70 border border-slate-200/80 rounded-full text-xs text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all">
            </div>
        </div>
    </div>

    <!-- Apple-Style Clean Data Table (No EHR clutter) -->
    <div class="bg-white rounded-3xl border border-slate-200/70 shadow-apple overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-200/60">
                    <tr>
                        <th class="py-4 px-6">Identitas Pasien</th>
                        <th class="py-4 px-5">Waktu Kunjungan</th>
                        <th class="py-4 px-5">Status Alur</th>
                        <th class="py-4 px-5">Dokter & Perawat</th>
                        <th class="py-4 px-5">Dokumentasi Foto</th>
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
                                    @if($visit->patient?->nik)
                                        <span class="text-[11px] text-slate-400 font-mono hidden lg:inline">&bull; NIK: {{ $visit->patient->nik }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Waktu Kunjungan -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                <div class="text-slate-800 font-medium text-xs">{{ $visit->created_at->translatedFormat('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">{{ $visit->created_at->format('H:i') }} WIB</div>
                            </td>

                            <!-- Status Alur Pelayanan -->
                            <td class="py-4 px-5 whitespace-nowrap">
                                @if(in_array($visit->status, ['registered', 'consent_signed', 'waiting_doctor']))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Menunggu Pemeriksaan Dokter
                                    </span>
                                @elseif($visit->status === 'in_treatment')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-800 border border-purple-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                                        Sedang Dalam Pemeriksaan
                                    </span>
                                @elseif($visit->status === 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Kunjungan Selesai
                                    </span>
                                @endif
                            </td>

                            <!-- Dokter & Tim Medis -->
                            <td class="py-4 px-5">
                                <div class="text-xs">
                                    <div class="font-medium text-slate-800">{{ $visit->doctor?->name ?? 'dr. Alana Wijaya' }}</div>
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

                            <!-- Action Button -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    @if($visit->status !== 'completed')
                                        <a href="{{ route('doctor.treatment', ['visit_id' => $visit->id]) }}"
                                           class="h-9 px-4 rounded-full text-xs font-semibold text-white bg-slate-900 hover:bg-black transition-all inline-flex items-center gap-1.5 active:scale-[0.97]">
                                            <span>Masuk Ruang Pemeriksaan</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    @else
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
                            <td colspan="6" class="py-16 text-center text-slate-400">
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
