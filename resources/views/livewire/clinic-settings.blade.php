<div class="w-full space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-1.5">
                <span>Administrasi Sistem</span>
                <span>/</span>
                <span class="text-slate-900">Portal Super Admin</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Pengaturan Klinik & Master Data</h1>
            <p class="text-sm text-slate-500 mt-1">Konfigurasi identitas klinik, kelola tenaga medis (dokter & perawat), serta katalog bahan habis pakai (BHP).</p>
        </div>

        <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-4 py-2 rounded-full border border-slate-200/80 bg-white hover:bg-slate-50 shadow-2xs self-start transition-all active:scale-[0.98]">
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Apple Segmented Tab Switcher -->
    <div class="flex items-center p-1 rounded-2xl bg-slate-100/90 border border-slate-200/60 max-w-xl text-xs font-semibold">
        <button type="button"
                wire:click="setTab('general')"
                class="flex-1 py-2.5 rounded-xl transition-all duration-200 {{ $activeTab === 'general' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
            1. Identitas & White-Label
        </button>
        <button type="button"
                wire:click="setTab('staff')"
                class="flex-1 py-2.5 rounded-xl transition-all duration-200 {{ $activeTab === 'staff' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
            2. Dokter & Perawat ({{ $doctors->count() + $nurses->count() }})
        </button>
        <button type="button"
                wire:click="setTab('consumables')"
                class="flex-1 py-2.5 rounded-xl transition-all duration-200 {{ $activeTab === 'consumables' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
            3. Bahan Baku / BHP ({{ $consumables->count() }})
        </button>
    </div>

    @if (session()->has('success'))
        <div class="rounded-2xl bg-emerald-50 border border-emerald-200/90 p-4 text-emerald-950 text-xs shadow-apple flex items-center justify-between" x-data="{ show: true }" x-show="show">
            <div class="flex items-center gap-3">
                <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-slate-400 hover:text-slate-700 p-1 rounded-md">&times;</button>
        </div>
    @endif

    <!-- TAB 1: IDENTITAS & WHITE-LABEL -->
    @if($activeTab === 'general')
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-9 shadow-apple">
            <form wire:submit="saveSettings" class="space-y-6">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">
                        Informasi Utama Klinik
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Pengaturan identitas klinik, nama brand, nomor telepon, dan logo resmi.
                    </p>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Nama Klinik -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                                Nama Klinik
                            </label>
                            <input wire:model="name"
                                   type="text"
                                   class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                            @error('name') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kode Klinik -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                                Kode Unik Klinik (4 Karakter)
                            </label>
                            <input wire:model="clinic_code"
                                   type="text"
                                   maxlength="4"
                                   class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm font-mono uppercase focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                            @error('clinic_code') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <!-- Warna Primer -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                                Warna Primer (Hex Code)
                            </label>
                            <div class="flex items-center gap-3">
                                <input wire:model="primary_color"
                                       type="color"
                                       class="h-11 w-11 rounded-2xl border border-slate-200 cursor-pointer p-0.5 bg-white">
                                <input wire:model="primary_color"
                                       type="text"
                                       class="flex-1 px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm font-mono focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                            </div>
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                                Nomor Telepon / WhatsApp
                            </label>
                            <input wire:model="phone"
                                   type="text"
                                   class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        </div>

                        <!-- Logo -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                                Unggah Logo Kustom (PNG / JPG / SVG)
                            </label>
                            <input wire:model="logo"
                                   type="file"
                                   accept="image/*"
                                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200 cursor-pointer">
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-slate-500 tracking-wider mb-2">
                                Alamat Resmi Klinik (Dicetak pada Kop Rekam Medis)
                            </label>
                            <input wire:model="address"
                                   type="text"
                                   class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                        </div>
                    </div>
                </div>

                <!-- General Consent Text Template -->
                <div class="pt-6 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-base font-semibold text-slate-900">
                            Template Dokumen Persetujuan Umum Pasien (General Consent)
                        </h2>
                        <span class="text-xs text-slate-400">8 Poin Resmi ONEW Clinic</span>
                    </div>

                    <textarea wire:model="general_consent_text"
                              rows="8"
                              class="w-full text-xs leading-relaxed px-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all"></textarea>
                </div>

                <!-- Submit Button Bar -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit"
                            class="h-12 px-8 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-xs tracking-wide transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Simpan Konfigurasi Klinik</span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- TAB 2: KELOLA DOKTER & PERAWAT -->
    @if($activeTab === 'staff')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah Dokter / Perawat (Left Column) -->
            <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-apple space-y-5">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">
                        Tambah Tenaga Medis Baru
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Daftarkan akun dokter atau perawat jaga baru ke dalam sistem klinik.
                    </p>
                </div>

                <form wire:submit="addStaff" class="space-y-4">
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-600 tracking-wider mb-1.5">
                            Peran / Posisi Tenaga Medis <span class="text-rose-500">*</span>
                        </label>
                        <select wire:model="staff_role"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none">
                            <option value="doctor">Dokter Pemeriksa</option>
                            <option value="nurse">Perawat Jaga</option>
                            <option value="receptionist">Resepsionis</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-600 tracking-wider mb-1.5">
                            Nama Lengkap & Gelar <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="staff_name"
                               placeholder="Contoh: dr. Kevin Pratama, Sp.D.V.E"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none">
                        @error('staff_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-600 tracking-wider mb-1.5">
                            Alamat Email Login <span class="text-rose-500">*</span>
                        </label>
                        <input type="email"
                               wire:model="staff_email"
                               placeholder="dokter@onewclinic.com"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none">
                        @error('staff_email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-600 tracking-wider mb-1.5">
                            Kata Sandi Akun <span class="text-rose-500">*</span>
                        </label>
                        <input type="password"
                               wire:model="staff_password"
                               placeholder="Minimal 6 karakter"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none">
                        @error('staff_password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit"
                            class="w-full h-11 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-xs tracking-wide transition-all mt-2 active:scale-[0.98]">
                        + Simpan Tenaga Medis Baru
                    </button>
                </form>
            </div>

            <!-- List Dokter & Perawat Aktif (Right Column) -->
            <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-apple space-y-6">
                <!-- Daftar Dokter -->
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-bold text-slate-900 text-sm">Daftar Dokter Klinik</h3>
                        <span class="text-xs font-mono font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">{{ $doctors->count() }} Dokter</span>
                    </div>

                    <div class="divide-y divide-slate-100 mt-2">
                        @foreach($doctors as $doc)
                            <div class="py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                                        DR
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-xs">{{ $doc->name }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono">{{ $doc->email }}</p>
                                    </div>
                                </div>
                                @if($doc->id !== auth()->id())
                                    <button type="button"
                                            wire:click="deleteStaff({{ $doc->id }})"
                                            wire:confirm="Yakin ingin menghapus dokter ini dari klinik?"
                                            class="text-[11px] text-rose-600 hover:text-rose-800 font-semibold px-3 py-1 rounded-full hover:bg-rose-50 transition-colors">
                                        Hapus
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Daftar Perawat -->
                <div class="pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-bold text-slate-900 text-sm">Daftar Perawat Jaga</h3>
                        <span class="text-xs font-mono font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-700">{{ $nurses->count() }} Perawat</span>
                    </div>

                    <div class="divide-y divide-slate-100 mt-2">
                        @foreach($nurses as $nurse)
                            <div class="py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-800 flex items-center justify-center font-bold text-xs border border-slate-200">
                                        NS
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-xs">{{ $nurse->name }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono">{{ $nurse->email }}</p>
                                    </div>
                                </div>
                                @if($nurse->id !== auth()->id())
                                    <button type="button"
                                            wire:click="deleteStaff({{ $nurse->id }})"
                                            wire:confirm="Yakin ingin menghapus perawat ini?"
                                            class="text-[11px] text-rose-600 hover:text-rose-800 font-semibold px-3 py-1 rounded-full hover:bg-rose-50 transition-colors">
                                        Hapus
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 3: KATALOG BAHAN BAKU / BHP (BARANG HABIS PAKAI) -->
    @if($activeTab === 'consumables')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah Bahan Baku Baru (Left Column) -->
            <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-apple space-y-5">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">
                        Tambah Bahan Baku (BHP)
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Masukkan nama barang medis habis pakai ke dalam katalog klinik (Form BHP Tindakan Harian).
                    </p>
                </div>

                <form wire:submit="addConsumable" class="space-y-4">
                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-600 tracking-wider mb-1.5">
                            Nama Bahan Medis / BHP <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="consumable_name"
                               placeholder="Contoh: SPUIT 1CC, CREAM ANESTESI, NEEDLE 30G"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs uppercase font-semibold text-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none">
                        @error('consumable_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-slate-600 tracking-wider mb-1.5">
                                Kategori
                            </label>
                            <select wire:model="consumable_category"
                                    class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none">
                                <option value="Disposable">Disposable</option>
                                <option value="Topikal">Topikal</option>
                                <option value="Jarum & Spuit">Jarum & Spuit</option>
                                <option value="Obat & Salep">Obat & Salep</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold uppercase text-slate-600 tracking-wider mb-1.5">
                                Satuan
                            </label>
                            <input type="text"
                                   wire:model="consumable_unit"
                                   placeholder="pcs / cc / gram"
                                   class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-mono text-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold uppercase text-slate-600 tracking-wider mb-1.5">
                            Kuantitas Default Standar (QT)
                        </label>
                        <input type="number"
                               wire:model="consumable_default_qty"
                               min="1"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-mono text-slate-900 focus:ring-2 focus:ring-slate-900 focus:outline-none">
                    </div>

                    <button type="submit"
                            class="w-full h-11 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-xs tracking-wide transition-all mt-2 active:scale-[0.98]">
                        + Tambahkan ke Katalog BHP
                    </button>
                </form>
            </div>

            <!-- List Katalog Bahan Baku Aktif (Right Column) -->
            <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-7 shadow-apple space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Katalog BHP Tindakan Harian ONEW</h3>
                        <p class="text-[11px] text-slate-400">Total {{ $consumables->count() }} item bahan medis aktif</p>
                    </div>
                </div>

                <div class="divide-y divide-slate-100 max-h-[560px] overflow-y-auto pr-1">
                    @foreach($consumables as $item)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-900 text-xs block">{{ $item->name }}</span>
                                <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-400">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-mono text-[10px]">{{ $item->category }}</span>
                                    <span>&bull;</span>
                                    <span>Satuan: {{ $item->unit }}</span>
                                    <span>&bull;</span>
                                    <span>Default QT: {{ $item->default_quantity }}</span>
                                </div>
                            </div>

                            <button type="button"
                                    wire:click="deleteConsumable({{ $item->id }})"
                                    wire:confirm="Hapus {{ $item->name }} dari katalog BHP?"
                                    class="text-[11px] text-rose-600 hover:text-rose-800 font-semibold px-3 py-1 rounded-full hover:bg-rose-50 transition-colors">
                                Hapus
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
