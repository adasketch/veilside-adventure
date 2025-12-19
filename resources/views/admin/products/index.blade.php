@extends('layouts.main')

@section('title', 'Kelola Produk')

@push('styles')
<style>
    /* === 1. LAYOUT UTAMA (Sticky Footer) === */
    html, body { height: 100%; }
    body { display: flex; flex-direction: column; background-color: #f8faf9; }
    .main-content { flex: 1 0 auto; padding-top: 110px; padding-bottom: 40px; width: 100%; }
    footer { flex-shrink: 0; margin-top: auto; }

    /* === 2. TABEL COMPACT === */
    .table-compact th {
        font-size: 0.75rem;
        padding: 12px 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background-color: #1d3b36;
        color: white;
        vertical-align: middle;
    }

    .table-compact td {
        font-size: 0.85rem;
        padding: 8px 10px;
        vertical-align: middle;
    }

    /* Style Gambar Produk Kecil */
    .product-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #ddd;
    }
</style>
@endpush

@section('content')
<div class="container main-content">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold" style="color:#0c6452; margin: 0;">Daftar Produk</h4>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Kelola barang penyewaan Anda di sini.</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm me-2">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-success btn-sm shadow-sm">
                <i class="fa-solid fa-plus me-1"></i> Tambah Produk
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 mb-3 shadow-sm" role="alert" style="font-size: 0.85rem;">
            <i class="fa-solid fa-check-circle me-1"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 10px;"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 8px;">
        <div class="table-responsive">
            <table class="table table-hover table-compact mb-0">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="10%" class="text-center">Gambar</th>
                        <th width="20%">Nama Produk</th>
                        <th width="15%">Harga/Hari</th>
                        <th>Deskripsi</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($products as $index => $product)
                    <tr>
                        <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>

                        <td class="text-center">
                            {{-- Pastikan path gambar sesuai --}}
                            <img src="{{ asset($product->image) }}" class="product-thumb" alt="Img">
                        </td>

                        <td>
                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                        </td>

                        <td class="text-success fw-bold">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>

                        <td>
                            <span class="text-muted" style="font-size: 0.8rem;">
                                {{ Str::limit($product->description, 60) }}
                            </span>
                        </td>

                        <td class="text-center">
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?');">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm text-white" title="Edit" style="padding: 2px 8px;">
                                    <i class="fa-solid fa-pen-to-square" style="font-size: 0.8rem;"></i>
                                </a>

                                @csrf
                                @method('DELETE')

                                {{-- Tombol Hapus --}}
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus" style="padding: 2px 8px;">
                                    <i class="fa-solid fa-trash" style="font-size: 0.8rem;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Belum ada produk yang ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
