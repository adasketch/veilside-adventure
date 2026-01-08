<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Veilside Adventure</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0; font-size: 14px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        .status-lunas { font-weight: bold; color: green; }
        .status-pending { font-weight: bold; color: orange; }
        .status-batal { font-weight: bold; color: red; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }

        @media print {
            @page { margin: 1cm; size: A4 landscape; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>Laporan Transaksi</h2>
        <p>Veilside Adventure - Outdoor Rental</p>
        <p>Dicetak pada: {{ date('d F Y, H:i') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Penyewa & Kontak</th>
                <th width="20%">Tanggal Sewa</th>
                <th width="30%">Barang Sewaan</th>
                <th width="10%">Status</th>
                <th width="15%" style="text-align: right;">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPemasukan = 0; @endphp
            @forelse ($transactions as $index => $t)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><strong>{{ $t->name }}</strong><br><small>{{ $t->whatsapp }}</small></td>
                <td>{{ $t->start_date->format('d M Y') }}<br><small>{{ $t->days }} Hari</small></td>
                <td>
                    <ul style="margin: 0; padding-left: 15px;">
                        @foreach ($t->items as $item)
                            <li>{{ $item['name'] }} (x{{ $item['qty'] }})</li>
                        @endforeach
                    </ul>
                </td>
                <td><span class="status-{{ $t->status }}">{{ strtoupper($t->status) }}</span></td>
                <td style="text-align: right;">Rp {{ number_format($t->total_price, 0, ',', '.') }}</td>
            </tr>
            @php $totalPemasukan += $t->total_price; @endphp
            @empty
            <tr><td colspan="6" style="text-align: center;">Data tidak ditemukan.</td></tr>
            @endforelse
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">TOTAL PEMASUKAN</td>
                <td style="text-align: right;">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
