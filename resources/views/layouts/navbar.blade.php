<nav class="navbar navbar-expand-lg fixed-top" style="background-color: #136f63">
    <div class="container-fluid container">
        {{-- LOGO & BRAND --}}
        <a class="navbar-brand d-flex align-items-center text-white" href="{{ route('home') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" width="60" height="54" class="me-2" />
            <span id="brand-text" class="fw-bold">Veilside Adventure</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">

                {{-- === MENU UTAMA (LOGIKA ROLE) === --}}
                @if(Auth::check() && Auth::user()->role === 'admin')
                    {{-- Menu Admin --}}
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.transactions') }}">Riwayat Transaksi</a></li>
                @else
                    {{-- Menu User / Tamu --}}
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('sewa') }}">Produk</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link text-white" href="{{ route('history') }}">Riwayat Saya</a></li>
                    @endauth
                @endif

                {{-- === BAGIAN KANAN: LOGIN / PROFIL === --}}

                @guest
                    {{-- Jika Belum Login --}}
                    <li class="nav-item ms-3">
                        <a href="{{ route('login') }}" class="nav-link text-white fw-bold">Login</a>
                    </li>
                @endguest

                @auth
                    {{-- Jika Sudah Login: FOTO PROFIL & DROPDOWN --}}
                    <li class="nav-item ms-3">
                        <div class="profile-wrapper" onclick="toggleProfileDropdown()">
                            <img src="{{ asset('img/user.png') }}" class="profile-icon" />

                            <div class="profile-dropdown" id="profileDropdown">
                                {{-- Info User --}}
                                <div style="margin-bottom: 20px;">
                                    <p class="user-name">{{ Auth::user()->name }}</p>
                                    <span class="user-role">{{ Auth::user()->role }}</span>
                                </div>

                                <hr style="margin: 15px 0; border-color: #eee;">

                                {{-- CONTAINER TOMBOL MENU (Supaya Sejajar) --}}
                                <div class="menu-container">

                                    {{-- 1. Tombol Dashboard (Khusus Admin) --}}
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="menu-bubble bubble-teal">
                                            <i class="fa-solid fa-gauge-high me-2"></i>Dashboard
                                        </a>
                                    @endif

                                    {{-- 2. Tombol Edit Profil --}}
                                    <a href="{{ route('profile.edit') }}" class="menu-bubble bubble-gray">
                                        <i class="fa-solid fa-user-pen me-2"></i>Edit Profil
                                    </a>

                                    {{-- 3. Tombol Logout (Form dipaksa Full Width) --}}
                                    <form action="{{ route('logout') }}" method="POST" style="width: 100%; margin: 0;">
                                        @csrf
                                        <button type="submit" class="menu-bubble bubble-red">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                @endauth

                {{-- IKON KERANJANG (Hanya di halaman sewa & bukan admin) --}}
                @if(Route::currentRouteName() == 'sewa' && (!Auth::check() || Auth::user()->role !== 'admin'))
                <li class="nav-item ms-3">
                    <div id="cart-toggle" class="cart-btn text-white">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span id="cart-count">0</span>
                    </div>
                </li>
                @endif

            </ul>
        </div>
    </div>
</nav>

{{-- === CSS STYLE === --}}
<style>
    /* Icon Profil Bulat */
    .profile-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid white;
        transition: transform 0.2s;
    }
    .profile-icon:hover { transform: scale(1.05); }

    /* Dropdown Container Utama */
    .profile-dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 65px;
        background: white;
        padding: 25px;
        border-radius: 18px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        min-width: 260px; /* Lebar minimum agar luas */
        text-align: center;
        z-index: 9999;
        border: 1px solid #f1f1f1;
    }

    /* Panah Kecil di Atas Dropdown */
    .profile-dropdown::before {
        content: "";
        position: absolute;
        top: -8px;
        right: 20px;
        width: 16px;
        height: 16px;
        background: white;
        transform: rotate(45deg);
        border-top: 1px solid #f1f1f1;
        border-left: 1px solid #f1f1f1;
    }

    /* Typography User Info */
    .user-name { margin: 0; font-weight: 800; color: #333; font-size: 18px; }
    .user-role {
        font-size: 11px; color: #fff; background: #136f63;
        padding: 4px 10px; border-radius: 12px;
        text-transform: uppercase; letter-spacing: 1px;
    }

    /* Container Tombol Vertikal */
    .menu-container {
        display: flex;
        flex-direction: column;
        gap: 10px; /* Jarak antar tombol */
        width: 100%;
    }

    /* CLASS UTAMA TOMBOL (BUBBLE) */
    .menu-bubble {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100% !important;    /* WAJIB: Paksa lebar penuh */
        padding: 12px 15px;
        border-radius: 50px;       /* Bentuk Kapsul */
        text-decoration: none;
        font-weight: 700;
        font-size: 15px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        box-sizing: border-box;
        margin: 0;
    }

    /* Warna: Dashboard (Teal) */
    .bubble-teal { background-color: #e0f2f1; color: #00695c; }
    .bubble-teal:hover { background-color: #136f63; color: white; }

    /* Warna: Edit Profil (Abu) */
    .bubble-gray { background-color: #f5f5f5; color: #424242; }
    .bubble-gray:hover { background-color: #e0e0e0; color: #000; }

    /* Warna: Logout (Merah) */
    .bubble-red { background-color: #ffebee; color: #c62828; }
    .bubble-red:hover { background-color: #d32f2f; color: white; }
</style>

{{-- === SCRIPT TOGGLE === --}}
<script>
    function toggleProfileDropdown() {
        var d = document.getElementById("profileDropdown");
        // Toggle display antara block dan none
        d.style.display = (d.style.display === "none" || d.style.display === "") ? "block" : "none";
    }

    // Tutup dropdown jika klik di luar area
    document.addEventListener('click', function(e) {
        var wrapper = document.querySelector('.profile-wrapper');
        var dropdown = document.getElementById("profileDropdown");
        if (wrapper && dropdown && !wrapper.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
</script>
