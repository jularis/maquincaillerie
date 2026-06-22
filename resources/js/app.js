require('./bootstrap');
import Alpine from 'alpinejs';
window.Alpine = Alpine;

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const cartAddUrl = () => document.querySelector('meta[name="cart-add-url"]')?.content ?? '/panier/ajouter';
const cartCountUrl = () => cartAddUrl().replace('/ajouter', '/count');

// Store: cart count (initialized from server on load)
Alpine.store('cart', {
    count: 0,
    init() {
        fetch(cartCountUrl())
            .then(r => r.json())
            .then(d => { this.count = d.count; })
            .catch(() => {});
    },
});

// Store: toast notifications
Alpine.store('toast', {
    show: false,
    message: '',
    type: 'success',
    _timer: null,
    fire(message, type = 'success') {
        this.message = message;
        this.type = type;
        this.show = true;
        clearTimeout(this._timer);
        this._timer = setTimeout(() => { this.show = false; }, 3000);
    },
});

// Component: add to cart (used in product cards and product detail page)
window.addToCart = function(productId, inStock) {
    return {
        loading: false,
        added: false,
        quantity: 1,
        async submit() {
            if (this.loading || this.added || !inStock) return;
            this.loading = true;
            try {
                const resp = await fetch(cartAddUrl(), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf(),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ product_id: productId, quantity: this.quantity }),
                });
                if (!resp.ok) throw new Error('Erreur réseau');
                const data = await resp.json();
                Alpine.store('cart').count = data.count;
                Alpine.store('toast').fire('Produit ajouté au panier !');
                this.added = true;
                setTimeout(() => { this.added = false; }, 2500);
            } catch (e) {
                Alpine.store('toast').fire('Une erreur est survenue, veuillez réessayer.', 'error');
            } finally {
                this.loading = false;
            }
        },
    };
};

Alpine.start();
