<div class="w-full">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-1.5">
                <span>Konfigurasi Sistem</span>
                <span>/</span>
                <span class="text-slate-900">Pengaturan Klinik</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Pengaturan White-Label Klinik</h1>
            <p class="text-sm text-slate-500 mt-1">Sesuaikan identitas klinik, aksen warna, kop laporan rekam medis, dan dokumen persetujuan.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-4 py-2 rounded-full border border-slate-200/80 bg-white hover:bg-slate-50 shadow-2xs transition-all active:scale-[0.98]">
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Main Settings Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-apple p-6 sm:p-10">
        <form wire:submit="saveSettings" class="space-y-8">
            <!-- Brand Identity -->
            <div>
                <h2 class="text-base font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-5">
                    Identitas Klinik & Palet Warna
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Nama Klinik -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                            Nama Klinik <span class="text-rose-500">*</span>
                        </label>
                        <input wire:model="name"
                               type="text"
                               placeholder="Nama klinik resmi"
                               class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        @error('name') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kode Klinik (4 Karakter) -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                            Kode Klinik (4 Karakter) <span class="text-rose-500">*</span>
                        </label>
                        <input wire:model="clinic_code"
                               type="text"
                               maxlength="4"
                               placeholder="ONEW"
                               class="w-full font-mono uppercase px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        <span class="text-[10px] text-slate-400 mt-1 block">Digunakan untuk format kode RM: [KODE][YY]-[000001]</span>
                        @error('clinic_code') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Primary Color -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                            Warna Utama (Primary Color Hex) <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input wire:model.live="primary_color"
                                   type="color"
                                   class="w-12 h-12 rounded-2xl border border-slate-200 p-1 cursor-pointer bg-white shadow-2xs">
                            <input wire:model.live="primary_color"
                                   type="text"
                                   placeholder="#0284C7"
                                   class="flex-1 font-mono px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        </div>
                        @error('primary_color') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Secondary Color -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                            Warna Sekunder (Secondary Color Hex)
                        </label>
                        <div class="flex items-center gap-3">
                            <input wire:model.live="secondary_color"
                                   type="color"
                                   class="w-12 h-12 rounded-2xl border border-slate-200 p-1 cursor-pointer bg-white shadow-2xs">
                            <input wire:model.live="secondary_color"
                                   type="text"
                                   placeholder="#0EA5E9"
                                   class="flex-1 font-mono px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        </div>
                        @error('secondary_color') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Telepon Klinik -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                            Nomor Telepon Kontak
                        </label>
                        <input wire:model="phone"
                               type="text"
                               placeholder="0812-9988-7766"
                               class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        @error('phone') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Logo -->
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                            Logo Klinik (PNG / JPG / SVG)
                        </label>
                        <input wire:model="logo"
                               type="file"
                               accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200 cursor-pointer">
                        @error('logo') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                            Alamat Resmi Klinik (Dicetak pada Kop Rekam Medis)
                        </label>
                        <input wire:model="address"
                               type="text"
                               placeholder="Alamat lengkap klinik..."
                               class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        @error('address') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- General Consent Text Template -->
            <div class="pt-6 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-semibold text-slate-900">
                        Template Dokumen Persetujuan Umum
                    </h2>
                    <span class="text-xs text-slate-400">Ditampilkan pada tablet kiosk pasien</span>
                </div>

                <textarea wire:model="general_consent_text"
                          rows="8"
                          class="w-full text-xs leading-relaxed px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all"></textarea>
                @error('general_consent_text') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Button Bar -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                <button type="submit"
                        class="h-12 px-7 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-xs tracking-wide transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Simpan Konfigurasi</span>
                </button>
            </div>
        </form>
    </div>
</div>
