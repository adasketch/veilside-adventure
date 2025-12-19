@extends('layouts.main')
@section('title', 'Produk | Veilside Adventure')

@section('content')
{{-- SECTION HERO: LOGIKA BANNER DISKON --}}
<section class="heroo" style="background: #199181e3; color: #fff; text-align: center; padding: 120px 20px 60px;">
    <div class="container">
        <h2>Daftar Perlengkapan Outdoor</h2>

        {{-- Logika Cek Hari Kamis --}}
        @php
            $isThursday = now()->timezone('Asia/Jakarta')->isThursday();
        @endphp

        @if($isThursday)
            {{-- Tampilan Jika Hari Kamis --}}
            <div style="background: white; color: #199181; display: inline-block; padding: 5px 15px; border-radius: 20px; font-weight: bold; margin-bottom: 10px;">
                🎉 PROMO KAMIS: DISKON 10% SEMUA BARANG! 🎉
            </div>
            <p>Harga di bawah ini sudah termasuk potongan harga.</p>
        @else
            {{-- Tampilan Hari Biasa --}}
            <p>Pilih barang yang kamu butuhkan untuk petualanganmu.</p>
        @endif
    </div>
</section>

{{-- SECTION PRODUK --}}
<section class="section">
    <div class="container">
        {{-- Tempat Render Produk oleh sewa.js --}}
        <div id="product-list" class="grid"></div>

        {{-- Tombol Lanjut ke Form --}}
        <a href="{{ route('form') }}" class="btn-primary" style="display: block; width: max-content; margin: 30px auto 0; text-decoration: none; text-align: center; padding: 10px 20px; border-radius: 5px; color: white; background-color: #0c6452;">
            Lanjut ke Form Penyewaan
        </a>
    </div>
</section>

{{-- Panel Keranjang Slide-in --}}
<aside id="cart-panel" class="cart-panel">
    <div class="cart-header">
        <h3>Keranjang</h3>
        <button id="cart-close" type="button">✕</button>
    </div>
    <div id="cart-items" class="cart-items"></div>
    <div class="cart-footer">
        <p>Total per hari: <strong id="cart-total">Rp 0</strong></p>
        <a href="{{ route('form') }}" class="btn-primary" style="width: 100%; text-align: center; display: block; padding: 10px; background: #0c6452; color: white; text-decoration: none; border-radius: 5px;">Lanjut ke Form</a>
    </div>
</aside>
<div id="cart-backdrop" class="cart-backdrop"></div>
@endsection

@push('scripts')
<script>
    // Data dari Laravel dikirim ke variable global JS
    window.productsFromDB = @json($products).map(item => {
        return {
            id: item.id, // Tambahkan ID jika diperlukan untuk cart
            name: item.name,

            // Harga (sudah didiskon dari Controller jika Kamis)
            price: item.price,

            // Data tambahan untuk logika tampilan coret (diskon)
            // Menggunakan operator nullish coalescing (??) agar tidak error jika data null
            original_price: item.original_price ?? null,
            has_discount: item.has_discount ?? false,

            desc: item.description,

            // PERBAIKAN DI SINI:
            // Kembali menggunakan asset('') + item.image sesuai kode lama Anda agar gambar muncul
            img: "{{ asset('') }}" + item.image
        };
    });
</script>
<script src="{{ asset('js/sewa.js') }}"></script>
@endpush
