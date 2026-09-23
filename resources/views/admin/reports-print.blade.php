<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>รายงานสรุปผลระบบห้องสมุด - {{ now()->format('Y-m-d') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=noto-sans-thai:400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Noto Sans Thai', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            line-height: 1.5;
            padding: 24px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            padding: 36px;
        }

        /* Action Top Bar */
        .action-bar {
            max-width: 1000px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 10px;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-primary {
            background-color: #059669;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
        }
        .btn-primary:hover {
            background-color: #047857;
        }
        .btn-secondary {
            background-color: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
        }
        .btn-secondary:hover {
            background-color: #f1f5f9;
        }

        /* Header Document Section */
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 24px;
            border-b: 2px solid #10b981;
            margin-bottom: 24px;
        }
        .brand-box {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .brand-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #10b981, #0d9488);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }
        .brand-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
        }
        .brand-sub {
            font-size: 12px;
            color: #059669;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .meta-box {
            text-align: right;
            font-size: 13px;
            color: #64748b;
        }
        .meta-box strong {
            color: #0f172a;
        }

        /* Summary Cards Grid */
        .grid-cards {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-bottom: 28px;
        }
        .card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px;
            text-align: center;
        }
        .card-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }
        .card-val {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 4px;
        }
        .card-val.emerald { color: #059669; }
        .card-val.rose { color: #e11d48; }

        /* Tables Styling */
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 16px;
            background-color: #10b981;
            border-radius: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 28px;
            font-size: 13px;
        }
        th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 700;
            text-align: left;
            padding: 10px 12px;
            border-bottom: 2px solid #cbd5e1;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }
        tr:nth-child(even) td {
            background-color: #fafafa;
        }

        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 9999px;
        }
        .badge-borrowed { background-color: #d1fae5; color: #065f46; }
        .badge-returned { background-color: #f1f5f9; color: #475569; }
        .badge-overdue { background-color: #ffe4e6; color: #9f1239; }
        .badge-pending { background-color: #fef3c7; color: #92400e; }

        /* Signature Footer */
        .footer-sig {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 12px;
            color: #64748b;
        }
        .sig-block {
            text-align: center;
            width: 220px;
        }
        .sig-line {
            margin-top: 50px;
            border-top: 1px dashed #94a3b8;
            padding-top: 6px;
        }

        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .container {
                border: none;
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .action-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Action Bar (Hidden on Print) -->
    <div class="action-bar">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            ← ย้อนกลับ
        </a>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn btn-primary">
                🖨️ พิมพ์เอกสาร / บันทึกเป็น PDF
            </button>
        </div>
    </div>

    <!-- Document Container -->
    <div class="container">
        <!-- Header -->
        <div class="doc-header">
            <div class="brand-box">
                <div class="brand-icon">
                    <svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <h1 class="brand-title">{{ config('app.name', 'ระบบจัดการห้องสมุด') }}</h1>
                    <p class="brand-sub">รายงานสรุปผลการดำเนินงานระบบยืม-คืนหนังสือ</p>
                </div>
            </div>
            <div class="meta-box">
                <p><strong>เลขที่เอกสาร:</strong> RPT-{{ now()->format('Ymd-Hi') }}</p>
                <p><strong>วันที่ออกเอกสาร:</strong> {{ now()->locale('th')->translatedFormat('j F Y H:i น.') }}</p>
                <p><strong>ผู้ออกรายงาน:</strong> {{ auth()->user()?->name ?? 'ผู้ดูแลระบบ' }}</p>
            </div>
        </div>

        <!-- Summary Metric Cards Grid -->
        <div class="grid-cards">
            <div class="card">
                <div class="card-label">หนังสือทั้งหมด</div>
                <div class="card-val">{{ number_format($totalBooks) }}</div>
            </div>
            <div class="card">
                <div class="card-label">สมาชิกทั้งหมด</div>
                <div class="card-val">{{ number_format($totalMembers) }}</div>
            </div>
            <div class="card">
                <div class="card-label">หมวดหมู่หนังสือ</div>
                <div class="card-val">{{ number_format($totalCategories) }}</div>
            </div>
            <div class="card">
                <div class="card-label">กำลังยืม</div>
                <div class="card-val emerald">{{ number_format($activeLoans) }}</div>
            </div>
            <div class="card">
                <div class="card-label">เกินกำหนดส่ง</div>
                <div class="card-val rose">{{ number_format($overdueLoans) }}</div>
            </div>
        </div>

        <!-- Popular Books Summary -->
        @if(count($popularBooks) > 0)
        <h2 class="section-title">หนังสือยอดนิยม ( Top 10 Most Borrowed )</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">อันดับ</th>
                    <th>ชื่อหนังสือ</th>
                    <th>หมวดหมู่</th>
                    <th style="width: 140px; text-align: right;">จำนวนครั้งที่ถูกยืม</th>
                </tr>
            </thead>
            <tbody>
                @foreach($popularBooks as $index => $book)
                <tr>
                    <td><strong>#{{ $index + 1 }}</strong></td>
                    <td><strong>{{ $book->title }}</strong></td>
                    <td>{{ $book->category?->name ?? 'ทั่วไป' }}</td>
                    <td style="text-align: right;"><span class="badge badge-borrowed">{{ number_format($book->borrow_records_count) }} ครั้ง</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Recent Borrow Records Table -->
        <h2 class="section-title">ประวัติการทำรายการยืม-คืนล่าสุด ( Recent Circulation Activity )</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 90px;">รหัสรายการ</th>
                    <th>ชื่อหนังสือ</th>
                    <th>หมวดหมู่</th>
                    <th>สมาชิกผู้ยืม</th>
                    <th style="width: 100px;">วันที่ยืม</th>
                    <th style="width: 100px;">กำหนดคืน</th>
                    <th style="width: 100px;">วันที่คืนจริง</th>
                    <th style="width: 110px;">สถานะ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRecords as $record)
                @php
                    $statusClass = 'badge-returned';
                    $statusLabel = 'คืนเรียบร้อย';

                    if ($record->status === 'borrowed') {
                        if ($record->due_date && $record->due_date->isPast()) {
                            $statusClass = 'badge-overdue';
                            $statusLabel = 'เกินกำหนดส่ง';
                        } else {
                            $statusClass = 'badge-borrowed';
                            $statusLabel = 'กำลังยืม';
                        }
                    } elseif ($record->status === 'overdue') {
                        $statusClass = 'badge-overdue';
                        $statusLabel = 'เกินกำหนดส่ง';
                    } elseif ($record->status === 'pending') {
                        $statusClass = 'badge-pending';
                        $statusLabel = 'รออนุมัติ';
                    }
                @endphp
                <tr>
                    <td><code>BR-{{ str_pad($record->id, 5, '0', STR_PAD_LEFT) }}</code></td>
                    <td><strong>{{ $record->book?->title ?? 'ไม่พบหนังสือ' }}</strong></td>
                    <td>{{ $record->book?->category?->name ?? 'ทั่วไป' }}</td>
                    <td>{{ $record->user?->name ?? 'ไม่พบผู้ใช้' }}</td>
                    <td>{{ $record->borrowed_at?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $record->due_date?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $record->returned_at?->format('d/m/Y') ?? '-' }}</td>
                    <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #94a3b8; padding: 20px;">ไม่พบข้อมูลรายการยืม-คืน</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Signature Block -->
        <div class="footer-sig">
            <div>
                <p>รายงานนี้เป็นเอกสารสรุปผลการใช้งานจริงจากฐานข้อมูลระบบ</p>
                <p style="font-size: 11px; margin-top: 4px; color: #94a3b8;">{{ config('app.name', 'ระบบจัดการห้องสมุด') }} © {{ now()->year }}</p>
            </div>
            <div class="sig-block">
                <div class="sig-line">
                    <p><strong>({{ auth()->user()?->name ?? 'ผู้ดูแลระบบ' }})</strong></p>
                    <p style="font-size: 11px; color: #64748b;">ตำแหน่ง: ผู้ดูแลระบบห้องสมุด</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
