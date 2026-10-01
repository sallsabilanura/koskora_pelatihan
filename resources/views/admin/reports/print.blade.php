<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan Kos - {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 40px;
        }
        .header {
            text-align: left;
            border-bottom: 2px solid #1e1b9b;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 140px;
            height: auto;
            margin-bottom: 15px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: #1e1b9b;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin: 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table td {
            vertical-align: top;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            font-size: 13px;
        }
        .data-table th {
            background-color: #f8fafc;
            color: #1e1b9b;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            text-align: left;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .total-row {
            background-color: #f0f1ff;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
        }
        .signature {
            float: right;
            text-align: center;
            width: 200px;
        }
        .signature-line {
            margin-top: 80px;
            border-bottom: 1px solid #000;
        }
        @media print {
            @page { size: landscape; }
            body { padding: 0; }
            button { display: none; }
        }
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #1e1b9b;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(30,27,155,0.3);
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Cetak Laporan</button>

    <div class="header">
        <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="logo">
        <p class="subtitle">Platform Manajemen Kos Modern<br>Laporan Keuangan & Rekapitulasi Pembayaran</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>Periode Laporan:</strong><br>
                {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}
            </td>
            <td width="50%" class="text-right">
                <strong>Tanggal Cetak:</strong><br>
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Tanggal</th>
                <th width="25%">Nama Penyewa</th>
                <th width="15%">Kamar</th>
                <th width="20%">Periode Sewa</th>
                <th width="20%" class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $payment)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                    <td>{{ $payment->rental->tenant->name ?? '-' }}</td>
                    <td>Kamar {{ $payment->rental->roomRental->room->room_number ?? '-' }}</td>
                    <td>{{ $payment->payment_period }}</td>
                    <td class="text-right">{{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada transaksi pembayaran lunas pada periode ini.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="5" class="text-right" style="padding: 12px;">TOTAL PENDAPATAN</td>
                <td class="text-right" style="padding: 12px; font-size: 16px;">{{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Admin KosKora</p>
            <div class="signature-line"></div>
            <p style="margin-top: 5px; font-size: 12px;">(Tanda Tangan & Nama Terang)</p>
        </div>
    </div>
</body>
</html>
