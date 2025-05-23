// Filter and Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Price slider styling
    const minPriceSlider = document.getElementById('minPriceSlider');
    const maxPriceSlider = document.getElementById('maxPriceSlider');
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');
    const minPriceLabel = document.getElementById('minPriceLabel');
    const maxPriceLabel = document.getElementById('maxPriceLabel');
    const searchInput = document.getElementById('searchProduct');
    const productGrid = document.getElementById('productGrid');
    const noResults = document.getElementById('noResults');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const sortBySelect = document.getElementById('sortBy');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const ratingCheckboxes = document.querySelectorAll('.rating-checkbox');
    
    const priceGap = 10000;
    
    function formatCurrency(value) {
        return 'Rp' + parseInt(value).toLocaleString('id-ID');
    }
    
    // Initialize price labels
    minPriceLabel.textContent = formatCurrency(minPriceSlider.value);
    maxPriceLabel.textContent = formatCurrency(maxPriceSlider.value);
    
    // Min price slider
    minPriceSlider.addEventListener('input', function() {
        let minVal = parseInt(minPriceSlider.value);
        let maxVal = parseInt(maxPriceSlider.value);
        
        if(maxVal - minVal < priceGap) {
            minVal = maxVal - priceGap;
            minPriceSlider.value = minVal;
        }
        
        minPriceInput.value = minVal;
        minPriceLabel.textContent = formatCurrency(minVal);
    });
    
    // Max price slider
    maxPriceSlider.addEventListener('input', function() {
        let minVal = parseInt(minPriceSlider.value);
        let maxVal = parseInt(maxPriceSlider.value);
        
        if(maxVal - minVal < priceGap) {
            maxVal = minVal + priceGap;
            maxPriceSlider.value = maxVal;
        }
        
        maxPriceInput.value = maxVal;
        maxPriceLabel.textContent = formatCurrency(maxVal);
    });
    
    // Min price input
    minPriceInput.addEventListener('input', function() {
        let minVal = parseInt(minPriceInput.value) || 0;
        let maxVal = parseInt(maxPriceInput.value) || 1000000;
        
        if(minVal < 0) minVal = 0;
        if(minVal > maxVal - priceGap) minVal = maxVal - priceGap;
        
        minPriceSlider.value = minVal;
        minPriceLabel.textContent = formatCurrency(minVal);
    });
    
    // Max price input
    maxPriceInput.addEventListener('input', function() {
        let minVal = parseInt(minPriceInput.value) || 0;
        let maxVal = parseInt(maxPriceInput.value) || 1000000;
        
        if(maxVal > 1000000) maxVal = 1000000;
        if(maxVal < minVal + priceGap) maxVal = minVal + priceGap;
        
        maxPriceSlider.value = maxVal;
        maxPriceLabel.textContent = formatCurrency(maxVal);
    });
    
    // Filter products function
    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const minPrice = parseInt(minPriceSlider.value);
        const maxPrice = parseInt(maxPriceSlider.value);
        const selectedCategories = Array.from(categoryCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        const selectedRatings = Array.from(ratingCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => parseInt(cb.value));
        const sortBy = sortBySelect.value;
        
        const productCards = document.querySelectorAll('.product-card');
        let visibleCount = 0;
        
        productCards.forEach(card => {
            const productName = card.getAttribute('data-name');
            const productPrice = parseInt(card.getAttribute('data-price'));
            const productRating = parseFloat(card.getAttribute('data-rating'));
            const productCategories = card.getAttribute('data-categories').split(',');
            
            // Check if product matches all filters
            const matchesSearch = productName.includes(searchTerm);
            const matchesPrice = productPrice >= minPrice && productPrice <= maxPrice;
            const matchesCategory = selectedCategories.length === 0 || 
                                   productCategories.some(cat => selectedCategories.includes(cat));
            const matchesRating = selectedRatings.length === 0 || 
                                 selectedRatings.some(r => productRating >= r);
            
            if (matchesSearch && matchesPrice && matchesCategory && matchesRating) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0) {
            productGrid.classList.add('hidden');
            noResults.classList.remove('hidden');
        } else {
            productGrid.classList.remove('hidden');
            noResults.classList.add('hidden');
        }
        
        // Sort visible products
        sortProducts(sortBy);
    }
    
    // Sort products function
    function sortProducts(sortBy) {
        const productCards = Array.from(document.querySelectorAll('.product-card:not(.hidden)'));
        
        productCards.sort((a, b) => {
            const aPrice = parseInt(a.getAttribute('data-price'));
            const bPrice = parseInt(b.getAttribute('data-price'));
            const aRating = parseFloat(a.getAttribute('data-rating'));
            const bRating = parseFloat(b.getAttribute('data-rating'));
            const aId = parseInt(a.getAttribute('data-id'));
            const bId = parseInt(b.getAttribute('data-id'));
            
            switch(sortBy) {
                case 'price_low':
                    return aPrice - bPrice;
                case 'price_high':
                    return bPrice - aPrice;
                case 'rating':
                    return bRating - aRating;
                case 'newest':
                    return bId - aId; // Assuming newer products have higher IDs
                default: // popular
                    return 0; // Keep original order
            }
        });
        
        // Reorder elements in the DOM
        const parent = productGrid;
        productCards.forEach(card => {
            parent.appendChild(card);
        });
    }
    
    // Reset all filters
    function resetFilters() {
        searchInput.value = '';
        minPriceSlider.value = 0;
        maxPriceSlider.value = 1000000;
        minPriceInput.value = 0;
        maxPriceInput.value = 1000000;
        minPriceLabel.textContent = formatCurrency(0);
        maxPriceLabel.textContent = formatCurrency(1000000);
        
        categoryCheckboxes.forEach(cb => {
            cb.checked = false;
        });
        
        ratingCheckboxes.forEach(cb => {
            cb.checked = false;
        });
        
        sortBySelect.value = 'popular';
        
        filterProducts();
    }
    
    // Event listeners
    searchInput.addEventListener('input', filterProducts);
    applyFiltersBtn.addEventListener('click', filterProducts);
    resetFiltersBtn.addEventListener('click', resetFilters);
    clearFiltersBtn.addEventListener('click', resetFilters);
    sortBySelect.addEventListener('change', () => sortProducts(sortBySelect.value));
    
    // Add event listeners to all checkboxes
    categoryCheckboxes.forEach(cb => {
        cb.addEventListener('change', filterProducts);
    });
    
    ratingCheckboxes.forEach(cb => {
        cb.addEventListener('change', filterProducts);
    });
    
    // Initial filter
    filterProducts();
});

// Mobile filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileFilterBtn = document.getElementById('mobileFilterBtn');
    const mobileFilterSidebar = document.getElementById('mobileFilterSidebar');
    const closeMobileFilter = document.getElementById('closeMobileFilter');
    
    mobileFilterBtn.addEventListener('click', () => {
        mobileFilterSidebar.classList.remove('hidden');
    });
    
    closeMobileFilter.addEventListener('click', () => {
        mobileFilterSidebar.classList.add('hidden');
    });
    
    // Close mobile filter when clicking outside
    mobileFilterSidebar.addEventListener('click', (e) => {
        if (e.target === mobileFilterSidebar) {
            mobileFilterSidebar.classList.add('hidden');
        }
    });
});

// Notification function
function showNotification(type, title, message) {
    // Remove any existing notifications
    const existingNotification = document.querySelector('.custom-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'custom-notification' + (type === 'error' ? ' error' : '');
    notification.innerHTML = `
        <div class="notification-content">
            <div class="notification-icon ${type}">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            </div>
            <div class="notification-text">
                <h4>${title}</h4>
                <p>${message}</p>
            </div>
        </div>
    `;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Add to cart functionality
function addToCartCard(productId, button) {
    if (!isUserLoggedIn) {
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    }
    // Show loading state
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    const formData = new FormData();
    formData.append('id_product', productId);
    formData.append('quantity', 1);
    fetch(baseUrl + 'cart/add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        button.innerHTML = originalHTML;
        button.disabled = false;
        if (data.success) {
            showNotification('success', 'Berhasil!', 'Produk telah ditambahkan ke keranjang');
        } else {
            showNotification('error', 'Gagal', data.message || 'Terjadi kesalahan saat menambahkan produk ke keranjang');
        }
    })
    .catch(error => {
        button.innerHTML = originalHTML;
        button.disabled = false;
        showNotification('error', 'Oops...', 'Terjadi kesalahan saat menghubungi server');
    });
}

// Wishlist functionality
function toggleWishlist(button, productId) {
    if (!isUserLoggedIn) {
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    }
    
    const icon = button.querySelector('i');
    
    // Toggle wishlist UI
    if (icon.classList.contains('text-red-500')) {
        icon.classList.remove('text-red-500');
        icon.classList.add('animate-heartbeat-out');
        setTimeout(() => {
            icon.classList.remove('animate-heartbeat-out');
        }, 500);
    } else {
        icon.classList.add('text-red-500');
        icon.classList.add('animate-heartbeat');
        setTimeout(() => {
            icon.classList.remove('animate-heartbeat');
        }, 800);
    }
    
    // Send request to server
    fetch(baseUrl + (icon.classList.contains('text-red-500') ? 'wishlist/add' : 'wishlist/remove'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id_product=' + productId
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Wishlist updated:', data);
        // No need to update UI here as we've already done it
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert the UI change if there was an error
        if (icon.classList.contains('text-red-500')) {
            icon.classList.remove('text-red-500');
        } else {
            icon.classList.add('text-red-500');
        }
    });
}

// Modal functionality
function closeLoginPrompt() {
    const modal = document.getElementById('loginPrompt');
    modal.classList.add('hidden');
}

function closeCartNotification() {
    document.getElementById('cartNotification').classList.add('hidden');
}

// Event listeners for modals
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('loginPrompt').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLoginPrompt();
        }
    });
    
    if (document.getElementById('cartNotification')) {
        document.getElementById('cartNotification').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCartNotification();
            }
        });
    }
    
    // Add cart functionality to product cards
    document.querySelectorAll('.product-card .fa-shopping-cart').forEach(function(icon) {
        const button = icon.closest('button');
        if (button) {
            button.onclick = function() {
                addToCartCard(button.closest('.product-card').getAttribute('data-id'), button);
            };
        }
    });
});