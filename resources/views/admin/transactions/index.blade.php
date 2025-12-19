@extends('layouts.main')

@section('title', 'Riwayat Transaksi')

@push('styles')
<style>
    /* === 1. LAYOUT UTAMA === */
    html, body { height: 100%; }
    body { display: flex; flex-direction: column; background-color: #f8faf9; }
    .main-content { flex: 1 0 auto; padding-top: 110px; padding-bottom: 40px; width: 100%; }
    footer { flex-shrink: 0; margin-top: auto; }

    /* === 2. TABEL COMPACT === */
    .table-compact th {
        font-size: 0.75rem;
        padding: 10px 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background-color: #1d3b36;
        color: white;
    }

    .table-compact td {
        font-size: 0.85rem;
        padding: 6px 8px;
        vertical-align: middle;
    }

    .compact-list {
        margin: 0;
        padding: 0;
        list-style: none;
        font-size: 0.8rem;
    }

    .date-compact {
        line-height: 1.3; /* Sedikit dilonggarkan agar teks bulan terbaca jelas */
        font-size: 0.8rem;
    }
</style>
@endpush

@section('content')
<div class="container main-content">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold" style="color:#0c6452; margin: 0;">Riwayat Transaksi</h4>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3" style="font-size: 0.85rem;">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 mb-3 shadow-sm" role="alert" style="font-size: 0.85rem;">
            <i class="fa-solid fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 10px;"></button>
        </div>
    @endif

    {{-- Card Tabel --}}
    <div class="card shadow-sm border-0" style="border-radius: 8px;">
        <div class="table-responsive">
            <table class="table table-hover table-compact mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="4%">No</th>
                        <th width="18%">Penyewa</th>
                        <th width="12%">Kontak</th>
                        <th width="20%">Waktu Sewa</th>
                        <th>Barang</th>
                        <th width="12%">Total</th>
                        <th width="12%" class="text-center">Status</th>
                        <th width="5%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($transactions as $index => $t)
                    <tr>
                        <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>

                        {{-- Nama & Payment --}}
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $t->name }}</div>
                            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.65rem;">
                                {{ strtoupper($t->payment_method) }}
                            </span>
                        </td>

                        {{-- Kontak WA --}}
                        <td>
                            <a href="https://wa.me/{{ $t->whatsapp }}" target="_blank" class="text-success fw-bold text-decoration-none" style="font-size: 0.8rem;">
                                <i class="fa-brands fa-whatsapp me-1"></i> {{ $t->whatsapp }}
                            </a>
                        </td>

                        {{-- Waktu Sewa (Format Hari Bulan Tahun) --}}
                        <td>
                            <div class="date-compact">
                                <div class="text-muted" style="font-size: 0.75rem;">Mulai: <span class="text-dark fw-bold">{{ $t->start_date->format('d M Y') }}</span></div>
                                <div class="text-muted" style="font-size: 0.75rem;">Selesai: <span class="text-danger fw-bold">{{ $t->start_date->copy()->addDays($t->days)->format('d M Y') }}</span></div>
                                <span class="badge bg-info text-dark mt-1 border-0" style="font-size: 0.65rem; padding: 2px 6px;">{{ $t->days }} Hari</span>
                            </div>
                        </td>

                        {{-- List Barang --}}
                        <td>
                            <ul class="compact-list">
                                @if(is_array($t->items))
                                    @foreach ($t->items as $item)
                                        <li>
                                            <span class="text-muted">•</span> {{ $item['name'] }}
                                            <span class="fw-bold" style="font-size: 0.75rem;">x{{ $item['qty'] }}</span>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </td>

                        {{-- Total Harga --}}
                        <td class="fw-bold text-success" style="font-size: 0.85rem;">
                            Rp {{ number_format($t->total_price, 0, ',', '.') }}
                        </td>

                        {{-- Status Dropdown --}}
                        <td class="text-center">
                            <form action="{{ route('transactions.updateStatus', $t->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                    class="form-select form-select-sm fw-bold text-white border-0 shadow-sm"
                                    style="border-radius: 50px; text-align:center; cursor: pointer; font-size: 0.7rem; padding: 2px 8px; height: 28px; line-height: 1; width: 100%;
                                    background-color:
                                        {{ $t->status == 'pending' ? '#ffc107' :
                                          ($t->status == 'lunas' ? '#198754' :
                                          ($t->status == 'selesai' ? '#0d6efd' : '#dc3545')) }};">
                                    <option value="pending" class="bg-white text-dark" {{ $t->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                                    <option value="lunas" class="bg-white text-dark" {{ $t->status == 'lunas' ? 'selected' : '' }}>LUNAS</option>
                                    <option value="selesai" class="bg-white text-dark" {{ $t->status == 'selesai' ? 'selected' : '' }}>SELESAI</option>
                                    <option value="batal" class="bg-white text-dark" {{ $t->status == 'batal' ? 'selected' : '' }}>BATAL</option>
                                </select>
                            </form>
                        </td>

                        {{-- Aksi Hapus --}}
                        <td class="text-center">
                            <form action="{{ route('transactions.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light text-danger btn-sm border" title="Hapus" style="padding: 2px 8px; font-size: 0.8rem;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted" style="font-size: 0.9rem;">
                            Belum ada data transaksi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
