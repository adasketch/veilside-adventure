<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // 1. Simpan Transaksi (Dari Form Sewa)
    public function store(Request $request)
    {
        // 1. Validasi Input dari Form
        $request->validate([
            'name' => 'required|string',
            'wa' => 'required|string',
            'start' => 'required|date',
            'days' => 'required|integer|min:1',
            'payment' => 'required|string',
            'cart_json' => 'required', // Data barang dari hidden input
        ]);

        // 2. Decode JSON Keranjang
        // Mengubah string JSON dari form menjadi Array PHP
        $cartItems = json_decode($request->cart_json, true);

        if (!$cartItems || count($cartItems) < 1) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        // 3. Hitung Total Harga Real-time
        // Rumus: (Harga Barang * Jumlah Barang) * Lama Sewa
        $subTotal = 0;
        foreach ($cartItems as $item) {
            $price = intval($item['price']); // Pastikan harga integer
            $qty = intval($item['qty']);
            $subTotal += ($price * $qty);
        }

        $lamaSewa = intval($request->days);
        $totalFix = $subTotal * $lamaSewa;

        // 4. Simpan ke Database
        $transaction = Transaction::create([
            'user_id' => Auth::id() ?? null, // Null jika user tamu
            'name' => $request->name,
            'whatsapp' => $request->wa,
            'start_date' => $request->start,
            'days' => $lamaSewa,
            'payment_method' => $request->payment,
            'status' => 'pending',
            'items' => $cartItems, // Laravel otomatis mengubah array ini jadi JSON berkat $casts di Model
            'total_price' => $totalFix
        ]);

        // 5. Redirect ke Success membawa ID Transaksi
        return redirect()->route('success', ['id' => $transaction->id]);
    }

    public function success($id)
    {
        
        $transaction = Transaction::findOrFail($id);

        return view('success', compact('transaction'));
    }
    // 2. ADMIN: Lihat Semua Riwayat
    public function index()
    {
        // Pastikan admin
        if (Auth::user()->role !== 'admin') abort(403);

        $transactions = Transaction::latest()->get();
        return view('admin.transactions.index', compact('transactions'));
    }

    // 3. USER: Lihat Riwayat Sendiri
    public function myHistory()
    {
        $transactions = Transaction::where('user_id', Auth::id())->latest()->get();
        return view('history', compact('transactions'));
    }

    // 4. Update Status Transaksi (Admin)
    public function updateStatus(Request $request, $id)
    {
        // Pastikan hanya admin
        if (Auth::user()->role !== 'admin') abort(403);

        $request->validate([
            'status' => 'required|in:pending,lunas,selesai,batal'
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->status = $request->status;
        $transaction->save();

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui!');
    }

    // 5. Hapus Transaksi (Admin)
    public function destroy($id)
    {
        // Pastikan hanya admin
        if (Auth::user()->role !== 'admin') abort(403);

        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->back()->with('success', 'Data transaksi berhasil dihapus!');
    }
}
