@extends('layouts.main')

@section('title', 'Veilside Adventure - Beranda')

@push('styles')
<style>
    /* Tambahan CSS khusus agar sesuai dengan index.html asli Anda */
    .hero {
        /* Override background dari style.css agar path-nya benar di Laravel */
        background: url("{{ asset('img/A group of people hiking in the mountains (1).jpeg') }}") center/cover no-repeat !important;
        position: relative;
    }

    /* Memastikan navbar fix tidak menutupi konten (jika belum ada di global css) */
    body { padding-top: 0; }

    /* Feedback Profile Image Fix */
    .feedback-card .profile img {
        width: 55px; height: 55px; border-radius: 50%; object-fit: cover;
    }

    /* Warna border kiri card feedback */
    .feedback-card.pink { border-left: 8px solid #d95fa3; }
    .feedback-card.green { border-left: 8px solid #4cb86a; }
    .feedback-card.yellow { border-left: 8px solid #f5b400; }
</style>
@endpush

@section('content')

<section class="hero" id="home">
    <main class="content">
        <h1>Teman Setia Petualanganmu !</h1>
        <br />
        <h4>
            Veilside Adventure menyediakan berbagai perlengkapan outdoor terbaik
            untuk mendukung kegiatan camping, hiking, dan petualangan alam kamu.
        </h4>
        <br />
        <a href="{{ route('sewa') }}" class="btn-primaryy">Sewa Sekarang</a>
    </main>
</section>

<section class="about" id="about">
    <div class="container">
        <h2><span>About</span> Us</h2>

        <div class="row">
            <div class="about-img">
                <img src="{{ asset('img/Logi_kotakOrange.png') }}" alt="About Us" />
            </div>
            <div class="content">
                <p>
                    Veilside Adventure hadir untuk mendukung para pecinta alam dalam
                    menjelajahi keindahan alam Indonesia tanpa perlu khawatir tentang
                    perlengkapan. Kami menyediakan layanan sewa perlengkapan outdoor
                    yang lengkap, berkualitas, dan terjangkau. Dengan sistem sewa yang
                    mudah serta pelayanan yang ramah, kami berkomitmen menjadi partner
                    terbaik dalam setiap perjalanan petualanganmu.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="layanan bg-light" id="layanan">
    <div class="container">
        <h2><span>Layanan</span> Kami</h2>
        <div class="grid">
            <div class="card">
                <img src="{{ asset('img/rent-removebg-preview__1_-removebg-preview.png') }}" />
                <h3>Penyewaan Perlengkapan Outdoor</h3>
                <p>
                    Nikmati kemudahan menyewa berbagai perlengkapan Outdoor seperti
                    tenda, kompor portable, sleeping bag, carrier, dan banyak lagi
                    alat outdoor lainnya. semua dalam kondisi bersih dan siap pakai
                </p>
            </div>
            <div class="card">
                <img src="{{ asset('img/Ilustrasi Lencana Petualangan Outdoor Gunung Vintage, Pendakian, Gunung, Lereng PNG dan Vektor dengan Background Transparan untuk Unduh Gratis.jfif') }}" />
                <h3>Antar-Jemput dan Guide Pendakian</h3>
                <p>
                    Siap antar-jemput ke base camp pendakian Gunung Lawu, Bukit
                    Mongkrang, Bukit Kendhil, dan Bukit sekitar Tawangmanggu. Melayani
                    Guide Pendakian dan Porter Pendakian, siap menemani pendakian anda
                    dan mendokumentasikan selama perjalanan.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="feedback" id="feedback">
    <div class="container">
        <h2><span>Apa Kata</span> Mereka?</h2>

        <div class="feedback-grid">
            <div class="feedback-card pink">
                <div class="profile">
                    <img src="https://i.pravatar.cc/80?img=12" alt="User" />
                    <div>
                        <h3>Hamam Paruk</h3>
                        <p>Pendaki</p>
                    </div>
                </div>
                <p class="text">
                    Rekomendasi tempat sewa outdoor perbatasan Ngawi Magetan
                </p>
                <div class="stars">★★★★★</div>
            </div>

            <div class="feedback-card green">
                <div class="profile">
                    <img src="https://images.pexels.com/photos/9163/night.jpg" alt="User" />
                    <div>
                        <h3>Much Panca</h3>
                        <p>Traveller</p>
                    </div>
                </div>
                <p class="text">
                    Rekomend buat para hiking atau trekking, Rental items are all available
                </p>
                <div class="stars">★★★★★</div>
            </div>

            <div class="feedback-card yellow">
                <div class="profile">
                    <img src="https://i.pravatar.cc/80?img=45" alt="User" />
                    <div>
                        <h3>Alicia John</h3>
                        <p>Hiker</p>
                    </div>
                </div>
                <p class="text">
                    Perjalanan terorganisir dengan baik, aman, dan sangat seru!
                    Rekomendasi banget.
                </p>
                <div class="stars">★★★★★</div>
            </div>
        </div>
    </div>
</section>

<section id="kontak" class="kontak">
    <div class="container">
        <h2><span>Hubungi</span> Kami</h2>
        <p>Untuk pemesanan atau pertanyaan, hubungi kami melalui WhatsApp:</p>
        <a href="https://wa.me/6281459125873" target="_blank" class="btn-primer">Chat via WhatsApp</a>
        <p class="small">
            Atau kunjungi kami di Veilside Adventure Store, Kendal-Ngawi.
        </p>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // 🔔 Logic Pengingat (Reminder) H-1
    const reminder = JSON.parse(localStorage.getItem("vs_reminder") || "null");

    if (reminder) {
        const now = new Date();
        const due = new Date(reminder.tanggalSelesai);
        const oneDayBefore = new Date(due);
        oneDayBefore.setDate(due.getDate() - 1);

        // Jika hari ini adalah H-1
        if (now.toDateString() === oneDayBefore.toDateString()) {
            const msg = `Halo ${reminder.nama}, ini pengingat dari Veilside Adventure 😊\nBesok (${due.toLocaleDateString("id-ID")}) adalah jadwal pengembalian barang sewaan Anda.\nTerima kasih sudah menyewa di Veilside Adventure!`;
            const waURL = `https://wa.me/${reminder.wa}?text=${encodeURIComponent(msg)}`;

            alert("🔔 Pengingat: Besok adalah jadwal pengembalian barang!");
            window.open(waURL, "_blank");

            // Hapus reminder agar tidak muncul berulang kali
            localStorage.removeItem("vs_reminder");
        }
    }

    // Logic Navbar Profile Toggle (jika belum dihandle layout)
    document.addEventListener("DOMContentLoaded", () => {
        // Cek login via sessionStorage (karena kita pakai simulasi frontend)
        const userData = sessionStorage.getItem("currentUser");
        const loginNav = document.getElementById("loginNav");
        const profileNav = document.getElementById("profileNav");

        if (userData) {
            const user = JSON.parse(userData);
            if(loginNav) loginNav.classList.add("hidden");
            if(profileNav) {
                profileNav.classList.remove("hidden");
                const usernameEl = document.getElementById("profileUsername");
                if(usernameEl) usernameEl.innerText = user.username;
            }
        } else {
            if(loginNav) loginNav.classList.remove("hidden");
            if(profileNav) profileNav.classList.add("hidden");
        }

        // Toggle dropdown klik icon
        document.getElementById("profileIcon")?.addEventListener("click", (e) => {
            e.stopPropagation(); // Mencegah event bubbling
            document.querySelector(".profile-wrapper").classList.toggle("active");
        });

        // Klik di luar untuk menutup dropdown
        document.addEventListener("click", () => {
            document.querySelector(".profile-wrapper")?.classList.remove("active");
        });

        // Logout
        document.getElementById("logoutBtn")?.addEventListener("click", () => {
            sessionStorage.removeItem("currentUser");
            window.location.href = "{{ route('login') }}";
        });
    });
</script>
@endpush
