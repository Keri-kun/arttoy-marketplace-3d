<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 5 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.2/js/dataTables.bootstrap5.js"></script>

<!-- Art Toy Cart System -->
<script>
    // Cart functionality
    let cart = JSON.parse(localStorage.getItem('artToyCart')) || {};

    // Update cart badge
    function updateCartBadge() {
        const totalItems = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
        const badge = document.getElementById('cartBadge');
        if (badge) {
            badge.textContent = totalItems;
            badge.style.display = totalItems === 0 ? 'none' : 'flex';
        }
        
        // Save to localStorage
        localStorage.setItem('artToyCart', JSON.stringify(cart));
    }

    // Toggle cart modal
    function toggleCart() {
        const modal = document.getElementById('cartModal');
        if (modal) {
            modal.classList.toggle('active');
            updateCartDisplay();
        }
    }

    // Add item to cart
    function addToCart(id, name, price, emoji) {
        if (cart[id]) {
            cart[id].quantity += 1;
        } else {
            cart[id] = { id, name, price, emoji, quantity: 1 };
        }
        
        updateCartBadge();
        
        // Show success animation
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> เพิ่มแล้ว!';
        btn.style.background = '#27ae60';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.style.background = '';
            btn.disabled = false;
        }, 1500);

        // Show notification
        showNotification('เพิ่มสินค้าลงตะกร้าเรียบร้อย! 🛒', 'success');
    }

    // Update quantity
    function updateQuantity(id, change) {
        if (cart[id]) {
            cart[id].quantity += change;
            
            if (cart[id].quantity <= 0) {
                delete cart[id];
            }
            
            updateCartBadge();
            updateCartDisplay();
        }
    }

    // Remove item from cart
    function removeFromCart(id) {
        if (confirm('ต้องการลบสินค้านี้จากตะกร้าหรือไม่?')) {
            delete cart[id];
            updateCartBadge();
            updateCartDisplay();
            showNotification('ลบสินค้าจากตะกร้าเรียบร้อย', 'info');
        }
    }

    // Update cart display
    function updateCartDisplay() {
        const cartItems = document.getElementById('cartItems');
        const totalAmount = document.getElementById('totalAmount');
        const cartFooter = document.getElementById('cartFooter');
        
        if (!cartItems) return;
        
        // Clear current items
        cartItems.innerHTML = '';
        
        if (Object.keys(cart).length === 0) {
            cartItems.innerHTML = `
                <div class="cart-empty">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>ตะกร้าสินค้าว่างเปล่า</h3>
                    <p>เลือกสินค้าที่ต้องการแล้วเพิ่มลงตะกร้า</p>
                </div>
            `;
            if (cartFooter) cartFooter.style.display = 'none';
            return;
        }
        
        if (cartFooter) cartFooter.style.display = 'block';
        let total = 0;
        
        Object.values(cart).forEach(item => {
            total += item.price * item.quantity;
            
            const cartItem = document.createElement('div');
            cartItem.className = 'cart-item';
            cartItem.innerHTML = `
                <div class="item-image">${item.emoji}</div>
                <div class="item-details">
                    <div class="item-name">${item.name}</div>
                    <div class="item-price">฿${item.price.toLocaleString()}</div>
                </div>
                <div class="quantity-controls">
                    <button class="qty-btn" onclick="updateQuantity('${item.id}', -1)" ${item.quantity <= 1 ? 'disabled' : ''}>
                        <i class="fas fa-minus"></i>
                    </button>
                    <span class="quantity">${item.quantity}</span>
                    <button class="qty-btn" onclick="updateQuantity('${item.id}', 1)">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="remove-btn" onclick="removeFromCart('${item.id}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            
            cartItems.appendChild(cartItem);
        });
        
        if (totalAmount) {
            totalAmount.textContent = `฿${total.toLocaleString()}`;
        }
    }

    // Checkout function
    function checkout() {
        if (Object.keys(cart).length === 0) {
            showNotification('ตะกร้าสินค้าว่างเปล่า!', 'warning');
            return;
        }
        
        const total = Object.values(cart).reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const itemCount = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
        
        if (confirm(`ยืนยันการสั่งซื้อ?\n\nจำนวนสินค้า: ${itemCount} ชิ้น\nยอดรวม: ฿${total.toLocaleString()}`)) {
            // Send order to server (you can implement this)
            // Example: submitOrder(cart, total);
            
            showNotification('🎉 สั่งซื้อเรียบร้อย! ขอบคุณที่ใช้บริการ', 'success');
            cart = {};
            updateCartBadge();
            updateCartDisplay();
            toggleCart();
        }
    }

    // Show notification
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 3000);
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCartBadge();
        
        // Close modal when clicking outside
        const cartModal = document.getElementById('cartModal');
        if (cartModal) {
            cartModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    toggleCart();
                }
            });
        }
        
        // Initialize DataTables if table exists
        if (typeof $.fn.DataTable !== 'undefined') {
            $('.data-table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"
                },
                "responsive": true,
                "pageLength": 10,
                "order": [[ 0, "desc" ]]
            });
        }
        
        // Add hover effects to cards
        const cards = document.querySelectorAll('.toy-card, .stat-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add click effects to buttons
        const buttons = document.querySelectorAll('.btn, .add-btn, .add-to-cart-btn');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
    });

    // Navigation functions
    function showPage(page) {
        console.log('Navigating to:', page);
        // You can add page navigation logic here
    }

    function addNewToy() {
        showNotification('🎨 เปิดหน้าเพิ่ม Art Toy ใหม่!', 'info');
    }

    // Utility functions
    function formatCurrency(amount) {
        return new Intl.NumberFormat('th-TH', {
            style: 'currency',
            currency: 'THB'
        }).format(amount);
    }

    function formatDate(date) {
        return new Intl.DateTimeFormat('th-TH', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(new Date(date));
    }
</script>

<!-- Notification Styles -->
<style>
    .notification {
        position: fixed;
        top: 100px;
        right: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.3s ease-out;
        border-left: 4px solid var(--primary-color);
    }

    .notification-success {
        border-left-color: #27ae60;
    }

    .notification-warning {
        border-left-color: #f39c12;
    }

    .notification-info {
        border-left-color: var(--accent-color);
    }

    .notification-content {
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification-content button {
        background: none;
        border: none;
        color: #7f8c8d;
        cursor: pointer;
        padding: 0.2rem;
        border-radius: 3px;
        transition: all 0.3s ease;
    }

    .notification-content button:hover {
        background: #ecf0f1;
        color: var(--dark-color);
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Additional component styles */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-color);
    }

    .page-title {
        color: var(--dark-color);
        font-size: 2rem;
        font-weight: 600;
    }

    .add-btn {
        background: var(--gradient-bg);
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .add-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        color: white;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 2rem;
        border-radius: 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .stat-card h3 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }

    .stat-card p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    /* Art Toy Gallery */
    .toy-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .toy-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
    }

    .toy-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    }

    .toy-image {
        width: 100%;
        height: 150px;
        background: linear-gradient(45deg, var(--accent-color), var(--primary-color));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
    }

    .toy-info {
        padding: 1rem;
    }

    .toy-name {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--dark-color);
    }

    .toy-price {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .add-to-cart-btn {
        width: 100%;
        background: var(--gradient-bg);
        color: white;
        border: none;
        padding: 0.7rem;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        color: var(--dark-color);
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 0.8rem;
        border: 2px solid var(--light-color);
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }

    /* Table Styles */
    .table-responsive {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: var(--gradient-bg);
        color: white;
        border: none;
        padding: 1rem;
        font-weight: 600;
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--light-color);
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    /* Button Styles */
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background: #e55a30;
        transform: translateY(-2px);
    }

    .btn-success {
        background: #27ae60;
        color: white;
    }

    .btn-success:hover {
        background: #229954;
        transform: translateY(-2px);
    }

    .btn-warning {
        background: #f39c12;
        color: white;
    }

    .btn-warning:hover {
        background: #e67e22;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }

    .btn-sm {
        padding: 0.3rem 0.7rem;
        font-size: 0.9rem;
    }
</style>