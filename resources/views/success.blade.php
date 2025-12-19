@extends('layouts.main')

@section('title', 'Pesanan Berhasil')

@push('styles')
<style>
    body { background: #f8faf9; }

    .success-container {
        max-width: 600px;
        margin: 80px auto;
        background: #fff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        text-align: center;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: #e0f2f1;
        color: #136f63;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        margin-bottom: 20px;
    }

    .screenshot-alert {
        background: #fff3cd;
        border: 2px dashed #ffc107;
        color: #856404;
        padding: 15px;
        border-radius: 10px;
        margin: 20px 0;
        font-weight: bold;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }

    .details-box {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        text-align: left;
        margin-bottom: 25px;
        font-size: 0.95em;
        border: 1px solid #eee;
    }

    .details-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    .details-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .btn-wa {
        display: block;
        width: 100%;
        background: #25D366;
        color: white;
        font-weight: bold;
        padding: 15px;
        border-radius: 50px;
        text-decoration: none;
        font-size: 16px;
        transition: 0.3s;
        margin-bottom: 15px;
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
    }
    .btn-wa:hover {
        background: #1da851;
        color: white;
        transform: translateY(-2px);
    }

    .btn-home {
        display: inline-block;
        color: #666;
        text-decoration: none;
        margin-top: 10px;
        font-weight: 500;
    }
    .btn-home:hover { color: #136f63; }
</style>
@endpush

@section('content')
<div class="container">
    <div class="success-container">

        {{-- Ikon Sukses --}}
        <div class="success-icon">
            <i class="fa-solid fa-check"></i>
        </div>

        <h2 style="color: #136f63; font-weight: bold; margin-bottom: 10px;">Pesanan Berhasil!</h2>
        <p style="color: #666;">Terima kasih telah menyewa di Veilside Adventure.</p>

        {{-- PERINGATAN SCREENSHOT --}}
        <div class="screenshot-alert">
            <i class="fa-solid fa-camera mb-2" style="font-size: 24px;"></i><br>
            MOHON SCREENSHOT HALAMAN INI<br>
            <small style="font-weight: normal;">Simpan halaman ini sebagai bukti pemesanan Anda.</small>
        </div>

        {{-- DETAIL PESANAN --}}
        <div class="details-box">
            <div class="details-row">
                <span class="text-muted">ID Pesanan</span>
                <strong>#{{ $transaction->id }}</strong>
            </div>
            <div class="details-row">
                <span class="text-muted">Nama Penyewa</span>
                <strong>{{ $transaction->name }}</strong>
            </div>

            <div class="details-row">
                <span class="text-muted">Tanggal Sewa</span>
                <strong>{{ $transaction->start_date ? $transaction->start_date->format('d M Y') : '-' }}</strong>
            </div>

            <div class="details-row">
                <span class="text-muted">Durasi Sewa</span>
                <strong>{{ $transaction->days }} Hari</strong>
            </div>

            <div class="details-row" style="flex-direction: column; align-items: flex-start;">
                <span class="text-muted" style="margin-bottom: 5px;">Barang Sewaan:</span>
                <ul style="padding-left: 20px; margin: 0; font-size: 0.9em; width: 100%;">
                    @if(is_array($transaction->items))
                        @foreach($transaction->items as $item)
                            <li style="margin-bottom: 4px;">
                                {{ $item['name'] ?? 'Item' }}
                                <span style="float: right; font-weight: bold;">x{{ $item['qty'] ?? 1 }}</span>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div class="details-row">
                <span class="text-muted">Total Tagihan</span>
                <strong style="color: #136f63; font-size: 1.2em;">
                    Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                </strong>
            </div>

            <div class="details-row">
                <span class="text-muted">Metode Pembayaran</span>
                <span class="badge bg-warning text-dark">{{ strtoupper($transaction->payment_method) }}</span>
            </div>
        </div>

        {{-- TOMBOL WHATSAPP --}}
        @php
            $adminNumber = '6281459125873';

            $itemList = "";
            if(is_array($transaction->items)) {
                foreach($transaction->items as $item) {
                    $itemList .= "- " . ($item['name'] ?? 'Item') . " (" . ($item['qty'] ?? 1) . " pcs)\n";
                }
            }

            $message = "*KONFIRMASI PENYEWAAN BARU*\n\n" .
                       "ID: #" . $transaction->id . "\n" .
                       "Nama: " . $transaction->name . "\n" .
                       "Tgl Sewa: " . ($transaction->start_date ? $transaction->start_date->format('d M Y') : '-') . "\n" .
                       "Durasi: " . $transaction->days . " Hari\n\n" .
                       "*Detail Barang:*\n" . $itemList . "\n" .
                       "Total: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n" .
                       "Metode: " . strtoupper($transaction->payment_method) . "\n\n" .
                       "Mohon diproses min. Terima kasih!";

            $waLink = "https://wa.me/$adminNumber?text=" . urlencode($message);
        @endphp

        <a href="{{ $waLink }}" target="_blank" class="btn-wa">
            <i class="fa-brands fa-whatsapp me-2"></i> Konfirmasi ke Admin
        </a>

        <a href="{{ route('home') }}" class="btn-home">Kembali ke Beranda</a>

    </div>
</div>
@endsection
