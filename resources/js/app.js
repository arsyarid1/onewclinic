import SignaturePad from 'signature_pad';

window.SignaturePad = SignaturePad;

/**
 * Client-side Image Optimization and Compression
 * Automatically scales high-resolution mobile/tablet camera photos (e.g. 12MP/8MB)
 * down to max 1600x1600 (~250-400KB) JPEG before uploading to the server.
 */
window.compressImage = function (file, maxDimension = 1600, quality = 0.85) {
    return new Promise((resolve) => {
        if (!file || !file.type || !file.type.startsWith('image/')) {
            return resolve(file);
        }

        const img = new Image();
        const reader = new FileReader();

        reader.onload = (e) => {
            img.onload = () => {
                let { width, height } = img;
                if (width <= maxDimension && height <= maxDimension && file.size < 600 * 1024) {
                    return resolve(file);
                }

                if (width > height) {
                    if (width > maxDimension) {
                        height = Math.round((height * maxDimension) / width);
                        width = maxDimension;
                    }
                } else {
                    if (height > maxDimension) {
                        width = Math.round((width * maxDimension) / height);
                        height = maxDimension;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob((blob) => {
                    if (!blob) return resolve(file);
                    const compressed = new File([blob], file.name.replace(/\.[^.]+$/, '.jpg'), {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });
                    resolve(compressed);
                }, 'image/jpeg', quality);
            };

            img.onerror = () => resolve(file);
            img.src = e.target.result;
        };

        reader.onerror = () => resolve(file);
        reader.readAsDataURL(file);
    });
};

/**
 * Alpine.js Photo Uploader Component
 * Provides immediate local preview (URL.createObjectURL) the millisecond a photo is captured,
 * followed by client-side compression and reliable upload to Livewire backend.
 */
window.photoUploader = function (propertyName, initialUrl = null) {
    return {
        propertyName: propertyName,
        previewUrl: initialUrl || null,
        isUploading: false,
        uploadProgress: 0,
        errorMessage: null,
        isSuccess: false,

        init() {
            if (initialUrl && initialUrl !== '') {
                this.previewUrl = initialUrl;
            }
        },

        handleFileSelect(event) {
            const input = event.target;
            const file = input.files && input.files[0];
            if (!file) return;

            // 1. INSTANT LOCAL PREVIEW IN THE FORM IMMEDIATELY
            if (this.previewUrl && this.previewUrl.startsWith('blob:')) {
                try {
                    URL.revokeObjectURL(this.previewUrl);
                } catch (e) {}
            }
            this.previewUrl = URL.createObjectURL(file);
            this.isUploading = true;
            this.isSuccess = false;
            this.errorMessage = null;
            this.uploadProgress = 15;

            // 2. Compress image then upload to Livewire
            window.compressImage(file).then((readyFile) => {
                this.uploadProgress = 35;
                if (!this.$wire || typeof this.$wire.upload !== 'function') {
                    this.isUploading = false;
                    return;
                }

                this.$wire.upload(
                    this.propertyName,
                    readyFile,
                    () => {
                        this.isUploading = false;
                        this.isSuccess = true;
                        this.uploadProgress = 100;
                        if (this.$wire && typeof this.$wire.saveCurrentState === 'function') {
                            this.$wire.saveCurrentState();
                        }
                    },
                    (error) => {
                        this.isUploading = false;
                        this.errorMessage = 'Gagal mengunggah foto. Silakan coba lagi.';
                        console.error('Upload error for ' + this.propertyName + ':', error);
                    },
                    (progressEvent) => {
                        const p = typeof progressEvent === 'number'
                            ? progressEvent
                            : (progressEvent && progressEvent.detail ? progressEvent.detail.progress : 50);
                        this.uploadProgress = 35 + Math.round(p * 0.65);
                    }
                );
            }).catch((err) => {
                console.error('Compression error:', err);
                if (this.$wire && typeof this.$wire.upload === 'function') {
                    this.$wire.upload(
                        this.propertyName,
                        file,
                        () => {
                            this.isUploading = false;
                            this.isSuccess = true;
                            if (this.$wire && typeof this.$wire.saveCurrentState === 'function') {
                                this.$wire.saveCurrentState();
                            }
                        },
                        () => {
                            this.isUploading = false;
                            this.errorMessage = 'Gagal mengunggah foto.';
                        }
                    );
                } else {
                    this.isUploading = false;
                }
            });
        }
    };
};
