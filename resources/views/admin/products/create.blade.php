@extends('layouts.main')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="container" style="padding-top: 120px; padding-bottom: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow" style="border-radius:15px; border:none;">
                <div class="card-header bg-white" style="border-bottom:1px solid #eee;">
                    <h3 style="color:#0c6452; margin:0;">Tambah Produk Baru</h3>
                </div>
                <div class="card-body p-4">

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Produk</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Tenda Dome 4 Orang" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Harga Sewa (per Hari)</label>
                            <input type="number" name="price" class="form-control" placeholder="Contoh: 50000" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Keterangan singkat produk..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Gambar Produk</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Format: jpg, jpeg, png. Maks: 2MB</small>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success py-2 fw-bold" style="background:#0c6452;">Simpan Produk</button>
                            <a href="{{ route('products.index') }}" class="btn btn-light border">Batal</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
