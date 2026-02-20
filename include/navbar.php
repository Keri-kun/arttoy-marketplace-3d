<!-- Header -->
<header class="main-header">
    <div class="header-content">
        <a href="dashboard.php" class="logo">
            <i class="fas fa-robot"></i>
            Art Toy Gallery
        </a>
        <div class="user-menu">
            <div class="user-info">
                <span>สวัสดี, <?php echo $_SESSION['username'] ?? 'Admin'; ?></span>
            </div>
            <button class="cart-btn" onclick="toggleCart()">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-badge" id="cartBadge">0</span>
            </button>
            <a href="logout.php" class="logout-btn" style="text-decoration: none;">
                <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
            </a>
        </div>
    </div>
</header>

<!-- Main Wrapper -->
<div class="main-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h3>🎨 Art Toy</h3>
            <p>ระบบจัดการร้านของเล่นศิลปะ</p>
        </div>
        
        <nav>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo ($title == 'หน้าแรก') ? 'active' : '' ?>">
                        <i class="fas fa-home"></i>
                        <span>หน้าแรก</span>
                    </a>
                </li>
                
                <div class="nav-header">จัดการสินค้า</div>
                
                <li class="nav-item">
                    <a href="manage_category.php" class="nav-link <?php echo ($title == 'หมวดหมู่ Art Toy') ? 'active' : '' ?>">
                        <i class="fas fa-tags"></i>
                        <span>หมวดหมู่ Art Toy</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="manage_product.php" class="nav-link <?php echo ($title == 'คอลเลกชัน') ? 'active' : '' ?>">
                        <i class="fas fa-robot"></i>
                        <span>คอลเลกชัน</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="orders.php" class="nav-link <?php echo ($title == 'คำสั่งซื้อ') ? 'active' : '' ?>">
                        <i class="fas fa-shopping-bag"></i>
                        <span>คำสั่งซื้อ</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="show.php" class="nav-link <?php echo ($title == 'ที่ต้องจัดส่ง') ? 'active' : '' ?>">
                        <i class="fas fa-truck"></i>
                        <span>ที่ต้องจัดส่ง</span>
                    </a>
                </li>
                
                <div class="nav-header">รายงาน</div>
                
                <li class="nav-item">
                    <a href="analytics.php" class="nav-link <?php echo ($title == 'สถิติการขาย') ? 'active' : '' ?>">
                        <i class="fas fa-chart-bar"></i>
                        <span>สถิติการขาย</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

<!-- Cart Modal -->
<div class="modal-overlay" id="cartModal">
    <div class="cart-modal">
        <div class="cart-header">
            <div class="cart-title">
                <i class="fas fa-shopping-cart"></i>
                ตะกร้าสินค้า
            </div>
            <button class="close-btn" onclick="toggleCart()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="cart-body" id="cartItems">
            <!-- Cart items will be populated here -->
        </div>
        
        <div class="cart-footer" id="cartFooter">
            <div class="cart-total">
                <span>ยอดรวม:</span>
                <span class="total-amount" id="totalAmount">฿0</span>
            </div>
            <button class="checkout-btn" onclick="checkout()">
                <i class="fas fa-credit-card"></i> สั่งซื้อสินค้า
            </button>
        </div>
    </div>
</div>

<style>
    /* Main Layout Styles */
    .main-wrapper {
        display: flex;
        max-width: 1400px;
        margin: 2rem auto;
        gap: 2rem;
        padding: 0 2rem;
    }

    /* Sidebar Styles */
    .sidebar {
        width: 280px;
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        padding: 2rem;
        height: fit-content;
        position: sticky;
        top: 120px;
    }

    .sidebar-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-color);
    }

    .sidebar-header h3 {
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .sidebar-header p {
        color: #7f8c8d;
        font-size: 0.9rem;
    }

    .nav-menu {
        list-style: none;
    }

    .nav-item {
        margin-bottom: 0.5rem;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-radius: 15px;
        text-decoration: none;
        color: var(--dark-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: var(--gradient-bg);
        transition: left 0.3s ease;
        z-index: -1;
    }

    .nav-link:hover::before,
    .nav-link.active::before {
        left: 0;
    }

    .nav-link:hover,
    .nav-link.active {
        color: white;
        transform: translateX(10px);
    }

    .nav-link i {
        margin-right: 1rem;
        font-size: 1.2rem;
        width: 20px;
    }

    .nav-header {
        color: #7f8c8d;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        margin: 2rem 0 1rem 0;
        padding-left: 1rem;
    }

    /* Main Content Styles */
    .main-content {
        flex: 1;
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        padding: 2rem;
        animation: slideIn 0.6s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-wrapper {
            flex-direction: column;
            padding: 1rem;
        }

        .sidebar {
            width: 100%;
            position: static;
        }

        .header-content {
            padding: 0 1rem;
        }
    }
</style>