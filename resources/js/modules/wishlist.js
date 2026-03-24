// Wishlist functionality
(function () {
	function getWishlist() {
		return JSON.parse(localStorage.getItem('theme:wishlist') || '[]');
	}

	function saveWishlist(wishlist) {
		localStorage.setItem('theme:wishlist', JSON.stringify(wishlist));
	}

	function updateButtonStates() {
		const wishlist = getWishlist();
		const wishlistButtons = document.querySelectorAll('.wishlist-btn, .product-card__wishlist');
		const wishlistDot = document.querySelector('.wishlist-dot');

		wishlistButtons.forEach(function (btn) {
			const productId = btn.getAttribute('data-product-id');
			btn.classList.toggle('active', wishlist.includes(productId));
		});

		if (wishlistDot) {
			wishlistDot.classList.toggle('is-visible', wishlist.length > 0);
		}
		
		// FIX Bug 1: Gestisci correttamente la visibilità dei bubble
		document.querySelectorAll('.wishlist-count-bubble').forEach(function(bubble) {
			if (wishlist.length > 0) {
				bubble.textContent = wishlist.length;
				bubble.style.display = '';
			} else {
				bubble.style.display = 'none';
			}
		});
	}

	function initWishlistButtons() {
		document.querySelectorAll('.wishlist-btn, .product-card__wishlist').forEach(function (btn) {
			if (btn.dataset.wishlistInit) return;
			btn.dataset.wishlistInit = 'true';

			btn.addEventListener('click', function () {
				let wishlist = getWishlist();
				const productId = this.getAttribute('data-product-id');

				if (wishlist.includes(productId)) {
					wishlist = wishlist.filter((item) => item !== productId);
					this.classList.remove('active');
				} else {
					wishlist.unshift(productId);
					this.classList.add('active');
				}

				saveWishlist(wishlist);
				updateButtonStates();
				
				// FIX Bug 2: Ricarica i prodotti nella pagina wishlist
				const wishlistProductsElement = document.querySelector('wishlist-products');
				if (wishlistProductsElement) {
					wishlistProductsElement.loadProducts();
				}
			});
		});

		updateButtonStates();
	}

	// Wishlist page custom element
	if (!window.customElements.get('wishlist-products')) {
		class WishlistProducts extends HTMLElement {
			constructor() {
				super();
				this.loadProducts();
			}

			buildQuery() {
				const items = getWishlist();
				return items
					.slice(0, this.productLimit)
					.map((id) => `id:${id}`)
					.join(' OR ');
			}

			async loadProducts() {
				const query = this.buildQuery();
				if (!query) {
					// Se la wishlist è vuota, svuota il contenuto
					this.innerHTML = '';
					return;
				}

				try {
					const response = await fetch(
						`${theme.routes.root_url}search?q=${query}&resources[limit]=${this.productLimit}&resources[type]=product&section_id=${this.sectionId}`,
					);
					const html = await response.text();
					const products = new DOMParser()
						.parseFromString(html, 'text/html')
						.querySelector('wishlist-products');
					if (products && products.hasChildNodes()) {
						this.innerHTML = products.innerHTML;
						initWishlistButtons();
					}
				} catch (error) {
					console.error('Error fetching wishlist products:', error);
				}
			}

			get sectionId() {
				return this.getAttribute('section-id');
			}

			get productLimit() {
				return parseInt(this.getAttribute('products-limit')) || 12;
			}
		}

		window.customElements.define('wishlist-products', WishlistProducts);
	}

	// Initialize on DOM ready
	document.addEventListener('DOMContentLoaded', initWishlistButtons);

	// Expose for dynamic content
	window.initWishlistButtons = initWishlistButtons;
})();