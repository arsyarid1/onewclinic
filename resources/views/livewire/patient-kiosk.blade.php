<div class="w-full">
    @if(!$isCompleted)
        <!-- Kiosk Active Sign Form -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <!-- Patient Identity Summary Bar -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-clinic-primary text-white flex items-center justify-center font-bold text-lg shadow-xs">
                        {{ strtoupper(substr($patient->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <span class="text-[11px] font-semibold uppercase text-slate-400 tracking-wider">Identitas Pasien</span>
                        <h2 class="text-lg font-bold text-slate-900 leading-tight">{{ $patient->name }}</h2>
                        <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-600">
                            <span class="font-mono font-semibold bg-white px-2 py-0.5 rounded border border-slate-200 text-slate-800">{{ $patient->patient_code }}</span>
                            <span>&bull;</span>
                            <span>{{ $patient->gender === 'female' ? 'Perempuan' : 'Laki-laki' }}</span>
                            <span>&bull;</span>
                            <span>{{ $patient->date_of_birth ? $patient->date_of_birth->age . ' Tahun' : '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-left sm:text-right text-xs text-slate-500">
                    <span class="font-medium text-slate-700">Tanggal Kunjungan:</span><br>
                    <span>{{ date('d F Y') }}</span>
                </div>
            </div>

            <!-- General Consent Agreement Document -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800">
                        Dokumen Persetujuan Umum (General Consent)
                    </h3>
                    <span class="text-xs text-slate-400">Silakan baca teks persetujuan di bawah ini</span>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200 max-h-56 overflow-y-auto text-xs leading-relaxed text-slate-700 whitespace-pre-line font-normal scrollbar-thin">
{{ $clinic->general_consent_text ?? 'Dengan ini saya menyatakan persetujuan atas tindakan medis, perawatan estetika, serta dokumentasi rekam medis klinis di klinik ini.' }}
                </div>
            </div>

            <!-- Agreement Checkbox -->
            <div class="mb-6">
                <label class="flex items-start gap-3 p-3.5 rounded-2xl border border-sky-200 bg-sky-50/40 cursor-pointer select-none">
                    <input type="checkbox" wire:model="agreementChecked" class="mt-0.5 rounded text-sky-600 focus:ring-sky-500 w-4 h-4">
                    <span class="text-xs text-sky-950 font-medium">
                        Saya telah membaca, memahami, dan menyetujui seluruh ketentuan dalam Dokumen Persetujuan Umum (General Consent) di atas tanpa paksaan dari pihak manapun.
                    </span>
                </label>
                @error('agreement') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
            </div>

            <!-- Signature Pad Canvas (Alpine.js + SignaturePad) -->
            <div x-data="signatureComponent()" x-init="initSignature()" class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <span>Bubuhkan Tanda Tangan Digital Pasien</span>
                        <span class="text-rose-500">*</span>
                    </label>
                    <button type="button"
                            @click="clearSignature()"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-700 px-3 py-1.5 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 transition-colors">
                        Bersihkan (Clear)
                    </button>
                </div>

                <!-- Canvas Box -->
                <div class="relative w-full border-2 border-dashed border-slate-300 rounded-2xl bg-white overflow-hidden shadow-inner flex items-center justify-center p-2"
                     style="touch-action: none;">
                    <canvas x-ref="canvas"
                            class="w-full bg-white rounded-xl cursor-crosshair"
                            style="height: 220px;"></canvas>

                    <div x-show="isEmpty"
                         class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center text-slate-300 text-xs gap-1">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        <span>Tanda tangani menggunakan jari atau stylus pen di sini</span>
                    </div>
                </div>

                @error('signature') <span class="text-xs text-rose-500 block font-semibold">{{ $message }}</span> @enderror

                <!-- Action Submit Button -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-400">
                        Tanda tangan ini sah dan disimpan sebagai bukti persetujuan rekam medis elektronik klinik.
                    </p>
                    <button type="button"
                            @click="submitSignature()"
                            :disabled="saving"
                            class="w-full sm:w-auto px-8 py-4 rounded-2xl font-bold text-white bg-clinic-primary hover:bg-clinic-primary-dark shadow-md text-base transition-all flex items-center justify-center gap-2 disabled:opacity-50">
                        <span x-show="!saving">✍️ Saya Setuju & Simpan</span>
                        <span x-show="saving">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>

    @else
        <!-- Kiosk Completed / Thank You Screen -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center max-w-lg mx-auto"
             x-data="{ showPinModal: false }">
            <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            </div>

            <h2 class="text-2xl font-bold text-slate-900">Persetujuan Berhasil Disimpan!</h2>
            <p class="text-sm text-slate-500 mt-2">
                Terima kasih, <strong>{{ $patient->name }}</strong>. General Consent Anda telah diverifikasi dan dicatat ke dalam rekam medis klinik.
            </p>

            <div class="my-6 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-left text-xs space-y-2 text-slate-600">
                <div class="flex justify-between">
                    <span class="text-slate-400">Kode Rekam Medis:</span>
                    <span class="font-mono font-bold text-slate-800">{{ $patient->patient_code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Waktu Persetujuan:</span>
                    <span class="font-medium text-slate-800">{{ $visit->consent_signed_at?->translatedFormat('d M Y, H:i') ?? date('d M Y, H:i') }} WIB</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Status Alur:</span>
                    <span class="font-semibold text-blue-600">Menunggu Pemeriksaan Perawat</span>
                </div>
            </div>

            <p class="text-xs text-slate-400 mb-6">
                Silakan serahkan tablet kembali kepada staf resepsionis atau perawat jaga kami.
            </p>

            <!-- Staff Exit PIN Button -->
            <button type="button"
                    @click="showPinModal = true"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <span>Kembali ke Menu Staf (Proteksi PIN)</span>
            </button>

            <!-- Staff PIN Security Modal -->
            <div x-show="showPinModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
                <div class="bg-white rounded-3xl p-6 max-w-sm w-full text-left shadow-2xl border border-slate-200" @click.away="showPinModal = false">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h4 class="font-bold text-slate-900 text-sm">Autentikasi PIN Staf</h4>
                        <button type="button" @click="showPinModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                    </div>

                    <p class="text-xs text-slate-500 mt-3 mb-4">
                        Masukkan PIN petugas (Default: <code class="font-mono font-bold text-sky-600">1234</code>) untuk keluar dari Kiosk Tablet Pasien:
                    </p>

                    <form wire:submit="verifyStaffPin">
                        <input wire:model="staffPin"
                               type="password"
                               maxlength="8"
                               placeholder="Masukkan 4 digit PIN"
                               class="w-full text-center text-xl tracking-widest font-mono font-bold px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-500 mb-3"
                               autofocus>

                        @if($pinError)
                            <div class="text-xs text-rose-500 font-medium mb-3">{{ $pinError }}</div>
                        @endif

                        <div class="flex items-center justify-end gap-2">
                            <button type="button" @click="showPinModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-black transition-all">
                                Konfirmasi & Keluar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Inline SignaturePad Alpine Helper Script -->
<script>
function signatureComponent() {
    return {
        pad: null,
        isEmpty: true,
        saving: false,
        initSignature() {
            this.$nextTick(() => {
                const canvas = this.$refs.canvas;
                if (!canvas) return;

                // Adjust canvas resolution for high-DPI displays
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                const ctx = canvas.getContext('2d');
                ctx.scale(ratio, ratio);

                if (window.SignaturePad) {
                    this.pad = new window.SignaturePad(canvas, {
                        backgroundColor: 'rgba(255, 255, 255, 0)',
                        penColor: 'rgb(2, 132, 199)', // aesthetic blue
                        minWidth: 1.5,
                        maxWidth: 3.5
                    });

                    this.pad.addEventListener('endStroke', () => {
                        this.isEmpty = this.pad.isEmpty();
                    });
                } else {
                    console.warn('SignaturePad library not loaded on window.');
                }
            });
        },
        clearSignature() {
            if (this.pad) {
                this.pad.clear();
                this.isEmpty = true;
            }
        },
        submitSignature() {
            if (!this.pad || this.pad.isEmpty()) {
                alert('Silakan bubuhkan tanda tangan terlebih dahulu.');
                return;
            }
            this.saving = true;
            const dataUrl = this.pad.toDataURL('image/png');
            @this.call('saveSignature', dataUrl);
        }
    };
}
</script>
