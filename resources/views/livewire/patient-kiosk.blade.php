<div class="w-full">
    @if(!$isCompleted)
        <!-- Apple iPad Patient Kiosk Active View -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-apple p-6 sm:p-10">
            <!-- Patient Identity Capsule -->
            <div class="p-5 rounded-2xl bg-slate-50/80 border border-slate-200/60 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-semibold text-base shadow-2xs">
                        {{ strtoupper(substr($patient->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold uppercase text-slate-400 tracking-wider">Identitas Pasien</span>
                        <h2 class="text-lg font-bold text-slate-900 tracking-tight leading-tight">{{ $patient->name }}</h2>
                        <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                            <span class="font-mono font-medium bg-white px-2 py-0.5 rounded border border-slate-200 text-slate-800">{{ $patient->patient_code }}</span>
                            <span>&bull;</span>
                            <span>{{ $patient->gender === 'female' ? 'Perempuan' : 'Laki-laki' }}</span>
                            <span>&bull;</span>
                            <span>{{ $patient->date_of_birth ? $patient->date_of_birth->age . ' Tahun' : '—' }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-left sm:text-right text-xs text-slate-500">
                    <span class="text-slate-400">Tanggal Pelayanan:</span>
                    <div class="font-medium text-slate-800 mt-0.5">{{ date('d F Y') }}</div>
                </div>
            </div>

            <!-- General Consent Agreement Document -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2.5">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-700">
                        Dokumen Persetujuan Umum (General Consent)
                    </h3>
                    <span class="text-[11px] text-slate-400">Bacalah pernyataan di bawah ini dengan saksama</span>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50/60 border border-slate-200/70 max-h-56 overflow-y-auto text-xs leading-relaxed text-slate-700 whitespace-pre-line font-normal">
{{ $clinic->general_consent_text ?? 'Dengan ini saya menyatakan persetujuan atas tindakan medis, pemeriksaan klinis, serta dokumentasi rekam medis di klinik ini sesuai ketentuan yang berlaku.' }}
                </div>
            </div>

            <!-- Apple-Style Terms Checkbox -->
            <div class="mb-6">
                <label class="flex items-start gap-3.5 p-4 rounded-2xl border border-slate-200 bg-slate-50/40 hover:bg-slate-50/80 transition-colors cursor-pointer select-none">
                    <input type="checkbox" wire:model="agreementChecked" class="mt-0.5 rounded-md text-slate-900 focus:ring-slate-900 w-4 h-4 cursor-pointer">
                    <span class="text-xs text-slate-800 leading-normal font-medium">
                        Saya telah membaca, memahami, dan menyetujui seluruh ketentuan Dokumen Persetujuan Umum di atas secara sadar tanpa paksaan.
                    </span>
                </label>
                @error('agreement') <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Signature Pad Canvas (Optimized for iPad / Stylus) -->
            <div wire:ignore x-data="signatureComponent()" x-init="initSignature()" class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-700">
                        Tanda Tangan Digital Pasien <span class="text-rose-500">*</span>
                    </label>
                    <button type="button"
                            @click="clearSignature()"
                            class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-3.5 py-1.5 rounded-full border border-slate-200/80 bg-slate-50 hover:bg-slate-100 transition-colors active:scale-[0.97]">
                        Bersihkan
                    </button>
                </div>

                <!-- Canvas Surface -->
                <div class="relative w-full border-2 border-slate-200 hover:border-slate-300 rounded-2xl bg-white overflow-hidden shadow-inner transition-colors">
                    <canvas x-ref="canvas"
                            class="w-full bg-white cursor-crosshair block"
                            style="height: 240px; touch-action: none !important;"></canvas>

                    <!-- Apple-style signature baseline indicator -->
                    <div class="absolute bottom-6 left-8 right-8 border-b border-dashed border-slate-200 pointer-events-none flex justify-between items-center text-[10px] text-slate-300 font-mono select-none">
                        <span>Tanda Tangan Pasien</span>
                        <span>✕</span>
                    </div>

                    <div x-show="isEmpty"
                         class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center text-slate-300 text-xs gap-1.5 select-none">
                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        <span>Bubuhkan tanda tangan di sini menggunakan jari atau pena stylus</span>
                    </div>
                </div>

                @error('signature') <span class="text-xs text-rose-500 block font-medium">{{ $message }}</span> @enderror

                <!-- Action Button -->
                <div class="pt-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-[11px] text-slate-400">
                        Tanda tangan ini sah dan disimpan sebagai persetujuan rekam medis elektronik.
                    </p>
                    <button type="button"
                            @click="submitSignature()"
                            :disabled="saving"
                            class="w-full sm:w-auto h-13 px-8 rounded-full font-semibold text-white bg-slate-900 hover:bg-black shadow-apple text-sm tracking-wide transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 disabled:opacity-50">
                        <span x-show="!saving">Saya Setuju & Simpan</span>
                        <span x-show="saving">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>

    @else
        <!-- Apple iPad Completed State -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-apple p-8 sm:p-12 text-center max-w-lg mx-auto"
             x-data="{ showPinModal: false }">
            <div class="w-16 h-16 rounded-full bg-slate-900 text-white flex items-center justify-center mx-auto mb-5 shadow-apple">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            </div>

            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Persetujuan Berhasil Disimpan</h2>
            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                Terima kasih, <strong>{{ $patient->name }}</strong>. General Consent Anda telah tercatat ke dalam sistem rekam medis.
            </p>

            <div class="my-6 p-5 rounded-2xl bg-slate-50 border border-slate-200/70 text-left text-xs space-y-2 text-slate-600">
                <div class="flex justify-between">
                    <span class="text-slate-400">Nomor Rekam Medis:</span>
                    <span class="font-mono font-semibold text-slate-900">{{ $patient->patient_code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Waktu Persetujuan:</span>
                    <span class="font-medium text-slate-800">{{ $visit->consent_signed_at?->translatedFormat('d M Y, H:i') ?? date('d M Y, H:i') }} WIB</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Status Alur:</span>
                    <span class="font-semibold text-slate-900">Siap untuk Pemeriksaan Perawat</span>
                </div>
            </div>

            <p class="text-xs text-slate-400 mb-6">
                Silakan kembalikan tablet kepada petugas resepsionis atau perawat jaga.
            </p>

            <!-- Staff PIN Security Button -->
            <button type="button"
                    @click="showPinModal = true"
                    class="h-11 px-6 rounded-full border border-slate-200/90 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 shadow-2xs transition-all active:scale-[0.98] inline-flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <span>Kembali ke Menu Staf</span>
            </button>

            <!-- Staff PIN Security Modal -->
            <div x-show="showPinModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-md p-4">
                <div class="bg-white rounded-3xl p-6 sm:p-7 max-w-sm w-full text-left shadow-apple-elevated border border-slate-200" @click.away="showPinModal = false">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h4 class="font-bold text-slate-900 text-sm">Autentikasi PIN Petugas</h4>
                        <button type="button" @click="showPinModal = false" class="text-slate-400 hover:text-slate-600 p-1">&times;</button>
                    </div>

                    <p class="text-xs text-slate-500 mt-3 mb-4">
                        Masukkan PIN staf (Default: <span class="font-mono font-bold text-slate-900">1234</span>) untuk kembali:
                    </p>

                    <form wire:submit="verifyStaffPin">
                        <input wire:model="staffPin"
                               type="password"
                               maxlength="6"
                               placeholder="••••"
                               class="w-full text-center text-2xl tracking-[0.4em] font-mono font-bold px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-900 mb-3"
                               autofocus>

                        @if($pinError)
                            <div class="text-xs text-rose-500 font-medium mb-3">{{ $pinError }}</div>
                        @endif

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="showPinModal = false" class="h-10 px-4 rounded-full text-xs font-semibold text-slate-600 hover:bg-slate-100">
                                Batal
                            </button>
                            <button type="submit" class="h-10 px-5 rounded-full text-xs font-semibold text-white bg-slate-900 hover:bg-black transition-all">
                                Konfirmasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function signatureComponent() {
    return {
        pad: null,
        isEmpty: true,
        saving: false,
        resizeObserver: null,

        initSignature() {
            this.$nextTick(() => {
                const canvas = this.$refs.canvas;
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
                    const width = rect.width;
                    const height = rect.height;
                    if (width <= 0 || height <= 0) return;

                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    const targetWidth = Math.round(width * ratio);
                    const targetHeight = Math.round(height * ratio);

                    if (canvas.width === targetWidth && canvas.height === targetHeight) {
                        return;
                    }

                    const data = this.pad.toData();

                    canvas.width = targetWidth;
                    canvas.height = targetHeight;

                    const ctx = canvas.getContext('2d');
                    ctx.scale(ratio, ratio);

                    this.pad.clear();
                    if (data && data.length > 0) {
                        this.pad.fromData(data);
                    }
                };

                requestAnimationFrame(() => resizeCanvas());

                if (window.ResizeObserver) {
                    this.resizeObserver = new ResizeObserver(() => {
                        resizeCanvas();
                    });
                    this.resizeObserver.observe(canvas);
                } else {
                    window.addEventListener('resize', resizeCanvas);
                }

                this.pad.addEventListener('endStroke', () => {
                    this.isEmpty = this.pad.isEmpty();
                });
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
