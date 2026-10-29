<div class="w-full max-w-md">
    <!-- Login Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-10 shadow-apple-elevated">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <img src="{{ asset('logo.svg') }}" alt="ONEW Clinic" class="h-14 w-auto object-contain mx-auto mb-4">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Masuk ke Sistem</h1>
            <p class="text-xs text-slate-500 mt-1">Sistem Rekam Medis & Alur Pelayanan Pasien</p>
        </div>

        <!-- Form -->
        <form wire:submit="login" class="space-y-5">
            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Alamat Email
                </label>
                <input wire:model="email"
                       type="email"
                       id="email"
                       required
                       autofocus
                       placeholder="nama@onewclinic.com"
                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                @error('email') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Kata Sandi
                </label>
                <input wire:model="password"
                       type="password"
                       id="password"
                       required
                       placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 focus:bg-white text-slate-900 text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none transition-all">
                @error('password') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none text-slate-600">
                    <input wire:model="remember"
                           type="checkbox"
                           class="rounded-md border-slate-300 text-slate-900 focus:ring-slate-900 w-4 h-4">
                    <span>Ingat saya di perangkat ini</span>
                </label>
            </div>

            <!-- Submit Button (Apple Pill) -->
            <div class="pt-3">
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="w-full h-12 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-xs tracking-wide transition-all duration-200 hover:scale-[1.01] active:scale-[0.98] flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="login">Masuk ke Dashboard</span>
                    <span wire:loading wire:target="login" class="flex items-center gap-2">
                        <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span>Memproses...</span>
                    </span>
                </button>
            </div>
        </form>

        <!-- Quick Role Demo Selector (Untuk kemudahan pengujian staf) -->
        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block mb-3">
                Pilih Akun Demo (1-Klik Isi Kredensial)
            </span>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <button type="button"
                        wire:click="quickFill('superadmin')"
                        class="px-3 py-2 rounded-xl border border-slate-200 hover:border-slate-900 bg-slate-50 hover:bg-white text-slate-700 hover:text-slate-900 font-medium transition-all text-left flex flex-col">
                    <span class="font-semibold text-slate-900 text-[11px]">Super Admin</span>
                    <span class="text-[10px] text-slate-400 truncate">admin@onewclinic.com</span>
                </button>

                <button type="button"
                        wire:click="quickFill('doctor')"
                        class="px-3 py-2 rounded-xl border border-slate-200 hover:border-slate-900 bg-slate-50 hover:bg-white text-slate-700 hover:text-slate-900 font-medium transition-all text-left flex flex-col">
                    <span class="font-semibold text-slate-900 text-[11px]">Dokter</span>
                    <span class="text-[10px] text-slate-400 truncate">doctor@onewclinic.com</span>
                </button>

                <button type="button"
                        wire:click="quickFill('receptionist')"
                        class="px-3 py-2 rounded-xl border border-slate-200 hover:border-slate-900 bg-slate-50 hover:bg-white text-slate-700 hover:text-slate-900 font-medium transition-all text-left flex flex-col">
                    <span class="font-semibold text-slate-900 text-[11px]">Resepsionis</span>
                    <span class="text-[10px] text-slate-400 truncate">receptionist@...</span>
                </button>

                <button type="button"
                        wire:click="quickFill('nurse')"
                        class="px-3 py-2 rounded-xl border border-slate-200 hover:border-slate-900 bg-slate-50 hover:bg-white text-slate-700 hover:text-slate-900 font-medium transition-all text-left flex flex-col">
                    <span class="font-semibold text-slate-900 text-[11px]">Perawat</span>
                    <span class="text-[10px] text-slate-400 truncate">nurse@onewclinic.com</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Footer Copyright -->
    <div class="mt-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} ONEW Clinic &bull; Rekam Medis Elektronik Terintegrasi
    </div>
</div>
