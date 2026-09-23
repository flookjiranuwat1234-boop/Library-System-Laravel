<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Code — {{ $book->title }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #f8fafc; display: flex; min-height: 100vh; align-items: center; justify-content: center; }
        .card { background: white; border-radius: 20px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 40px; text-align: center; max-width: 360px; width: 100%; }
        .logo { font-size: 32px; margin-bottom: 8px; }
        .label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.2em; color: #6b7280; margin-bottom: 4px; }
        h1 { font-size: 18px; font-weight: 700; color: #111827; line-height: 1.3; margin-bottom: 4px; }
        .author { font-size: 13px; color: #6b7280; margin-bottom: 24px; }
        .qr-wrapper { display: flex; justify-content: center; margin-bottom: 20px; }
        .qr-wrapper img, .qr-wrapper canvas { display: block; border-radius: 12px; max-width: 100%; height: auto; }
        .url { font-size: 10px; color: #9ca3af; word-break: break-all; margin-bottom: 24px; }
        .print-btn { display: inline-block; background: #059669; color: white; padding: 10px 28px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: background 0.2s; }
        .print-btn:hover { background: #047857; }
        @media print { .print-btn { display: none; } body { background: white; } .card { box-shadow: none; } }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">📚</div>
        <p class="label">ห้องสมุด · QR Code</p>
        <h1>{{ $book->title }}</h1>
        <p class="author">{{ $book->author }}@if($book->year) · {{ $book->year }}@endif</p>
        
        <div class="qr-wrapper">
            <canvas id="qr-canvas" style="display:none;"></canvas>
            <img id="qr-img" src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data={{ urlencode($bookUrl) }}&color=065f46" alt="QR Code" width="240" height="240">
        </div>

        <p class="url">{{ $bookUrl }}</p>
        <button class="print-btn" onclick="window.print()">🖨️ พิมพ์ QR Code</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                if (typeof QRCode !== 'undefined') {
                    const container = document.querySelector('.qr-wrapper');
                    container.innerHTML = '';
                    new QRCode(container, {
                        text: @json($bookUrl),
                        width: 240,
                        height: 240,
                        colorDark: "#065f46",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                }
            } catch (e) {
                console.error("QRCode JS failed, falling back to QR API:", e);
            }
        });
    </script>
</body>
</html>
