/* public/js/sewa.js */

// KUNCI PENYIMPANAN KERANJANG (SINKRONKAN DENGAN FORM)
const CART_KEY = "vs_cart";

document.addEventListener("DOMContentLoaded", () => {
    const listContainer = document.getElementById("product-list");

    // 1. Ambil Data Produk (Dari window.productsFromDB Blade)
    const products = window.productsFromDB || [];

    // 2. Render Produk ke HTML
    if (listContainer && products.length > 0) {
        listContainer.innerHTML = products.map((item, index) => {

            // LOGIKA TAMPILAN HARGA (DISKON VS NORMAL)
            let priceHTML = '';

            if (item.has_discount) {
                // Tampilan Diskon (Ada Coret)
                priceHTML = `
                    <div style="margin: 10px 0;">
                        <span style="text-decoration: line-through; color: #888; font-size: 0.9em;">
                            Rp ${parseInt(item.original_price).toLocaleString("id-ID")}
                        </span>
                        <div style="font-weight:bold; color:#d9534f; font-size: 1.1em; display:flex; align-items:center; gap:5px;">
                            Rp ${parseInt(item.price).toLocaleString("id-ID")}/hari
                            <span style="font-size:0.7em; background:#ffebee; color:#d9534f; padding:2px 6px; border-radius:4px;">-10%</span>
                        </div>
                    </div>
                `;
            } else {
                // Tampilan Normal
                priceHTML = `
                    <p style="font-weight:bold; color:#136f63; margin:10px 0;">
                       Rp ${parseInt(item.price).toLocaleString("id-ID")}/hari
                    </p>
                `;
            }

            return `
              <div class="card">
                <div style="position:relative;">
                    <img src="${item.img}" alt="${item.name}" class="product-img">
                    ${item.has_discount ? '<span style="position:absolute; top:10px; right:10px; background:#d9534f; color:white; font-size:0.7em; padding:4px 8px; border-radius:20px; font-weight:bold;">PROMO</span>' : ''}
                </div>
                <h3>${item.name}</h3>
                <p>${item.desc || ''}</p>

                ${priceHTML}

                <button onclick="addToCart(${index})" class="btn-primary" style="width:100%">
                    Tambah ke Keranjang
                </button>
              </div>
            `;
        }).join("");
    }

    // 3. Update Angka di Navbar saat loading
    updateCartCount();

    // 4. Handle Tombol Keranjang di Navbar (Toggle Panel)
    const cartToggle = document.getElementById("cart-toggle");
    const cartPanel = document.getElementById("cart-panel");
    const cartClose = document.getElementById("cart-close");
    const backdrop = document.getElementById("cart-backdrop");

    if (cartToggle) {
        cartToggle.addEventListener("click", () => {
            renderCartPanel(); // Render ulang saat dibuka
            cartPanel.classList.add("open");
            if(backdrop) backdrop.classList.add("show");
        });
    }

    if (cartClose) {
        cartClose.addEventListener("click", () => {
            cartPanel.classList.remove("open");
            if(backdrop) backdrop.classList.remove("show");
        });
    }

    if(backdrop) {
        backdrop.addEventListener("click", () => {
            cartPanel.classList.remove("open");
            backdrop.classList.remove("show");
        });
    }
});

// === FUNGSI LOGIKA KERANJANG ===

function getCart() {
    const raw = localStorage.getItem(CART_KEY);
    return JSON.parse(raw || "[]");
}

function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
    updateCartCount();
}

function addToCart(index) {
    const products = window.productsFromDB || [];
    const item = products[index];
    if (!item) return;

    let cart = getCart();

    // Cek apakah barang sudah ada di keranjang
    const existingItem = cart.find((c) => c.name === item.name);

    if (existingItem) {
        existingItem.qty += 1; // Jika ada, tambah qty

        // Update harga di cart jika ada perubahan harga (misal dari normal ke diskon)
        existingItem.price = item.price;
    } else {
        // Jika baru, masukkan object baru (harga otomatis mengikuti harga saat ini/diskon)
        cart.push({
            name: item.name,
            price: item.price,
            img: item.img,
            qty: 1
        });
    }

    saveCart(cart);
    renderCartPanel(); // Update tampilan panel

    // Buka panel otomatis agar user tahu barang masuk
    const cartPanel = document.getElementById("cart-panel");
    const backdrop = document.getElementById("cart-backdrop");
    if(cartPanel) cartPanel.classList.add("open");
    if(backdrop) backdrop.classList.add("show");
}

function updateCartCount() {
    const cart = getCart();
    const countSpan = document.getElementById("cart-count");
    if (countSpan) {
        // Hitung total item (bukan total jenis barang)
        const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
        countSpan.innerText = totalQty;

        // Sembunyikan badge jika 0
        countSpan.style.display = totalQty > 0 ? 'inline-block' : 'none';
    }
}

function renderCartPanel() {
    const cartItemsContainer = document.getElementById("cart-items");
    const cartTotalEl = document.getElementById("cart-total");
    if (!cartItemsContainer) return;

    const cart = getCart();

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = "<p style='text-align:center; margin-top:20px; color:#666;'>Keranjang kosong.</p>";
        if(cartTotalEl) cartTotalEl.innerText = "Rp 0";
        return;
    }

    let total = 0;
    cartItemsContainer.innerHTML = cart.map((item, idx) => {
        total += item.price * item.qty;

        return `
          <div class="cart-item" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; border-bottom:1px solid #eee; padding-bottom:10px;">
            <div style="display:flex; align-items:center; gap:10px;">
                <img src="${item.img}" style="width:40px; height:40px; object-fit:cover; border-radius:4px;">
                <div>
                    <div style="font-weight:600; font-size:0.9em;">${item.name}</div>
                    <div style="font-size:0.85em; color:#136f63;">
                        Rp ${parseInt(item.price).toLocaleString("id-ID")} x ${item.qty}
                    </div>
                </div>
            </div>
            <div style="display:flex; gap:5px; align-items:center;">
                <button onclick="changeQty(${idx}, -1)" style="width:25px; height:25px; border:1px solid #ccc; background:#fff; cursor:pointer;">-</button>
                <span style="font-size:0.9em; min-width:15px; text-align:center;">${item.qty}</span>
                <button onclick="changeQty(${idx}, 1)" style="width:25px; height:25px; border:1px solid #ccc; background:#fff; cursor:pointer;">+</button>
                <button onclick="removeItem(${idx})" style="margin-left:5px; background:none; border:none; color:#d9534f; cursor:pointer;">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
          </div>
        `;
    }).join("");

    if(cartTotalEl) cartTotalEl.innerText = "Rp " + total.toLocaleString("id-ID");
}

function changeQty(index, change) {
    let cart = getCart();
    if (cart[index]) {
        cart[index].qty += change;
        if (cart[index].qty <= 0) {
            cart.splice(index, 1); // Hapus jika 0
        }
        saveCart(cart);
        renderCartPanel();
    }
}

function removeItem(index) {
    let cart = getCart();
    cart.splice(index, 1);
    saveCart(cart);
    renderCartPanel();
}
