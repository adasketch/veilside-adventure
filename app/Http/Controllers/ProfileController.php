<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // 1. Tampilkan Form Edit Profil
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    // 2. Proses Update Data
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            // VALIDASI USERNAME:
            // - Wajib diisi
            // - Harus unik di tabel users, KECUALI milik user yang sedang login ($user->id)
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Update Nama & Username
        $user->name = $request->name;
        $user->username = $request->username; // <--- Menyimpan Username Baru

        // Update Password (Hanya jika kolom diisi)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        /** @var \App\Models\User $user */
        $user->save();

        return redirect()->back()->with('success', 'Profil (Username/Password) berhasil diperbarui!');
    }
}
