<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // ==========================================================
    // 1. FRONTEND: PROSES CHECKOUT / SEWA
    // ==========================================================
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string',
            'wa' => 'required|string',
            'start' => 'required|date',
            'days' => 'required|integer|min:1',
            'payment' => 'required|string',
            'cart_json' => 'required',
        ]);

        // 2. Decode JSON Keranjang
        $cartItems = json_decode($request->cart_json, true);

        if (!$cartItems || count($cartItems) < 1) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        // 3. Hitung Total Harga
        $subTotal = 0;
        foreach ($cartItems as $item) {
            $price = intval($item['price']);
            $qty = intval($item['qty']);
            $subTotal += ($price * $qty);
        }

        $lamaSewa = intval($request->days);
        $totalFix = $subTotal * $lamaSewa;

        // 4. Simpan ke Database
        $transaction = Transaction::create([
            'user_id' => Auth::id() ?? null, // Null jika tamu
            'name' => $request->name,
            'whatsapp' => $request->wa,
            'start_date' => $request->start,
            'days' => $lamaSewa,
            'payment_method' => $request->payment,
            'status' => 'pending',
            'items' => $cartItems, // Array otomatis jadi JSON (pastikan di Model ada casts)
            'total_price' => $totalFix
        ]);

        // 5. Redirect ke Halaman Sukses
        return redirect()->route('success', ['id' => $transaction->id]);
    }

    public function success($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('success', compact('transaction'));
    }

    // ==========================================================
    // 2. ADMIN: LIHAT RIWAYAT (Index dengan Search & Filter Lengkap)
    // ==========================================================
    public function index(Request $request)
    {
        // Pastikan admin
        if (Auth::user()->role !== 'admin') abort(403);

        $query = Transaction::query();

        // A. Filter Search (Nama atau WA)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('whatsapp', 'LIKE', "%{$search}%");
            });
        }

        // B. Filter Status
        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        // C. Filter Bulan
        if ($request->filled('filter_month')) {
            $query->whereMonth('start_date', $request->filter_month);
        }

        // D. Filter Tahun
        if ($request->filled('filter_year')) {
            $query->whereYear('start_date', $request->filter_year);
        }

        // Ambil data dengan Pagination + Query String (agar filter tidak hilang saat pindah hal)
        // latest() mengurutkan dari yang terbaru
        $transactions = $query->latest()->paginate(10)->withQueryString();

        return view('admin.transactions.index', compact('transactions'));
    }

    // ==========================================================
    // 3. ADMIN: CETAK LAPORAN (Logic sama dengan Index tapi tanpa Paginate)
    // ==========================================================
    public function printReport(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $query = Transaction::query();

        // Copy Paste Logika Filter dari Index agar hasil cetak sesuai filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('whatsapp', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        if ($request->filled('filter_month')) {
            $query->whereMonth('start_date', $request->filter_month);
        }

        if ($request->filled('filter_year')) {
            $query->whereYear('start_date', $request->filter_year);
        }

        // Gunakan get() untuk mengambil SEMUA data hasil filter (tanpa halaman)
        $transactions = $query->latest()->get();

        return view('admin.transactions.print', compact('transactions'));
    }

    // ==========================================================
    // 4. USER: RIWAYAT SAYA
    // ==========================================================
    public function myHistory()
    {
        $transactions = Transaction::where('user_id', Auth::id())->latest()->get();
        return view('history', compact('transactions'));
    }

    // ==========================================================
    // 5. ADMIN: UPDATE STATUS & HAPUS
    // ==========================================================
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'status' => 'required|in:pending,lunas,selesai,batal'
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->status = $request->status;
        $transaction->save();

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->back()->with('success', 'Data transaksi berhasil dihapus!');
    }
}
