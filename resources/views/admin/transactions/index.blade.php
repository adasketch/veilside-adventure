@extends('layouts.main')

@section('title', 'Riwayat Transaksi')

@push('styles')
<style>
    /* FIX LAYOUT & NAVBAR */
    html, body { height: 100%; margin: 0; }
    body { display: flex; flex-direction: column; background-color: #f8faf9; }
    .main-content { flex: 1 0 auto; margin-top: 90px; padding-bottom: 40px; width: 100%; }

    /* TABEL STYLE */
    .table-compact th { font-size: 0.75rem; padding: 12px 10px; text-transform: uppercase; letter-spacing: 0.5px; background-color: #1d3b36; color: white; white-space: nowrap; }
    .table-compact td { font-size: 0.85rem; padding: 8px 10px; vertical-align: middle; }
    .compact-list { margin: 0; padding: 0; list-style: none; font-size: 0.8rem; }
    .date-compact { line-height: 1.4; font-size: 0.8rem; }

    /* PAGINATION */
    .pagination { margin-bottom: 0; }
    .pagination .page-item.active .page-link { background-color: #0c6452; border-color: #0c6452; color: white; }
    .pagination .page-link { color: #0c6452; border-radius: 5px; margin: 0 2px; }
    .pagination .page-link:hover { background-color: #e0f2f1; }

    @media (max-width: 768px) {
        .main-content { margin-top: 80px; padding-left: 15px; padding-right: 15px; }
    }
</style>
@endpush

@section('content')
<div class="container main-content">

    {{-- HEADER HALAMAN (UPDATED: Tombol Kembali Dihapus) --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold" style="color:#0c6452; margin: 0;">Riwayat Transaksi</h4>
            <small class="text-muted">Kelola data penyewaan, cari, filter, dan cetak.</small>
        </div>
        <div>
            {{-- Tombol Cetak Laporan (Sendirian sekarang) --}}
            <a href="{{ route('transactions.print', request()->query()) }}" target="_blank" class="btn btn-success btn-sm px-3 shadow-sm d-flex align-items-center">
                <i class="fa-solid fa-print me-2"></i> Cetak Laporan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 mb-3 shadow-sm" role="alert" style="font-size: 0.9rem;">
            <i class="fa-solid fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 10px;"></button>
        </div>
    @endif

    {{-- CARD FILTER & SEARCH --}}
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 10px;">
        <div class="card-body p-3">
            <form action="{{ route('admin.transactions') }}" method="GET">
                <div class="row g-2">

                    {{-- 1. Search Text --}}
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama atau No. WA..." value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- 2. Filter Status --}}
                    <div class="col-md-2 col-6">
                        <select name="filter_status" class="form-select form-select-sm" style="cursor: pointer;">
                            <option value="">- Status -</option>
                            <option value="pending" {{ request('filter_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="lunas" {{ request('filter_status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="selesai" {{ request('filter_status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ request('filter_status') == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>

                    {{-- 3. Filter Bulan --}}
                    <div class="col-md-2 col-6">
                        <select name="filter_month" class="form-select form-select-sm" style="cursor: pointer;">
                            <option value="">- Bulan -</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ request('filter_month') == $m ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4. Filter Tahun --}}
                    <div class="col-md-2 col-6">
                        <select name="filter_year" class="form-select form-select-sm" style="cursor: pointer;">
                            <option value="">- Tahun -</option>
                            @for($y = 2024; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ request('filter_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- 5. Tombol Action --}}
                    <div class="col-md-2 col-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100" style="background-color: #0c6452; border:none;">Filter</button>
                        @if(request()->hasAny(['search', 'filter_status', 'filter_month', 'filter_year']))
                            <a href="{{ route('admin.transactions') }}" class="btn btn-light btn-sm border" title="Reset"><i class="fa-solid fa-rotate-left"></i></a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- CARD TABEL --}}
    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover table-compact mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th width="20%">Penyewa</th>
                        <th width="15%">Kontak</th>
                        <th width="20%">Waktu Sewa</th>
                        <th width="20%">Barang</th>
                        <th width="10%">Total</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="5%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($transactions as $index => $t)
                    <tr>
                        <td class="text-center text-secondary fw-bold">{{ $transactions->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $t->name }}</div>
                            <span class="badge bg-light text-secondary border px-2 py-1 mt-1" style="font-size: 0.65rem;">{{ strtoupper($t->payment_method) }}</span>
                        </td>
                        <td>
                            <a href="https://wa.me/{{ $t->whatsapp }}" target="_blank" class="text-success fw-bold text-decoration-none" style="font-size: 0.85rem;">
                                <i class="fa-brands fa-whatsapp me-1"></i> {{ $t->whatsapp }}
                            </a>
                        </td>
                        <td>
                            <div class="date-compact">
                                <div class="text-muted" style="font-size: 0.75rem;">Mulai: <span class="text-dark fw-bold">{{ $t->start_date->format('d M Y') }}</span></div>
                                <div class="text-muted" style="font-size: 0.75rem;">Selesai: <span class="text-danger fw-bold">{{ $t->start_date->copy()->addDays($t->days)->format('d M Y') }}</span></div>
                            </div>
                        </td>
                        <td>
                            <ul class="compact-list">
                                @if(is_array($t->items))
                                    @foreach ($t->items as $item)
                                        <li class="mb-1"><span class="text-muted">•</span> {{ $item['name'] }} <span class="fw-bold text-dark ms-1" style="font-size: 0.75rem;">x{{ $item['qty'] }}</span></li>
                                    @endforeach
                                @endif
                            </ul>
                        </td>
                        <td class="fw-bold text-success" style="font-size: 0.9rem;">Rp {{ number_format($t->total_price, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <form action="{{ route('transactions.updateStatus', $t->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm fw-bold text-white border-0 shadow-sm"
                                    style="border-radius: 50px; text-align:center; cursor: pointer; font-size: 0.7rem; padding: 4px 8px; width: 100%; min-width: 90px;
                                    background-color: {{ $t->status == 'pending' ? '#ffc107' : ($t->status == 'lunas' ? '#198754' : ($t->status == 'selesai' ? '#0d6efd' : '#dc3545')) }};">
                                    <option value="pending" class="bg-white text-dark" {{ $t->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                                    <option value="lunas" class="bg-white text-dark" {{ $t->status == 'lunas' ? 'selected' : '' }}>LUNAS</option>
                                    <option value="selesai" class="bg-white text-dark" {{ $t->status == 'selesai' ? 'selected' : '' }}>SELESAI</option>
                                    <option value="batal" class="bg-white text-dark" {{ $t->status == 'batal' ? 'selected' : '' }}>BATAL</option>
                                </select>
                            </form>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('transactions.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-light text-danger btn-sm border shadow-sm" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-magnifying-glass fa-2x mb-3 text-secondary opacity-50"></i>
                            <p class="mb-0 fw-bold">Data tidak ditemukan.</p>
                            <small>Coba sesuaikan filter bulan/tahun atau kata kunci pencarian.</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="card-footer bg-white d-flex justify-content-end py-3">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
