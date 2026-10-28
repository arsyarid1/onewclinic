<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                <span>Konfigurasi Sistem</span>
                <span>&rsaquo;</span>
                <span class="text-sky-600">White-Label Branding & CMS</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pengaturan White-Label Klinik</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kustomisasi identitas klinik, warna brand tema, kop surat laporan PDF, dan template General Consent.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">
            &larr; Dashboard
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs p-6 sm:p-8">
        <form wire:submit="saveSettings" class="space-y-6">
            <!-- Basic Brand Identity -->
            <div>
                <h2 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                    Identitas & Tema Warna White-Label
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Nama Klinik -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">
                            Nama Klinik <span class="text-rose-500">*</span>
                        </label>
                        <input wire:model="name"
                               type="text"
                               placeholder="Contoh: Onew Clinic"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all">
                        @error('name') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kode Klinik (4 Karakter) -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">
                            Kode Klinik (4 Karakter) <span class="text-rose-500">*</span>
                        </label>
                        <input wire:model="clinic_code"
                               type="text"
                               maxlength="4"
                               placeholder="ONEW"
                               class="w-full font-mono uppercase px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all">
                        <span class="text-[11px] text-slate-400 mt-1 block">Digunakan untuk format kode RM: [KODE][YY]-[000001]</span>
                        @error('clinic_code') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Primary Color -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">
                            Warna Utama (Primary Color Hex) <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input wire:model.live="primary_color"
                                   type="color"
                                   class="w-12 h-12 rounded-xl border border-slate-300 p-1 cursor-pointer bg-white">
                            <input wire:model.live="primary_color"
                                   type="text"
                                   placeholder="#0284C7"
                                   class="flex-1 font-mono px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all">
                        </div>
                        @error('primary_color') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Secondary Color -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">
                            Warna Sekunder (Secondary Color Hex)
                        </label>
                        <div class="flex items-center gap-3">
                            <input wire:model.live="secondary_color"
                                   type="color"
                                   class="w-12 h-12 rounded-xl border border-slate-300 p-1 cursor-pointer bg-white">
                            <input wire:model.live="secondary_color"
                                   type="text"
                                   placeholder="#0EA5E9"
                                   class="flex-1 font-mono px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all">
                        </div>
                        @error('secondary_color') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Telepon Klinik -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">
                            Nomor Telepon Hotline Klinik
                        </label>
                        <input wire:model="phone"
                               type="text"
                               placeholder="0812-9988-7766"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all">
                        @error('phone') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Unggah Logo -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">
                            Logo Klinik (PNG / JPG / SVG)
                        </label>
                        <input wire:model="logo"
                               type="file"
                               accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 cursor-pointer">
                        @error('logo') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alamat Klinik -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold uppercase text-slate-600 tracking-wider mb-2">
                            Alamat Lengkap Klinik (Dicetak di Kop PDF)
                        </label>
                        <input wire:model="address"
                               type="text"
                               placeholder="Jl. Senopati No. 88, Kebayoran Baru, Jakarta Selatan 12190"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all">
                        @error('address') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- General Consent Text Template -->
            <div class="pt-6 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-bold text-slate-900">
                        Template Teks Persetujuan Umum (General Consent)
                    </h2>
                    <span class="text-xs text-slate-400">Ditampilkan di tablet kiosk pasien</span>
                </div>

                <textarea wire:model="general_consent_text"
                          rows="8"
                          class="w-full font-mono text-xs leading-relaxed px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 focus:ring-2 focus:ring-sky-500 focus:outline-none transition-all"></textarea>
                @error('general_consent_text') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Button Bar -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="submit"
                        class="px-6 py-3.5 rounded-xl font-bold text-white bg-clinic-primary hover:bg-clinic-primary-dark shadow-sm text-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Simpan Perubahan White-Label</span>
                </button>
            </div>
        </form>
    </div>
</div>
