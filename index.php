<?php

include './database.php';

$db = new Database();

$result = $db->read("SELECT * FROM product AS p INNER JOIN category AS c ON p.CategoryID = c.CategoryID;");

$data = [];

foreach ($result as $key => $row) {
    $data[$row->CategoryName][] = $row;
}

?>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('คุณยังไม่ได้เข้าสู่ระบบ'); window.location.href='login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ArtToy Paradise - ร้านของเล่นศิลปะ</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    
    <!-- AdminLTE -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    
    <!-- Custom Art Toy Styles -->
    <style>
        :root {
            --primary-color: #FF6B9D;
            --secondary-color: #4ECDC4;
            --accent-color: #FFE66D;
            --dark-color: #2C3E50;
            --light-bg: #F8F9FA;
            --card-shadow: 0 8px 25px rgba(0,0,0,0.1);
            --hover-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        body {
            font-family: 'Kanit', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .content-wrapper {
            background: transparent;
        }

        /* Navbar Styling */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-bottom: 3px solid var(--primary-color);
        }

        .navbar-brand {
            font-family: 'Fredoka', cursive;
            font-weight: 600;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .brand-text {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        /* Content Header */
        .content-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            margin: 20px;
            padding: 20px;
        }

        .content-header h1 {
            font-family: 'Fredoka', cursive;
            color: white;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin: 0;
        }

        /* Category Headers */
        .category-header {
            font-family: 'Fredoka', cursive;
            color: white;
            font-size: 1.8rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin: 30px 0 20px 0;
            position: relative;
        }

        .category-header::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(45deg, var(--accent-color), var(--primary-color));
            border-radius: 2px;
        }

        /* Product Cards */
        .product-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.4s ease;
            overflow: hidden;
            position: relative;
            margin-bottom: 25px;
        }

        .product-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--hover-shadow);
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color), var(--accent-color));
        }

        .product-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.1);
        }

        .card-body {
            padding: 20px !important;
        }

        .card-title {
            font-family: 'Fredoka', cursive;
            font-weight: 500;
            color: var(--dark-color);
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .card-text {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.2rem;
        }

        /* Buttons */
        .btn-cart {
            background: linear-gradient(45deg, var(--primary-color), #FF8FB3);
            border: none;
            color: white;
            font-weight: 500;
            border-radius: 10px;
            padding: 10px;
            transition: all 0.3s ease;
            margin-bottom: 5px;
        }

        .btn-cart:hover {
            background: linear-gradient(45deg, #FF8FB3, var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 157, 0.4);
            color: white;
        }

        .btn-3d {
            background: linear-gradient(45deg, var(--secondary-color), #6FE7DD);
            border: none;
            color: white;
            font-weight: 500;
            border-radius: 10px;
            padding: 10px;
            transition: all 0.3s ease;
        }

        .btn-3d:hover {
            background: linear-gradient(45deg, #6FE7DD, var(--secondary-color));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 205, 196, 0.4);
            color: white;
        }

        /* Shopping Cart Badge */
        .badge-danger {
            background: linear-gradient(45deg, var(--primary-color), #FF8FB3) !important;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* Footer */
        .main-footer {
            background: rgba(44, 62, 80, 0.9);
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }

        /* Container Background */
        .content .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-top: 20px;
        }

        /* Cart Modal Styles */
        .cart-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .cart-modal-content {
            background: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 20px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .cart-modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px 30px;
            position: relative;
        }

        .cart-modal-header h2 {
            margin: 0;
            font-family: 'Fredoka', cursive;
            font-size: 1.8rem;
        }

        .cart-close {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cart-close:hover {
            transform: translateY(-50%) scale(1.1);
            text-shadow: 0 0 10px rgba(255,255,255,0.5);
        }

        .cart-modal-body {
            max-height: 50vh;
            overflow-y: auto;
            padding: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.3s ease;
        }

        .cart-item:hover {
            background-color: #f9f9f9;
        }

        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .cart-item-details {
            flex-grow: 1;
        }

        .cart-item-name {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 5px;
            font-size: 1.1rem;
        }

        .cart-item-price {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1rem;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .quantity-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-btn:hover {
            background: #FF8FB3;
            transform: scale(1.1);
        }

        .quantity-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .quantity-display {
            margin: 0 15px;
            font-weight: 600;
            font-size: 1.2rem;
            color: var(--dark-color);
            min-width: 30px;
            text-align: center;
        }

        .remove-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: 10px;
        }

        .remove-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .cart-modal-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            border-top: 1px solid #dee2e6;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .cart-total h3 {
            margin: 0;
            color: var(--dark-color);
            font-family: 'Fredoka', cursive;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .checkout-btn {
            background: linear-gradient(45deg, var(--secondary-color), #6FE7DD);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .checkout-btn:hover {
            background: linear-gradient(45deg, #6FE7DD, var(--secondary-color));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 205, 196, 0.4);
        }

        .empty-cart {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-cart i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ddd;
        }

        /* Loading spinner */
        .cart-loading {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .content-header h1 {
                font-size: 2rem;
            }
            
            .category-header {
                font-size: 1.5rem;
            }
            
            .product-card {
                margin-bottom: 20px;
            }

            .cart-modal-content {
                width: 95%;
                margin: 2% auto;
                max-height: 95vh;
            }

            .cart-item {
                flex-direction: column;
                text-align: center;
            }

            .cart-item-image {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .quantity-controls {
                justify-content: center;
            }
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light">
            <div class="container">
                <a href="./" class="navbar-brand">
                    <i class="fas fa-robot" style="color: var(--primary-color); font-size: 1.5rem; margin-right: 10px;"></i>
                    <span class="brand-text font-weight-bold">ArtToy Paradise</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="./" class="nav-link">
                                <i class="fas fa-home mr-1"></i>หน้าแรก
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                                <li><a href="Custom Model.php" class="dropdown-item">
                                    <i class="fas fa-star mr-2"></i>Custom Model
                                </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showCartModal()" title="ตะกร้าสินค้า">
                            <i class="fas fa-shopping-cart" style="font-size: 1.2rem;"></i>
                            <span id="num_busket" class="badge badge-danger navbar-badge"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php" title="เข้าสู่ระบบ">
                            <i class="fas fa-user-circle" style="font-size: 1.2rem;"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-sm-8">
                            <h1><i class="fas fa-palette mr-3"></i>คอลเลกชั่น Art Toy</h1>
                        </div>
                        <div class="col-sm-4 text-right">
                            <i class="fas fa-robot" style="font-size: 3rem; color: rgba(255,255,255,0.3);"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="content">
                <div class="container">
                    <?php foreach ($data as $key => $rows) { ?>
                        <div class="row">
                            <div class="col-12">
                                <h2 class="category-header">
                                    <i class="fas fa-cube mr-2"></i><?= $key ?>
                                </h2>
                            </div>
                        </div>
                        
                        <div class="row">
                            <?php foreach ($rows as $k => $row) { ?>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
                                    <div class="card product-card">
                                        <img class="card-img-top product-image" 
                                             src="./uploaded_images/<?= $row->ImageURL ?>" 
                                             alt="<?= $row->ProductName ?>"
                                             loading="lazy">
                                        
                                        <div class="card-body">
                                            <h5 class="card-title"><?= $row->ProductName ?></h5>
                                            <p class="card-text">
                                                <i class="fas fa-tag mr-1"></i>
                                                <?= number_format($row->Price, 0) ?> บาท
                                            </p>
                                        </div>
                                        
                                        <div class="card-footer p-2">
                                            <button class="btn btn-cart btn-sm w-100" 
                                                    onclick="add_busket('<?= $row->ProductID ?>')">
                                                <i class="fas fa-cart-plus mr-1"></i>เพิ่มในตะกร้า
                                            </button>
                                            <a href="view3d.php?product_id=<?= $row->ProductID ?>" 
                                               class="btn btn-3d btn-sm w-100" 
                                               target="_blank">
                                                <i class="fas fa-cube mr-1"></i>ดู 3D
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h5><i class="fas fa-heart" style="color: var(--primary-color);"></i> ArtToy Paradise</h5>
                        <p>ร้านของเล่นศิลปะที่รวบรวมความสวยงามและความสนุก</p>
                        <small>&copy; 2025 ArtToy Paradise. สงวนลิขสิทธิ์.</small>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Cart Modal -->
        <div id="cartModal" class="cart-modal">
            <div class="cart-modal-content">
                <div class="cart-modal-header">
                    <h2><i class="fas fa-shopping-cart mr-2"></i>ตะกร้าสินค้า</h2>
                    <span class="cart-close" onclick="closeCartModal()">&times;</span>
                </div>
                <div class="cart-modal-body" id="cartModalBody">
                    <div class="cart-loading">
                        <div class="spinner"></div>
                    </div>
                </div>
                <div class="cart-modal-footer" id="cartModalFooter" style="display: none;">
                    <div class="cart-total">
                        <h3>ยอดรวมทั้งหมด:</h3>
                        <span class="total-amount" id="totalAmount">0 บาท</span>
                    </div>
                    <button class="checkout-btn" onclick="goToCheckout()">
                        <i class="fas fa-credit-card mr-2"></i>ดำเนินการสั่งซื้อ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>

    <script>
        // Global variables
        let cartItems = [];

        function add_busket(productID) {
            // Store the original button reference
            const originalButton = event.target;
            
            // Add loading state
            originalButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังเพิ่ม...';
            originalButton.disabled = true;

            $.ajax({
                type: "POST",
                url: "sql_busket.php",
                data: {
                    ProductID: productID,
                    action: 'add_busket',
                },
                dataType: "json",
                success: function(response) {
                    // Reset button
                    originalButton.innerHTML = '<i class="fas fa-cart-plus mr-1"></i>เพิ่มในตะกร้า';
                    originalButton.disabled = false;

                    if (response.success || response.num >= 0) {
                        $("#num_busket").html(response.num || 0);
                        
                        // Show success message with animation
                        showNotification("เพิ่มสินค้าสำเร็จ! 🎉", "success");
                        
                        // Add visual feedback
                        $(originalButton).removeClass('btn-cart').addClass('btn-success').html('<i class="fas fa-check mr-1"></i>เพิ่มแล้ว');
                        setTimeout(() => {
                            $(originalButton).removeClass('btn-success').addClass('btn-cart').html('<i class="fas fa-cart-plus mr-1"></i>เพิ่มในตะกร้า');
                        }, 2000);
                    } else {
                        showNotification(response.message || "ไม่สามารถเพิ่มสินค้าได้", "error");
                    }
                },
                error: function() {
                    // Reset button
                    originalButton.innerHTML = '<i class="fas fa-cart-plus mr-1"></i>เพิ่มในตะกร้า';
                    originalButton.disabled = false;
                    showNotification("เกิดข้อผิดพลาด", "error");
                }
            });
        }

        function view_busket() {
            $.ajax({
                type: "GET",
                url: "sql_busket.php",
                data: {
                    action: 'count_busket',
                },
                dataType: "json",
                success: function(response) {
                    if (response.num > 0) {
                        $("#num_busket").html(response.num);
                    } else {
                        $("#num_busket").html('');
                    }
                }
            });
        }

        function showCartModal() {
            $('#cartModal').fadeIn(300);
            loadCartItems();
        }

        function closeCartModal() {
            $('#cartModal').fadeOut(300);
        }

        function loadCartItems() {
            $('#cartModalBody').html('<div class="cart-loading"><div class="spinner"></div></div>');
            $('#cartModalFooter').hide();

            $.ajax({
                type: "GET",
                url: "sql_busket.php",
                data: {
                    action: 'get_cart_items',
                },
                dataType: "json",
                success: function(response) {
                    console.log('Cart items response:', response); // Debug log
                    if (response.success && response.items && response.items.length > 0) {
                        cartItems = response.items;
                        renderCartItems();
                        updateCartTotal();
                        $('#cartModalFooter').show();
                    } else {
                        showEmptyCart();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading cart items:', error); // Debug log
                    $('#cartModalBody').html('<div class="empty-cart"><i class="fas fa-exclamation-triangle"></i><h4>เกิดข้อผิดพลาดในการโหลดข้อมูล</h4><p>กรุณาลองใหม่อีกครั้ง</p></div>');
                }
            });
        }

        // แทนที่ฟังก์ชันเดิมใน index.php ด้วยโค้ดนี้

function renderCartItems() {
    let html = '';
    cartItems.forEach(item => {
        const itemTotal = parseFloat(item.Price) * parseInt(item.Quantity);
        html += `
            <div class="cart-item" data-cart-id="${item.CartID}">
                <img src="./uploaded_images/${item.ImageURL}" alt="${item.ProductName}" class="cart-item-image">
                <div class="cart-item-details">
                    <div class="cart-item-name">${item.ProductName}</div>
                    <small style="color: #666; margin-bottom: 5px; display: block;">
                        <i class="fas fa-tag mr-1"></i>${item.CategoryName}
                    </small>
                    <div class="cart-item-price">${parseInt(item.Price).toLocaleString()} บาท x ${item.Quantity} = ${itemTotal.toLocaleString()} บาท</div>
                    <div class="quantity-controls">
                        <button class="quantity-btn" onclick="updateQuantity('${item.CartID}', ${parseInt(item.Quantity) - 1})" ${item.Quantity <= 1 ? 'disabled' : ''}>
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="quantity-display">${item.Quantity}</span>
                        <button class="quantity-btn" onclick="updateQuantity('${item.CartID}', ${parseInt(item.Quantity) + 1})">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="remove-btn" onclick="removeFromCart('${item.CartID}')">
                            <i class="fas fa-trash mr-1"></i>ลบ
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    $('#cartModalBody').html(html);
}

function updateQuantity(cartID, newQuantity) {
    if (newQuantity < 1) {
        removeFromCart(cartID);
        return;
    }

    // Show loading state
    const cartItem = $(`.cart-item[data-cart-id="${cartID}"]`);
    const originalControls = cartItem.find('.quantity-controls').html();
    cartItem.find('.quantity-controls').html('<div class="spinner" style="width: 20px; height: 20px; border-width: 2px;"></div>');

    $.ajax({
        type: "POST",
        url: "sql_busket.php",
        data: {
            CartID: cartID,
            quantity: newQuantity,
            action: 'update_quantity',
        },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                // Update local cart items
                const itemIndex = cartItems.findIndex(item => item.CartID === cartID);
                if (itemIndex !== -1) {
                    cartItems[itemIndex].Quantity = newQuantity;
                }
                
                renderCartItems();
                updateCartTotal();
                
                // Update cart badge
                if (response.cart_count !== undefined) {
                    if (response.cart_count > 0) {
                        $("#num_busket").html(response.cart_count);
                    } else {
                        $("#num_busket").html('');
                    }
                }
                
                showNotification("อัพเดทจำนวนสินค้าสำเร็จ", "success");
            } else {
                showNotification(response.error || "ไม่สามารถอัพเดทจำนวนสินค้าได้", "error");
                cartItem.find('.quantity-controls').html(originalControls);
            }
        },
        error: function() {
            showNotification("เกิดข้อผิดพลาด", "error");
            cartItem.find('.quantity-controls').html(originalControls);
        }
    });
}

function removeFromCart(cartID) {
    if (!confirm('คุณต้องการลบสินค้านี้ออกจากตะกร้าหรือไม่?')) {
        return;
    }

    // Show loading state
    const cartItem = $(`.cart-item[data-cart-id="${cartID}"]`);
    cartItem.fadeTo(300, 0.5);

    $.ajax({
        type: "POST",
        url: "sql_busket.php",
        data: {
            CartID: cartID,
            action: 'remove_item',
        },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                // Remove from local cart items
                cartItems = cartItems.filter(item => item.CartID !== cartID);
                
                if (cartItems.length === 0) {
                    showEmptyCart();
                } else {
                    renderCartItems();
                    updateCartTotal();
                }
                
                // Update cart badge
                if (response.cart_count !== undefined) {
                    if (response.cart_count > 0) {
                        $("#num_busket").html(response.cart_count);
                    } else {
                        $("#num_busket").html('');
                    }
                }
                
                showNotification("ลบสินค้าสำเร็จ", "success");
            } else {
                showNotification(response.error || "ไม่สามารถลบสินค้าได้", "error");
                cartItem.fadeTo(300, 1);
            }
        },
        error: function() {
            showNotification("เกิดข้อผิดพลาด", "error");
            cartItem.fadeTo(300, 1);
        }
    });
}

        function updateCartTotal() {
            let total = 0;
            cartItems.forEach(item => {
                total += parseFloat(item.Price) * parseInt(item.Quantity);
            });
            $('#totalAmount').text(total.toLocaleString() + ' บาท');
        }

        function goToCheckout() {
            if (cartItems.length === 0) {
                showNotification("ตะกร้าสินค้าว่างเปล่า", "error");
                return;
            }
            
            closeCartModal();
            window.location.href = 'busket.php';
        }

        function showNotification(message, type) {
            const notification = $(`
                <div class="alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show" 
                     style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                    ${message}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            `);
            
            $('body').append(notification);
            
            setTimeout(() => {
                notification.alert('close');
            }, 3000);
        }

        // Initialize
        $(document).ready(function() {
            view_busket();
            
            // Close modal when clicking outside
            $('#cartModal').click(function(e) {
                if (e.target === this) {
                    closeCartModal();
                }
            });
            
            // Close modal with Escape key
            $(document).keydown(function(e) {
                if (e.keyCode === 27) { // Escape key
                    closeCartModal();
                }
            });
            
            // Add smooth scrolling
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if( target.length ) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                }
            });
        });
    </script>
</body>
</html>