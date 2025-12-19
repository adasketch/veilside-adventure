@extends('layouts.main')

@section('title', 'Form Penyewaan')

@push('styles')
<style>
    body { background: #f8faf9; }
    .form-container {
        max-width: 550px; margin: 60px auto; background: #fff;
        padding: 30px 40px; border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    label { font-weight: 600; margin-top: 15px; display: block; color: #333; margin-bottom: 5px; }
    input[type="text"], input[type="number"], input[type="date"], select {
        width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 15px;
        display: block; box-sizing: border-box; margin-bottom: 10px;
    }
    input:focus, select:focus { outline: none; border-color: #136f63; box-shadow: 0 0 5px rgba(19, 111, 99, 0.2); }
    .qr-section {
        text-align: center; margin-top: 20px; display: none; background: #f0fdf4;
        padding: 15px; border-radius: 10px; border: 1px dashed #136f63;
    }
    .qr-section img { width: 180px; margin: 10px 0; border-radius: 8px; }
    button[type="submit"] {
        display: block; width: 100%; background: #136f63; color: #fff; border: none;
        border-radius: 8px; padding: 14px; font-size: 16px; font-weight: bold;
        cursor: pointer; margin-top: 25px; transition: 0.3s;
    }
    button[type="submit"]:hover { background: #0b4d47; }

    /* Style ringkasan keranjang */
    .summary-item { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.95em; }
    .summary-total { border-top: 2px dashed #ddd; margin-top: 10px; padding-top: 10px; text-align: right; font-weight: bold; color: #136f63; font-size: 1.1em; }
</style>
@endpush

@section('content')
<main>
    <div class="form-container">
        <h2 style="text-align: center; color: #136f63; margin-bottom: 25px; font-weight: bold;">Form Penyewaan</h2>

        <div id="cart-summary-area" style="margin-bottom: 20px; padding: 15px; background: #f1f1f1; border-radius: 8px;">
            <p class="text-center text-muted">Memuat keranjang...</p>
        </div>

        <form id="transaction-form" action="{{ route('transaction.store') }}" method="POST">
            @csrf

            <input type="hidden" name="cart_json" id="cart_json_input">

            <label for="name">Nama Penyewa</label>
            <input type="text" name="name" id="name" placeholder="Masukkan nama Anda" required
                   value="{{ Auth::check() ? Auth::user()->name : '' }}" />

            <label for="wa">Nomor WhatsApp</label>
            <input type="text" name="wa" id="wa" placeholder="08xxxxxxxxxx" required />

            <label for="start">Tanggal Sewa</label>
            <input type="date" name="start" id="start" required />

            <label for="days">Lama Sewa (hari)</label>
            <input type="number" name="days" id="days" min="1" value="1" required />

            <label for="payment">Metode Pembayaran</label>
            <select name="payment" id="payment" onchange="toggleQRIS(this)" required>
                <option value="">-- Pilih Metode --</option>
                <option value="cash">Cash</option>
                <option value="qris">QRIS</option>
            </select>

            <div class="qr-section" id="qr-section">
                <p>Scan kode QR berikut untuk pembayaran:</p>
                <img src="{{ asset('img/qriss.jpg') }}" alt="QRIS" />
                <p style="margin:0; color:#136f63;"><strong>QRIS BRI</strong></p>
            </div>

            <button type="submit">Konfirmasi Pemesanan</button>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
    function toggleQRIS(selectElement) {
        const qrSection = document.getElementById('qr-section');
        qrSection.style.display = (selectElement.value === 'qris') ? 'block' : 'none';
    }

    document.addEventListener("DOMContentLoaded", function() {
        // 1. GUNAKAN KEY YANG SAMA DENGAN SEWA.JS
        const CART_KEY = "vs_cart";

        // 2. Ambil data
        const rawCart = localStorage.getItem(CART_KEY);
        const cart = JSON.parse(rawCart || "[]");

        const summaryDiv = document.getElementById("cart-summary-area");
        const hiddenInput = document.getElementById("cart_json_input");
        const form = document.getElementById("transaction-form"); // Pastikan ID form sesuai

        // 3. Render Ringkasan
        if (cart.length === 0) {
            summaryDiv.innerHTML = '<p style="text-align:center; color:red; font-weight:bold;">Keranjang Kosong! <br> <a href="{{ route("sewa") }}">Kembali ke Produk</a></p>';
            // Matikan tombol submit
            const btn = document.querySelector('button[type="submit"]');
            if(btn) {
                btn.disabled = true;
                btn.innerText = "Keranjang Kosong";
                btn.style.background = "#ccc";
            }
        } else {
            let html = '<h4 style="border-bottom:1px solid #ccc; padding-bottom:10px; margin-bottom:10px;">Ringkasan Pesanan</h4>';
            let total = 0;

            cart.forEach(item => {
                let price = parseInt(item.price);
                html += `
                    <div style="display:flex; justify-content:space-between; margin-bottom:5px; font-size:0.9em;">
                        <span>${item.qty}x ${item.name}</span>
                        <span>Rp ${(price * item.qty).toLocaleString('id-ID')}</span>
                    </div>
                `;
                total += price * item.qty;
            });

            html += `<div style="border-top:2px dashed #ccc; margin-top:10px; padding-top:10px; text-align:right; font-weight:bold; color:#136f63;">Total per Hari: Rp ${total.toLocaleString('id-ID')}</div>`;
            summaryDiv.innerHTML = html;

            // Masukkan data ke input hidden agar dikirim ke Laravel
            hiddenInput.value = JSON.stringify(cart);
        }

        // 4. Handle Submit
        if(form) {
            form.addEventListener("submit", function(e) {
                if (cart.length === 0) {
                    e.preventDefault();
                    alert("Keranjang kosong!");
                } else {
                    // Hapus keranjang setelah dikirim (opsional)
                    // localStorage.removeItem(CART_KEY);
                }
            });
        }
    });
</script>
@endpush
