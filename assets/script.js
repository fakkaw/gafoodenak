// assets/script.js

document.addEventListener('DOMContentLoaded', function() {

    // Hamburger menu toggle
    const hamburger = document.getElementById('hamburger-menu');
    const nav = document.getElementById('main-nav');

    if (hamburger && nav) {
        hamburger.addEventListener('click', function() {
            nav.classList.toggle('active');
            hamburger.classList.toggle('active'); // Untuk styling hamburger (misal: jadi 'X')
        });
    }

    // Fungsi untuk update jumlah item di keranjang pada header
    function updateCartCount(count) {
        const cartCountElement = document.getElementById('cart-item-count');
        if (cartCountElement) {
            cartCountElement.textContent = `(${count})`;
        }
    }

    // Event listener untuk tombol kuantitas (+/-)
    document.querySelectorAll('.product-card').forEach(card => {
        const productId = card.querySelector('.add-to-cart-btn').dataset.productId;
        const quantityInput = card.querySelector(`.quantity-input[data-product-id="${productId}"]`);
        const minusBtn = card.querySelector(`.minus-btn[data-product-id="${productId}"]`);
        const plusBtn = card.querySelector(`.plus-btn[data-product-id="${productId}"]`);
        const addToCartBtn = card.querySelector(`.add-to-cart-btn[data-product-id="${productId}"]`);

        minusBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        plusBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
        });

        // Event listener untuk tombol 'Tambah ke Keranjang'
        addToCartBtn.addEventListener('click', function() {
            const quantity = parseInt(quantityInput.value);
            if (quantity < 1) {
                alert('Kuantitas minimal adalah 1.');
                return;
            }

            // Kirim permintaan AJAX
            fetch('cart_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&action=add&quantity=${quantity}`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.cart_total_items);
                    alert(data.message);
                } else {
                    alert('Gagal menambahkan ke keranjang: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghubungi server.');
            });
        });
    });

    // Event listener untuk tombol +/- di halaman keranjang (jika ada)
    document.querySelectorAll('.cart-item-quantity-control').forEach(control => {
        const productId = control.dataset.productId;
        const quantityDisplay = control.querySelector('.cart-quantity-display');
        const minusBtn = control.querySelector('.cart-minus-btn');
        const plusBtn = control.querySelector('.cart-plus-btn');

        minusBtn.addEventListener('click', function() {
            sendCartUpdate(productId, 'remove_one');
        });

        plusBtn.addEventListener('click', function() {
            sendCartUpdate(productId, 'add', 1); // Menambahkan 1 item
        });
    });

    function sendCartUpdate(productId, action, quantity = 0) {
        fetch('cart_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&action=${action}&quantity=${quantity}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload halaman keranjang untuk update tampilan
                window.location.reload(); 
            } else {
                alert('Gagal memperbarui keranjang: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghubungi server.');
        });
    }

});