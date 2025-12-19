<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Veilside Adventure')</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" />

    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        /* Fix agar konten tidak tertutup navbar fixed */
        body { padding-top: 75px; }
        /* Khusus home page yang punya hero besar, padding top direset jika perlu,
           tapi di CSS asli Anda hero sudah punya padding besar */
    </style>
    @stack('styles')
</head>
<body>

    @include('layouts.navbar')

    @yield('content')

    <footer>
        <div class="container">
            <p>© 2025 Veilside Adventure. Anda Nyaman Kami Senang.</p>
        </div>
    </footer>

    <script>
        // Variabel global agar JS tahu lokasi aset gambar
        const BASE_URL = "{{ url('/') }}";
    </script>
    @stack('scripts')
</body>
</html>
