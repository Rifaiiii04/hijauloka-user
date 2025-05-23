// Filter and Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Price range sliders
    const minPriceSlider = document.getElementById('minPriceSlider');
    const maxPriceSlider = document.getElementById('maxPriceSlider');
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');
    const minPriceLabel = document.getElementById('minPriceLabel');
    const maxPriceLabel = document.getElementById('maxPriceLabel');
    
    // Category and rating checkboxes
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const ratingCheckboxes = document.querySelectorAll('.rating-checkbox');
    
    // Sort by select
    const sortBySelect = document.getElementById('sortBy');
    
    // Filter buttons
    const applyFiltersBtn = document.getElementById('applyFilters');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    
    // Search input
    const searchInput = document.getElementById('searchProduct');
    const searchSuggestions = document.getElementById('searchSuggestions');
    const productGrid = document.getElementById('productGrid');
    const noResults = document.getElementById('noResults');
    
    // Pagination variables
    const productsPerPage = 15;
    let currentPage = 1;
    let totalPages = 1;
    let filteredProducts = [];
    
    // Pagination elements
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');
    const pageNumbersContainer = document.getElementById('pageNumbers');
    const paginationControls = document.getElementById('paginationControls');
    
    // Format price as Rupiah
    function formatRupiah(price) {
        return 'Rp' + new Intl.NumberFormat('id-ID').format(price);
    }
    
    // Update min price slider and input
    minPriceSlider.addEventListener('input', function() {
        const minVal = parseInt(this.value);
        const maxVal = parseInt(maxPriceSlider.value);
        
        if (minVal > maxVal) {
            this.value = maxVal;
            minPriceInput.value = maxVal;
            minPriceLabel.textContent = formatRupiah(maxVal);
        } else {
            minPriceInput.value = minVal;
            minPriceLabel.textContent = formatRupiah(minVal);
        }
    });
    
    // Update max price slider and input
    maxPriceSlider.addEventListener('input', function() {
        const maxVal = parseInt(this.value);
        const minVal = parseInt(minPriceSlider.value);
        
        if (maxVal < minVal) {
            this.value = minVal;
            maxPriceInput.value = minVal;
            maxPriceLabel.textContent = formatRupiah(minVal);
        } else {
            maxPriceInput.value = maxVal;
            maxPriceLabel.textContent = formatRupiah(maxVal);
        }
    });
    
    // Update min price slider from input
    minPriceInput.addEventListener('change', function() {
        const minVal = parseInt(this.value);
        const maxVal = parseInt(maxPriceSlider.value);
        
        if (minVal > maxVal) {
            this.value = maxVal;
            minPriceSlider.value = maxVal;
            minPriceLabel.textContent = formatRupiah(maxVal);
        } else {
            minPriceSlider.value = minVal;
            minPriceLabel.textContent = formatRupiah(minVal);
        }
    });
    
    // Update max price slider from input
    maxPriceInput.addEventListener('change', function() {
        const maxVal = parseInt(this.value);
        const minVal = parseInt(minPriceSlider.value);
        
        if (maxVal < minVal) {
            this.value = minVal;
            maxPriceSlider.value = minVal;
            maxPriceLabel.textContent = formatRupiah(minVal);
        } else {
            maxPriceSlider.value = maxVal;
            maxPriceLabel.textContent = formatRupiah(maxVal);
        }
    });
    
    // Enhanced search with debounce
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = this.value.toLowerCase().trim();
        
        // Clear suggestions if search term is empty
        if (searchTerm === '') {
            searchSuggestions.innerHTML = '';
            searchSuggestions.classList.add('hidden');
            filterProducts();
            return;
        }
        
        // Debounce search to improve performance
        searchTimeout = setTimeout(() => {
            // Get all product names for suggestions
            const productCards = document.querySelectorAll('.product-card');
            const productNames = new Set();
            
            productCards.forEach(card => {
                const name = card.getAttribute('data-name');
                if (name.includes(searchTerm)) {
                    productNames.add(name);
                }
            });
            
            // Display suggestions
            if (productNames.size > 0) {
                searchSuggestions.innerHTML = '';
                Array.from(productNames).slice(0, 5).forEach(name => {
                    const suggestion = document.createElement('div');
                    suggestion.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer';
                    suggestion.textContent = name.charAt(0).toUpperCase() + name.slice(1);
                    suggestion.addEventListener('click', () => {
                        searchInput.value = name;
                        searchSuggestions.classList.add('hidden');
                        filterProducts();
                    });
                    searchSuggestions.appendChild(suggestion);
                });
                searchSuggestions.classList.remove('hidden');
            } else {
                searchSuggestions.innerHTML = '';
                searchSuggestions.classList.add('hidden');
            }
            
            filterProducts();
        }, 300);
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
            searchSuggestions.classList.add('hidden');
        }
    });
    
    // Filter products function
    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase().trim();
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
        filteredProducts = [];
        
        // First, filter all products based on criteria
        productCards.forEach(card => {
            const productName = card.getAttribute('data-name');
            const productPrice = parseInt(card.getAttribute('data-price'));
            const productRating = parseFloat(card.getAttribute('data-rating'));
            const productCategories = card.getAttribute('data-categories').split(',');
            
            // Improved search matching - fuzzy search
            let matchesSearch = true;
            if (searchTerm) {
                // Split search term into words for better matching
                const searchWords = searchTerm.split(/\s+/);
                matchesSearch = searchWords.every(word => 
                    productName.includes(word) || 
                    // Check for similar words (simple fuzzy search)
                    productName.split(/\s+/).some(namePart => 
                        levenshteinDistance(namePart, word) <= 2
                    )
                );
            }
            
            const matchesPrice = productPrice >= minPrice && productPrice <= maxPrice;
            const matchesCategory = selectedCategories.length === 0 || 
                                   productCategories.some(cat => selectedCategories.includes(cat));
            const matchesRating = selectedRatings.length === 0 || 
                                 selectedRatings.some(r => productRating >= r);
            
            if (matchesSearch && matchesPrice && matchesCategory && matchesRating) {
                filteredProducts.push(card);
            }
            
            // Hide all products initially
            card.classList.add('hidden');
        });
        
        // Sort filtered products
        sortFilteredProducts(sortBy);
        
        // Calculate total pages
        totalPages = Math.ceil(filteredProducts.length / productsPerPage);
        
        // Reset to first page when filters change
        currentPage = 1;
        
        // Show/hide no results message
        if (filteredProducts.length === 0) {
            productGrid.classList.add('hidden');
            noResults.classList.remove('hidden');
            paginationControls.classList.add('hidden');
        } else {
            productGrid.classList.remove('hidden');
            noResults.classList.add('hidden');
            paginationControls.classList.remove('hidden');
            
            // Display current page
            displayCurrentPage();
        }
        
        // Update pagination controls
        updatePaginationControls();
    }
    
    // Simple Levenshtein distance function for fuzzy matching
    function levenshteinDistance(a, b) {
        if (a.length === 0) return b.length;
        if (b.length === 0) return a.length;
        
        const matrix = [];
        
        // Initialize matrix
        for (let i = 0; i <= b.length; i++) {
            matrix[i] = [i];
        }
        
        for (let j = 0; j <= a.length; j++) {
            matrix[0][j] = j;
        }
        
        // Fill matrix
        for (let i = 1; i <= b.length; i++) {
            for (let j = 1; j <= a.length; j++) {
                if (b.charAt(i-1) === a.charAt(j-1)) {
                    matrix[i][j] = matrix[i-1][j-1];
                } else {
                    matrix[i][j] = Math.min(
                        matrix[i-1][j-1] + 1, // substitution
                        matrix[i][j-1] + 1,   // insertion
                        matrix[i-1][j] + 1    // deletion
                    );
                }
            }
        }
        
        return matrix[b.length][a.length];
    }
    
    // Sort filtered products
    function sortFilteredProducts(sortBy) {
        filteredProducts.sort((a, b) => {
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
    }
    
    // Display current page of products
    function displayCurrentPage() {
        // Calculate start and end indices for current page
        const startIndex = (currentPage - 1) * productsPerPage;
        const endIndex = Math.min(startIndex + productsPerPage, filteredProducts.length);
        
        // Hide all products
        document.querySelectorAll('.product-card').forEach(card => {
            card.classList.add('hidden');
        });
        
        // Show only products for current page
        for (let i = startIndex; i < endIndex; i++) {
            filteredProducts[i].classList.remove('hidden');
        }
        
        // Scroll to top of product grid
        productGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    // Update pagination controls
    function updatePaginationControls() {
        // Update prev/next buttons
        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages;
        
        // Generate page numbers
        pageNumbersContainer.innerHTML = '';
        
        // Determine range of page numbers to show
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        
        // Adjust start page if end page is maxed out
        if (endPage === totalPages) {
            startPage = Math.max(1, endPage - 4);
        }
        
        // Add first page button if not in range
        if (startPage > 1) {
            addPageButton(1);
            if (startPage > 2) {
                addEllipsis();
            }
        }
        
        // Add page number buttons
        for (let i = startPage; i <= endPage; i++) {
            addPageButton(i);
        }
        
        // Add last page button if not in range
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                addEllipsis();
            }
            addPageButton(totalPages);
        }
    }
    
    // Add page number button
    function addPageButton(pageNum) {
        const button = document.createElement('button');
        button.textContent = pageNum;
        button.className = 'w-10 h-10 flex items-center justify-center rounded-md mx-1 ' + 
                          (pageNum === currentPage ? 
                           'bg-green-600 text-white' : 
                           'bg-gray-200 text-gray-700 hover:bg-gray-300');
        
        button.addEventListener('click', () => {
            if (pageNum !== currentPage) {
                currentPage = pageNum;
                displayCurrentPage();
                updatePaginationControls();
            }
        });
        
        pageNumbersContainer.appendChild(button);
    }
    
    // Add ellipsis
    function addEllipsis() {
        const ellipsis = document.createElement('span');
        ellipsis.textContent = '...';
        ellipsis.className = 'w-10 h-10 flex items-center justify-center text-gray-500';
        pageNumbersContainer.appendChild(ellipsis);
    }
    
    // Event listeners for pagination
    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            displayCurrentPage();
            updatePaginationControls();
        }
    });
    
    nextPageBtn.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            displayCurrentPage();
            updatePaginationControls();
        }
    });
    
    // Apply filters button
    applyFiltersBtn.addEventListener('click', filterProducts);
    
    // Reset filters button
    resetFiltersBtn.addEventListener('click', function() {
        // Reset price range
        minPriceSlider.value = 0;
        maxPriceSlider.value = 1000000;
        minPriceInput.value = 0;
        maxPriceInput.value = 1000000;
        minPriceLabel.textContent = 'Rp0';
        maxPriceLabel.textContent = 'Rp1.000.000';
        
        // Reset category checkboxes
        categoryCheckboxes.forEach(cb => {
            cb.checked = false;
        });
        
        // Reset rating checkboxes
        ratingCheckboxes.forEach(cb => {
            cb.checked = false;
        });
        
        // Reset sort by
        sortBySelect.value = 'popular';
        
        // Reset search
        searchInput.value = '';
        
        // Apply filters
        filterProducts();
    });
    
    // Clear filters button (in no results message)
    clearFiltersBtn.addEventListener('click', function() {
        resetFiltersBtn.click();
    });
    
    // Initial filter and pagination
    filterProducts();
});

// Mobile filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileFilterBtn = document.getElementById('mobileFilterBtn');
    const mobileFilterSidebar = document.getElementById('mobileFilterSidebar');
    const closeMobileFilter = document.getElementById('closeMobileFilter');
    
    mobileFilterBtn.addEventListener('click', () => {
        mobileFilterSidebar.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    });
    
    closeMobileFilter.addEventListener('click', () => {
        mobileFilterSidebar.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
    });
    
    // Close mobile filter when clicking outside
    mobileFilterSidebar.addEventListener('click', (e) => {
        if (e.target === mobileFilterSidebar) {
            mobileFilterSidebar.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        }
    });
});

// Wishlist functionality
function toggleWishlist(button, productId) {
    // Check if user is logged in
    if (!isUserLoggedIn) {
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    }
    
    // Toggle active class
    button.classList.toggle('active');
    const icon = button.querySelector('i');
    
    if (button.classList.contains('active')) {
        // Add to wishlist
        icon.classList.add('text-red-500');
        icon.classList.add('animate-heartbeat');
        setTimeout(() => {
            icon.classList.remove('animate-heartbeat');
        }, 800);
        
        // Send AJAX request to add to wishlist
        fetch(baseUrl + 'wishlist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id_product=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showNotification('Berhasil ditambahkan ke wishlist', 'success');
            } else {
                showNotification(data.message || 'Gagal menambahkan ke wishlist', 'error');
                button.classList.remove('active');
                icon.classList.remove('text-red-500');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
            button.classList.remove('active');
            icon.classList.remove('text-red-500');
        });
    } else {
        // Remove from wishlist
        icon.classList.remove('text-red-500');
        icon.classList.add('animate-heartbeat-out');
        setTimeout(() => {
            icon.classList.remove('animate-heartbeat-out');
        }, 500);
        
        // Send AJAX request to remove from wishlist
        fetch(baseUrl + 'wishlist/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id_product=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showNotification('Berhasil dihapus dari wishlist', 'success');
            } else {
                showNotification(data.message || 'Gagal menghapus dari wishlist', 'error');
                button.classList.add('active');
                icon.classList.add('text-red-500');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
            button.classList.add('active');
            icon.classList.add('text-red-500');
        });
    }
}

// Add to cart functionality
function addToCartCard(productId, button) {
    // Check if user is logged in
    if (!isUserLoggedIn) {
        document.getElementById('loginPrompt').classList.remove('hidden');
        return;
    }
    
    // Animate button
    button.classList.add('animate-pulse');
    
    // Send AJAX request to add to cart
    fetch(baseUrl + 'cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id_product=' + productId + '&quantity=1'
    })
    .then(response => response.json())
    .then(data => {
        button.classList.remove('animate-pulse');
        
        if (data.status === 'success') {
            // Show cart notification
            document.getElementById('cartNotification').classList.remove('hidden');
            
            // Update cart count in header
            const cartCountElement = document.getElementById('cartCount');
            if (cartCountElement) {
                cartCountElement.textContent = data.cart_count || '0';
                cartCountElement.classList.remove('hidden');
            }
        } else {
            showNotification(data.message || 'Gagal menambahkan ke keranjang', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.classList.remove('animate-pulse');
        showNotification('Terjadi kesalahan', 'error');
    });
}

// Close login prompt
function closeLoginPrompt() {
    document.getElementById('loginPrompt').classList.add('hidden');
}

// Close cart notification
function closeCartNotification() {
    document.getElementById('cartNotification').classList.add('hidden');
}

// Show notification
function showNotification(message, type = 'success') {
    // Create notification element if it doesn't exist
    let notification = document.querySelector('.custom-notification');
    if (!notification) {
        notification = document.createElement('div');
        notification.className = 'custom-notification';
        document.body.appendChild(notification);
    }
    
    // Set notification content
    notification.innerHTML = `
        <div class="notification-content">
            <div class="notification-icon ${type}">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            </div>
            <div class="notification-text">
                <h4>${type === 'success' ? 'Berhasil' : 'Gagal'}</h4>
                <p>${message}</p>
            </div>
        </div>
    `;
    
    // Add type class
    notification.className = 'custom-notification';
    if (type === 'error') {
        notification.classList.add('error');
    }
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Hide notification after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}