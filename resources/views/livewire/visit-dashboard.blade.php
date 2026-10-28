<div>
    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-slate-200 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard Alur Rekam Medis & Kunjungan</h1>
            <p class="text-sm text-slate-500 mt-1">
                Monitoring alur pasien dari Registrasi, Kiosk Tablet Persetujuan, Pemeriksaan Perawat, hingga Tindakan Dokter & Sync Trustmedis.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('patients.register') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-white bg-clinic-primary hover:bg-clinic-primary-dark shadow-sm text-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Registrasi Pasien Baru</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 my-6">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-xs font-medium text-slate-500">Kunjungan Hari Ini</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900">{{ $counts['total_today'] }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold">Today</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-amber-200 bg-amber-50/30 shadow-xs">
            <span class="text-xs font-medium text-amber-700">1. Perlu Consent</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-amber-900">{{ $counts['registered'] }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-semibold">Kiosk</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-blue-200 bg-blue-50/30 shadow-xs">
            <span class="text-xs font-medium text-blue-700">2. Siap Perawat</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-blue-900">{{ $counts['consent_signed'] }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-semibold">Foto Before</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-indigo-200 bg-indigo-50/30 shadow-xs">
            <span class="text-xs font-medium text-indigo-700">3. Ruang Dokter</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-indigo-900">{{ $counts['waiting_doctor'] }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800 font-semibold">Tindakan</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-emerald-200 bg-emerald-50/30 shadow-xs">
            <span class="text-xs font-medium text-emerald-700">4. Kunjungan Selesai</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-emerald-900">{{ $counts['completed'] }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold">Done</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-sky-200 bg-sky-50/30 shadow-xs">
            <span class="text-xs font-medium text-sky-700">Sync Trustmedis</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-sky-900">{{ $counts['synced'] }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-sky-100 text-sky-800 font-semibold">Synced</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Status Tabs -->
            <div class="flex items-center gap-1 overflow-x-auto pb-2 md:pb-0 text-sm">
                <button wire:click="$set('statusFilter', 'all')"
                        class="px-3 py-1.5 rounded-lg font-medium whitespace-nowrap transition-colors {{ $statusFilter === 'all' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    Semua ({{ $visits->total() }})
                </button>
                <button wire:click="$set('statusFilter', 'registered')"
                        class="px-3 py-1.5 rounded-lg font-medium whitespace-nowrap transition-colors {{ $statusFilter === 'registered' ? 'bg-amber-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    1. Belum Tanda Tangan
                </button>
                <button wire:click="$set('statusFilter', 'consent_signed')"
                        class="px-3 py-1.5 rounded-lg font-medium whitespace-nowrap transition-colors {{ $statusFilter === 'consent_signed' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    2. Sudah Consent
                </button>
                <button wire:click="$set('statusFilter', 'waiting_doctor')"
                        class="px-3 py-1.5 rounded-lg font-medium whitespace-nowrap transition-colors {{ $statusFilter === 'waiting_doctor' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    3. Menunggu Dokter
                </button>
                <button wire:click="$set('statusFilter', 'completed')"
                        class="px-3 py-1.5 rounded-lg font-medium whitespace-nowrap transition-colors {{ $statusFilter === 'completed' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    4. Selesai
                </button>
            </div>

            <!-- Search Field -->
            <div class="relative w-full md:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Cari pasien / kode / no hp..."
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all">
            </div>
        </div>
    </div>

    <!-- Visits Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Kode Pasien & Nama</th>
                        <th class="py-3.5 px-4">Waktu Kunjungan</th>
                        <th class="py-3.5 px-4">Status Alur</th>
                        <th class="py-3.5 px-4">Dokter / Tim</th>
                        <th class="py-3.5 px-4">Foto Klinis</th>
                        <th class="py-3.5 px-4">Trustmedis Sync</th>
                        <th class="py-3.5 px-4 text-right">Aksi Alur</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($visits as $visit)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Pasien Info -->
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900">{{ $visit->patient?->name ?? 'Pasien Tidak Diketahui' }}</div>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="font-mono text-xs px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold">
                                        {{ $visit->patient?->patient_code }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $visit->patient?->phone_number }}</span>
                                </div>
                            </td>

                            <!-- Waktu Kunjungan -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="text-slate-800 font-medium">{{ $visit->created_at->translatedFormat('d M Y') }}</div>
                                <div class="text-xs text-slate-400">{{ $visit->created_at->format('H:i') }} WIB</div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($visit->status === 'registered')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        1. Menunggu TTD Pasien
                                    </span>
                                @elseif($visit->status === 'consent_signed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        2. Consent OK - Perlu Perawat
                                    </span>
                                @elseif($visit->status === 'waiting_doctor')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        3. Siap di Ruang Dokter
                                    </span>
                                @elseif($visit->status === 'in_treatment')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                                        3. Sedang Ditangani Dokter
                                    </span>
                                @elseif($visit->status === 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        4. Selesai (Completed)
                                    </span>
                                @endif
                            </td>

                            <!-- Dokter & Tim -->
                            <td class="py-4 px-4">
                                <div class="text-xs">
                                    <div class="font-medium text-slate-800">{{ $visit->doctor?->name ?? 'Belum Ditentukan' }}</div>
                                    @if($visit->assistant_name)
                                        <div class="text-slate-400">Asisten: {{ $visit->assistant_name }}</div>
                                    @endif
                                </div>
                            </td>

                            <!-- Foto Klinis Status -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-1.5 text-xs">
                                    <span class="px-2 py-0.5 rounded {{ $visit->photo_before_path ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold' : 'bg-slate-100 text-slate-400' }}">
                                        Before {{ $visit->photo_before_path ? '✓' : '-' }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded {{ $visit->photo_after_path ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold' : 'bg-slate-100 text-slate-400' }}">
                                        After {{ $visit->photo_after_path ? '✓' : '-' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Trustmedis Sync Status -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($visit->trustmedis_sync_status === 'synced')
                                    <div class="flex items-center gap-1.5 text-xs text-emerald-700 font-semibold">
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        <span>Synced</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400">{{ $visit->trustmedis_synced_at?->format('H:i d/m/y') }}</div>
                                @elseif($visit->trustmedis_sync_status === 'failed')
                                    <div class="flex items-center gap-1.5 text-xs text-rose-700 font-semibold">
                                        <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                        <span>Failed</span>
                                    </div>
                                    <button wire:click="triggerSync({{ $visit->id }})" class="text-[11px] text-sky-600 hover:underline">Sync Ulang</button>
                                @else
                                    <span class="text-xs px-2 py-0.5 rounded bg-slate-100 text-slate-500 font-medium">Pending</span>
                                @endif
                            </td>

                            <!-- Aksi Alur Terstruktur -->
                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    @if($visit->status === 'registered')
                                        <a href="{{ route('kiosk.show', $visit) }}"
                                           target="_blank"
                                           class="px-3 py-1.5 rounded-lg text-xs font-bold text-amber-800 bg-amber-100 hover:bg-amber-200 transition-colors inline-flex items-center gap-1.5 shadow-xs">
                                            <span>📱 Kiosk Pasien</span>
                                        </a>
                                    @elseif($visit->status === 'consent_signed')
                                        <a href="{{ route('nurse.examination', ['visit_id' => $visit->id]) }}"
                                           class="px-3 py-1.5 rounded-lg text-xs font-bold text-blue-800 bg-blue-100 hover:bg-blue-200 transition-colors inline-flex items-center gap-1.5 shadow-xs">
                                            <span>🩺 Periksa Perawat</span>
                                        </a>
                                    @elseif(in_array($visit->status, ['waiting_doctor', 'in_treatment']))
                                        <a href="{{ route('doctor.treatment', ['visit_id' => $visit->id]) }}"
                                           class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-clinic-primary hover:bg-clinic-primary-dark transition-colors inline-flex items-center gap-1.5 shadow-xs">
                                            <span>💉 Ruang Dokter</span>
                                        </a>
                                    @elseif($visit->status === 'completed')
                                        <a href="{{ route('reports.visit.pdf', $visit) }}"
                                           target="_blank"
                                           class="px-3 py-1.5 rounded-lg text-xs font-bold text-emerald-800 bg-emerald-100 hover:bg-emerald-200 transition-colors inline-flex items-center gap-1.5 shadow-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span>Cetak PDF</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="max-w-sm mx-auto">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <p class="font-semibold text-slate-700">Belum ada kunjungan pasien</p>
                                    <p class="text-xs text-slate-400 mt-1">Gunakan tombol Registrasi Pasien Baru untuk memulai alur rekam medis.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visits->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $visits->links() }}
            </div>
        @endif
    </div>
</div>
