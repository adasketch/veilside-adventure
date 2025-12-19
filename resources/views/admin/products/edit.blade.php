@extends('layouts.main')

@section('title', 'Edit Produk')

@section('content')
<div class="container" style="padding-top: 120px; padding-bottom: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow" style="border-radius:15px; border:none;">
                <div class="card-header bg-white" style="border-bottom:1px solid #eee;">
                    <h3 style="color:#0c6452; margin:0;">Edit Produk</h3>
                </div>
                <div class="card-body p-4">

                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <div class="mb-3">
                            <label class="form-label fw-bold">Nama Produk</label>
                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Harga Sewa</label>
                            <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3" required>{{ $product->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ganti Gambar (Opsional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                            <div class="mt-2">
                                <img src="{{ asset($product->image) }}" width="100" style="border-radius:5px; border:1px solid #ddd;">
                                <span class="text-muted small ms-2">Gambar saat ini</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-warning text-white fw-bold">Update Produk</button>
                            <a href="{{ route('products.index') }}" class="btn btn-light border">Batal</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
