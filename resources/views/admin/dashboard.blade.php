<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Veilside</title>
    <style>
      body { font-family: Arial; background: #f3fbfb; margin: 0; }
      header { background: #0c6452; padding: 20px; color: white; display: flex; justify-content: space-between; align-items: center; }
      .admin-box { padding: 30px; }
      .card { background: white; padding: 20px; margin: 15px 0; border-radius: 10px; box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1); }
      button { padding: 10px 15px; background: #0c6452; color: white; border: none; border-radius: 5px; cursor: pointer; }
      .btn-danger { background: #d9534f; }
    </style>
</head>
<body>
    <header>
      <h2>Admin Panel ({{ Auth::user()->name }})</h2>

      <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn-danger">Logout</button>
      </form>
    </header>

    <div class="admin-box">
      <div class="card">
        <h3>Kelola Produk</h3>
        <p>Tambah, edit, atau hapus produk penyewaan.</p>
        <button onclick="location.href='{{ route('products.index') }}'">Masuk</button>
      </div>

      <div class="card">
        <h3>Riwayat Transaksi</h3>
        <p>Lihat data penyewaan yang masuk.</p>
        <button onclick="location.href='{{ route('admin.transactions') }}'">Lihat</button>
      </div>
    </div>
</body>
</html>
