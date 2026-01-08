<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // Cek apakah user adalah admin sebelum akses
    public function __construct()
    {
        // Alternatif middleware sederhana
        if (Auth::check() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }

    // 1. Tampilkan Daftar Produk
    public function index()
    {
        $products = Product::all(); // Ambil semua data dari database
        return view('admin.products.index', compact('products'));
    }

    // 2. Tampilkan Form Tambah
    public function create()
    {
        return view('admin.products.create');
    }

    // 3. Proses Simpan Produk ke Database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $input = $request->all();

        // Proses Upload Gambar
        if ($image = $request->file('image')) {
            $destinationPath = 'img/'; // Folder public/img
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path($destinationPath), $profileImage);

            // Simpan path gambar agar bisa dipanggil asset('img/...')
            $input['image'] = 'img/' . $profileImage;
        }

        Product::create($input);

        return redirect()->route('products.index')
                        ->with('success', 'Produk berhasil ditambahkan!');
    }

    // 4. Hapus Produk
    public function destroy($id)
    {
        $product = Product::find($id);

        // Hapus file gambar dari folder jika ada
        if(File::exists(public_path($product->image))){
            File::delete(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('products.index')
                        ->with('success', 'Produk berhasil dihapus');
    }

    // 5. Tampilkan Form Edit (isi data lama)
    public function edit($id)
    {
        $product = Product::find($id);
        return view('admin.products.edit', compact('product'));
    }

    // 6. Proses Update Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',

            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = Product::find($id);
        $input = $request->all();


        if ($image = $request->file('image')) {
            if(File::exists(public_path($product->image))){
                File::delete(public_path($product->image));
            }

            // Upload gambar baru
            $destinationPath = 'img/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path($destinationPath), $profileImage);
            $input['image'] = 'img/' . $profileImage;
        } else {
            // Jika tidak upload, pakai gambar lama
            unset($input['image']);
        }

        $product->update($input);

        return redirect()->route('products.index')
                        ->with('success', 'Produk berhasil diperbarui!');
    }
    // 7. Tampilkan Produk di Halaman Publik (SEWA)
    public function publicList()
    {
        $products = Product::all();


        if (now()->isThursday()) {
            foreach ($products as $product) {

                $product->original_price = $product->price;

                // Terapkan Diskon (Misal: Potongan 10%)
                // Rumus: Harga * 0.9
                $product->price = $product->price * 0.90;

                $product->has_discount = true;
            }
        }

        return view('sewa', compact('products'));
    }


}
