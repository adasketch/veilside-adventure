@extends('layouts.main')

@section('title', 'Edit Profil')

@push('styles')
<style>
    body { background: #f8faf9; }
    .form-container {
        max-width: 550px; margin: 40px auto; background: #fff; padding: 40px;
        border-radius: 14px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    h2 { text-align: center; color: #136f63; margin-bottom: 30px; font-weight: bold; }
    label { font-weight: 600; margin-top: 15px; display: block; color: #333; margin-bottom: 8px; }
    input[type="text"], input[type="password"] {
        width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;
        font-size: 15px; display: block; box-sizing: border-box; margin-bottom: 5px;
    }
    input:focus { outline: none; border-color: #136f63; box-shadow: 0 0 5px rgba(19, 111, 99, 0.2); }
    .btn-save {
        display: block; width: 100%; background: #136f63; color: #fff; border: none;
        border-radius: 8px; padding: 14px; font-size: 16px; font-weight: bold; cursor: pointer;
        margin-top: 30px; transition: 0.3s; text-align: center;
    }
    .btn-save:hover { background: #0b4d47; }
    .btn-cancel {
        display: block; width: 100%; background: #fff; color: #666; border: 1px solid #ddd;
        border-radius: 8px; padding: 12px; font-size: 15px; font-weight: 600; cursor: pointer;
        margin-top: 10px; text-align: center; text-decoration: none; transition: 0.3s;
    }
    .btn-cancel:hover { background: #f1f1f1; color: #333; }
</style>
@endpush

@section('content')
<div class="container" style="padding-top: 100px; padding-bottom: 50px;">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="max-width: 550px; margin: 0 auto 20px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tampilkan Error Validasi (Misal: Username sudah dipakai) --}}
    @if ($errors->any())
        <div class="alert alert-danger" style="max-width: 550px; margin: 0 auto 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <h2>Edit Profil Admin</h2>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- 1. Edit Nama (Display Name) --}}
            <label for="name">Nama Lengkap</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}" required>

            {{-- 2. Edit Username (Login ID) --}}
            <label for="username">Username (Login)</label>
            <input type="text" name="username" id="username" value="{{ $user->username ?? '' }}" required>

            {{-- 3. Edit Password --}}
            <label for="password">Password Baru</label>
            <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin mengganti">

            <label for="password_confirmation">Ulangi Password Baru</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ketik ulang password baru">

            <button type="submit" class="btn-save">Simpan Perubahan</button>
            <a href="{{ route('admin.dashboard') }}" class="btn-cancel">Batal</a>
        </form>
    </div>
</div>
@endsection
