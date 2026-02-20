<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('location:login.php');
}

if (!isset($title)) {
    $title = 'Art Toy Gallery - ระบบจัดการร้าน';
}
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.bootstrap5.css">

<!-- Custom Art Toy CSS -->
<style>
    :root {
        --primary-color: #ff6b35;
        --secondary-color: #f7931e;
        --accent-color: #4ecdc4;
        --dark-color: #2c3e50;
        --light-color: #ecf0f1;
        --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --card-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Kanit', 'Poppins', sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    /* Header Styles */
    .main-header {
        background: var(--gradient-bg);
        padding: 1rem 2rem;
        box-shadow: var(--card-shadow);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1400px;
        margin: 0 auto;
    }

    .logo {
        display: flex;
        align-items: center;
        color: white;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.5rem;
    }

    .logo i {
        margin-right: 0.5rem;
        font-size: 2rem;
        color: var(--accent-color);
    }

    .user-menu {
        display: flex;
        align-items: center;
        color: white;
        gap: 1rem;
    }

    .cart-btn {
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        padding: 0.8rem;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        font-size: 1.2rem;
    }

    .cart-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
    }

    .cart-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .logout-btn {
        background: rgba(255,255,255,0.2);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
    }

    /* Cart Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .cart-modal {
        background: white;
        border-radius: 20px;
        width: 90%;
        max-width: 500px;
        max-height: 80vh;
        overflow: hidden;
        transform: scale(0.8);
        transition: all 0.3s ease;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    }

    .modal-overlay.active .cart-modal {
        transform: scale(1);
    }

    .cart-header {
        background: var(--gradient-bg);
        color: white;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-title {
        font-size: 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .close-btn:hover {
        background: rgba(255,255,255,0.2);
        transform: rotate(90deg);
    }

    .cart-body {
        padding: 1.5rem;
        max-height: 50vh;
        overflow-y: auto;
    }

    .cart-empty {
        text-align: center;
        padding: 3rem 1rem;
        color: #7f8c8d;
    }

    .cart-empty i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .cart-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid var(--light-color);
        transition: all 0.3s ease;
    }

    .cart-item:hover {
        background: #f8f9fa;
        border-radius: 10px;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        background: var(--gradient-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin-right: 1rem;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.2rem;
    }

    .item-price {
        color: var(--primary-color);
        font-weight: 700;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-left: 1rem;
    }

    .qty-btn {
        background: var(--accent-color);
        color: white;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qty-btn:hover {
        background: #45b7aa;
        transform: scale(1.1);
    }

    .qty-btn:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
        transform: none;
    }

    .quantity {
        font-weight: 600;
        min-width: 30px;
        text-align: center;
        background: var(--light-color);
        padding: 0.3rem 0.5rem;
        border-radius: 10px;
    }

    .remove-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.3rem;
        border-radius: 5px;
        cursor: pointer;
        margin-left: 0.5rem;
        transition: all 0.3s ease;
    }

    .remove-btn:hover {
        background: #e55a30;
        transform: scale(1.1);
    }

    .cart-footer {
        border-top: 2px solid var(--light-color);
        padding: 1.5rem;
        background: #f8f9fa;
    }

    .cart-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .total-amount {
        color: var(--primary-color);
        font-size: 1.5rem;
    }

    .checkout-btn {
        width: 100%;
        background: var(--gradient-bg);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 15px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .checkout-btn:disabled {
        background: #bdc3c7;
        cursor: not-allowed;
        transform: none;
    }
</style>