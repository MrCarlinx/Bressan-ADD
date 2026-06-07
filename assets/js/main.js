// assets/js/main.js
// Funcionalidades globais do site Toca do Coelho

function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

function cartPost(params) {
    const body = new URLSearchParams(params);
    body.set('csrf_token', getCsrfToken());
    return fetch(window.SITE_URL + '/ajax/cart_actions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
    }).then(res => res.json());
}

document.addEventListener('DOMContentLoaded', () => {

    function updateCartBadge() {
        cartPost({ action: 'get_cart' })
            .then(data => {
                if (data.success) {
                    const cart = data.cart || {};
                    let total = 0;
                    for (const id in cart) {
                        total += cart[id];
                    }
                    document.querySelectorAll('[data-cart-count]').forEach(el => {
                        el.textContent = total;
                        el.style.display = total > 0 ? 'flex' : 'none';
                    });
                }
            });
    }

    document.querySelectorAll('[data-add-cart]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.addCart;

            cartPost({ action: 'add', id: id, qty: 1 })
            .then(data => {
                if (data.success) {
                    updateCartBadge();
                    btn.textContent = '✓ Adicionado!';
                    setTimeout(() => btn.textContent = btn.dataset.label || 'Adicionar', 1500);
                }
            });
        });
    });

    updateCartBadge();

    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            document.querySelectorAll('[data-product-card]').forEach(card => {
                const text = card.dataset.productCard.toLowerCase();
                card.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

});
