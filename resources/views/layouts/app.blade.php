<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=noto-sans-thai:400,500,600,700&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            [x-cloak] { display: none !important; }
            .status-badge {
                display: inline-flex;
                align-items: center;
                border-radius: 9999px;
                padding: 0.25rem 0.75rem;
                font-size: 0.75rem;
                line-height: 1rem;
                font-weight: 600;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }
            .panel { overflow: hidden; border-radius: 1rem; border: 1px solid #e2e8f0; background-color: #ffffff; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
            .page-shell { padding-top: 1.25rem; padding-bottom: 2rem; }
            .page-container { max-width: 80rem; margin-left: auto; margin-right: auto; padding-left: 1rem; padding-right: 1rem; }
            .page-title { font-size: 1.5rem; font-weight: 700; color: #0f172a; }
            .button-primary { display: inline-flex; align-items: center; justify-content: center; border-radius: 0.75rem; background-color: #059669; padding: 0.625rem 1rem; font-size: 0.875rem; font-weight: 600; color: #ffffff; transition: background-color 0.2s; }
            .button-primary:hover { background-color: #047857; }
            .button-secondary { display: inline-flex; align-items: center; justify-content: center; border-radius: 0.75rem; border: 1px solid #cbd5e1; background-color: #ffffff; padding: 0.625rem 1rem; font-size: 0.875rem; font-weight: 600; color: #334155; transition: border-color 0.2s; }
            .button-secondary:hover { border-color: #94a3b8; background-color: #f8fafc; }
            .form-control {
                display: block;
                width: 100%;
                border-radius: 0.75rem;
                border: 1px solid #cbd5e1;
                background-color: #ffffff;
                padding: 0.625rem 0.875rem;
                font-size: 0.875rem;
                color: #0f172a;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                outline: none;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .form-control:focus {
                border-color: #059669;
                box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
            }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="relative flex min-h-screen flex-col overflow-x-hidden bg-slate-50">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_10%_10%,rgba(16,185,129,0.08),transparent_25%),radial-gradient(circle_at_90%_40%,rgba(14,165,233,0.07),transparent_25%)]"></div>
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="border-b border-slate-200/70 bg-white shadow-sm">
                    <div class="mx-auto max-w-7xl px-4 py-3.5 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative flex-1">
                {{ $slot }}
            </main>

            <footer class="relative border-t border-slate-200/80 bg-white/80 py-6 backdrop-blur">
                <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                    <p>© {{ now()->year }} {{ config('app.name', 'ระบบห้องสมุด') }}</p>
                    <p class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>ระบบพร้อมให้บริการ</p>
                </div>
            </footer>
        </div>

        {{-- Live Camera & Image File QR Scanner Modal --}}
        <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
        <div x-data="qrScannerModal()" @open-qr-scanner.window="openModal()" x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
            <div @click.away="closeModal()" class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-lg">📷</span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">สแกน QR Code หนังสือ</h3>
                            <p class="text-xs text-slate-500">ใช้กล้องมือถือ/เว็บบอร์ด สแกนเพื่อเปิดหนังสือทันที</p>
                        </div>
                    </div>
                    <button @click="closeModal()" type="button" class="rounded-xl p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                        ✖
                    </button>
                </div>

                <div id="qr-reader" class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 min-h-[240px] flex items-center justify-center"></div>

                <div x-show="errorMsg" class="rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs font-semibold text-rose-700" x-text="errorMsg"></div>

                <div class="flex flex-col gap-2 pt-1">
                    <p class="text-center text-xs text-slate-400">หรือ เลือกรูปภาพ QR Code จากอุปกรณ์เพื่อสแกน:</p>
                    <input type="file" id="qr-file-input" accept="image/*" @change="handleFileUpload($event)" class="text-xs file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                </div>
            </div>
        </div>

        <script>
            function qrScannerModal() {
                return {
                    show: false,
                    html5QrCode: null,
                    errorMsg: '',
                    openModal() {
                        this.show = true;
                        this.errorMsg = '';
                        this.$nextTick(() => {
                            this.startScanner();
                        });
                    },
                    closeModal() {
                        this.stopScanner();
                        this.show = false;
                    },
                    startScanner() {
                        if (!document.getElementById('qr-reader')) return;
                        try {
                            if (!this.html5QrCode) {
                                this.html5QrCode = new Html5Qrcode("qr-reader");
                            }
                            this.html5QrCode.start(
                                { facingMode: "environment" },
                                { fps: 10, qrbox: { width: 200, height: 200 } },
                                (decodedText) => {
                                    this.onSuccess(decodedText);
                                },
                                (error) => {}
                            ).catch(err => {
                                console.warn("Camera access warning:", err);
                                this.errorMsg = 'ไม่สามารถเปิดกล้องได้ (โปรดอนุญาตสิทธิ์ใช้งานกล้อง หรือเลือกไฟล์รูปภาพด้านล่างแทน)';
                            });
                        } catch(e) {
                            console.error("QR Scanner Init Error:", e);
                        }
                    },
                    stopScanner() {
                        if (this.html5QrCode && this.html5QrCode.isScanning) {
                            this.html5QrCode.stop().catch(err => console.error(err));
                        }
                    },
                    onSuccess(decodedText) {
                        this.stopScanner();
                        if (decodedText.startsWith('http://') || decodedText.startsWith('https://')) {
                            window.location.href = decodedText;
                        } else if (!isNaN(decodedText)) {
                            window.location.href = '/books/' + decodedText;
                        } else {
                            window.location.href = '/books?search=' + encodeURIComponent(decodedText);
                        }
                    },
                    handleFileUpload(event) {
                        const file = event.target.files[0];
                        if (!file) return;
                        if (!this.html5QrCode) {
                            this.html5QrCode = new Html5Qrcode("qr-reader");
                        }
                        this.html5QrCode.scanFile(file, true)
                            .then(decodedText => {
                                this.onSuccess(decodedText);
                            })
                            .catch(err => {
                                this.errorMsg = 'ไม่พบ QR Code ในรูปภาพที่เลือก กรุณาลองใหม่อีกครั้ง';
                            });
                    }
                }
            }
        </script>
    </body>
</html>
