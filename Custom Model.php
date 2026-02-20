<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Custom Model - ArtToy Paradise</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Three.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/examples/js/loaders/GLTFLoader.js"></script>
    
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

        /* Main Content */
        .content-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin: 20px;
            margin-top: 0;
        }

        /* Customization Cards */
        .custom-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.4s ease;
            overflow: hidden;
            position: relative;
            margin-bottom: 25px;
            border: none;
        }

        .custom-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color), var(--accent-color));
        }

        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), #FF8FB3);
            color: white;
            border: none;
            padding: 15px 20px;
            font-family: 'Fredoka', cursive;
            font-weight: 500;
            font-size: 1.2rem;
        }

        .card-body {
            padding: 25px !important;
        }

        /* Option Buttons */
        .option-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .option-btn {
            background: white;
            border: 3px solid #e9ecef;
            color: var(--dark-color);
            padding: 12px 20px;
            border-radius: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            flex: 1;
            min-width: 120px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .option-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            transition: left 0.3s ease;
            z-index: -1;
        }

        .option-btn:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            color: white;
        }

        .option-btn:hover::before {
            left: 0;
        }

        .option-btn.active {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border-color: var(--primary-color);
            color: white;
            transform: scale(1.05);
        }

        .option-btn.disabled {
            background: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .option-btn.disabled:hover {
            transform: none;
            color: #6c757d;
        }

        .option-btn.disabled::before {
            display: none;
        }

        /* Sub-options */
        .sub-options {
            margin-left: 20px;
            margin-top: 15px;
            padding-left: 20px;
            border-left: 3px solid var(--accent-color);
            display: none;
            animation: slideDown 0.3s ease;
        }

        .sub-options.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .sub-option-label {
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        /* Hidden sections */
        .hidden-section {
            opacity: 0.5;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .hidden-section .card-header {
            background: linear-gradient(135deg, #6c757d, #adb5bd);
        }

        .show-section {
            opacity: 1;
            pointer-events: auto;
        }

        .show-section .card-header {
            background: linear-gradient(135deg, var(--primary-color), #FF8FB3);
        }

        /* 3D Preview Container */
        .preview-container {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            padding: 20px;
            text-align: center;
            position: sticky;
            top: 20px;
        }

        .preview-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
        }

        #threejs-container {
            width: 100%;
            height: 400px;
            border-radius: 15px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .preview-placeholder {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            color: #6c757d;
        }

        .preview-placeholder i {
            font-size: 4rem;
            margin-bottom: 15px;
            color: var(--secondary-color);
        }

        /* Generate Button */
        .generate-btn {
            background: linear-gradient(45deg, var(--secondary-color), #6FE7DD);
            border: none;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
            padding: 15px 40px;
            border-radius: 25px;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .generate-btn:hover {
            background: linear-gradient(45deg, #6FE7DD, var(--secondary-color));
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(78, 205, 196, 0.4);
            color: white;
        }

        .generate-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Selection Summary */
        .selection-summary {
            background: linear-gradient(135deg, rgba(255, 107, 157, 0.1), rgba(78, 205, 196, 0.1));
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .selection-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .selection-item:last-child {
            border-bottom: none;
        }

        .selection-label {
            font-weight: 600;
            color: var(--dark-color);
        }

        .selection-value {
            color: var(--primary-color);
            font-weight: 500;
        }

        /* Loading Animation */
        .loading-spinner {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(78, 205, 196, 0.3);
            border-top: 4px solid var(--secondary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            padding: 15px 20px;
            border-radius: 10px;
            color: white;
            font-weight: 500;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            background: linear-gradient(45deg, #28a745, #20c997);
        }

        .notification.error {
            background: linear-gradient(45deg, #dc3545, #fd7e14);
        }

        /* Footer */
        .main-footer {
            background: rgba(44, 62, 80, 0.9);
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content-header h1 {
                font-size: 2rem;
            }
            
            .option-btn {
                min-width: 100px;
                font-size: 0.9rem;
                padding: 10px 15px;
            }

            #threejs-container {
                height: 300px;
            }

            .sub-options {
                margin-left: 10px;
                padding-left: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a href="./" class="navbar-brand">
                    <i class="fas fa-robot" style="color: var(--primary-color); font-size: 1.5rem; margin-right: 10px;"></i>
                    <span class="brand-text font-weight-bold">ArtToy Paradise</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a href="./" class="nav-link">
                                <i class="fas fa-home me-1"></i>หน้าแรก
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="fas fa-cube me-1"></i>Custom Model
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#" title="ตะกร้าสินค้า">
                                <i class="fas fa-shopping-cart" style="font-size: 1.2rem;"></i>
                                <span class="badge bg-danger"></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php" title="เข้าสู่ระบบ">
                                <i class="fas fa-user-circle" style="font-size: 1.2rem;"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Content Header -->
        <div class="content-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1><i class="fas fa-magic me-3"></i>Custom Model Art Toy</h1>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="fas fa-cube" style="font-size: 3rem; color: rgba(255,255,255,0.3);"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-container">
            <div class="container-fluid">
                <div class="row">
                    <!-- Customization Options -->
                    <div class="col-lg-8">
                        <!-- Fruit Type Selection -->
                        <div class="card custom-card show-section" id="fruitSection">
                            <div class="card-header">
                                <i class="fas fa-apple-alt me-2"></i>1. เลือกผลไม้ประเภท
                            </div>
                            <div class="card-body">
                                <div class="option-group">
                                    <button class="option-btn" data-type="fruit" data-value="apple" onclick="selectOption(this)">
                                        <i class="fas fa-apple-alt me-2"></i>แอปเปิล
                                    </button>
                                    <button class="option-btn disabled" data-type="fruit" data-value="banana">
                                        <i class="fas fa-seedling me-2"></i>กล้วย (เร็วๆ นี้)
                                    </button>
                                    <button class="option-btn disabled" data-type="fruit" data-value="orange">
                                        <i class="fas fa-circle me-2"></i>ส้ม (เร็วๆ นี้)
                                    </button>
                                </div>
                                
                                <!-- Apple Sub-options -->
                                <div class="sub-options" id="apple-options">
                                    <div class="sub-option-label">เลือกสีแอปเปิล:</div>
                                    <div class="option-group">
                                        <button class="option-btn" data-type="apple-color" data-value="green" onclick="selectOption(this)">
                                            <i class="fas fa-circle me-2" style="color: green;"></i>เขียว
                                        </button>
                                        <button class="option-btn" data-type="apple-color" data-value="red" onclick="selectOption(this)">
                                            <i class="fas fa-circle me-2" style="color: red;"></i>แดง
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Face Expression Selection -->
                        <div class="card custom-card hidden-section" id="expressionSection">
                            <div class="card-header">
                                <i class="fas fa-smile me-2"></i>2. เลือกสีหน้าโมเดล
                            </div>
                            <div class="card-body">
                                <div class="option-group">
                                    <button class="option-btn" data-type="expression" data-value="smile" onclick="selectOption(this)">
                                        <i class="fas fa-smile me-2"></i>ยิ้ม
                                    </button>
                                    <button class="option-btn" data-type="expression" data-value="angry" onclick="selectOption(this)">
                                        <i class="fas fa-angry me-2"></i>โกรธ
                                    </button>
                                    <button class="option-btn" data-type="expression" data-value="sad" onclick="selectOption(this)">
                                        <i class="fas fa-sad-tear me-2"></i>ร้องไห้
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Panel -->
                    <div class="col-lg-4">
                        <div class="preview-container">
                            <h4 class="mb-3">
                                <i class="fas fa-eye me-2"></i>ตัวอย่างโมเดล
                            </h4>
                            
                            <!-- Selection Summary -->
                            <div class="selection-summary">
                                <div class="selection-item">
                                    <span class="selection-label">ผลไม้:</span>
                                    <span class="selection-value" id="selected-fruit">ยังไม่ได้เลือก</span>
                                </div>
                                <div class="selection-item">
                                    <span class="selection-label">สีหน้า:</span>
                                    <span class="selection-value" id="selected-expression">ยังไม่ได้เลือก</span>
                                </div>
                            </div>

                            <!-- 3D Container -->
                            <div id="threejs-container">
                                <div class="preview-placeholder">
                                    <i class="fas fa-cube"></i>
                                    <h5>เลือกตัวเลือกเพื่อดูตัวอย่าง</h5>
                                    <p class="text-muted mb-0">กรุณาเลือกผลไม้เพื่อเริ่มต้น</p>
                                </div>
                                <div class="loading-spinner">
                                    <div class="spinner"></div>
                                </div>
                            </div>

                            <!-- Generate Button -->
                            <button class="generate-btn" id="generateBtn" onclick="generate3DModel(); return false;" disabled>
                                <i class="fas fa-magic me-2"></i>สร้างโมเดล 3D
                            </button>
                        </div>
                    </div>
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
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Global variables
        let selectedOptions = {
            fruit: null,
            appleColor: null,
            expression: null
        };

        let scene, camera, renderer, currentModel;

        // Model configurations mapping
        const modelConfigs = {
            // Apple Green combinations
            'apple-green-smile': './models/apple_green_smile.glb',
            'apple-green-angry': './models/apple_green_angry.glb',
            'apple-green-sad': './models/apple_green_sad.glb',
            
            // Apple Red combinations
            'apple-red-smile': './models/apple_red_smile.glb',
            'apple-red-angry': './models/apple_red_angry.glb',
            'apple-red-sad': './models/apple_red_sad.glb'
        };

        // Initialize Three.js scene
        function initThreeJS() {
            const container = document.getElementById('threejs-container');
            
            // Scene
            scene = new THREE.Scene();
            scene.background = new THREE.Color(0xf0f0f0);

            // Camera
            camera = new THREE.PerspectiveCamera(75, container.offsetWidth / container.offsetHeight, 0.1, 1000);
            camera.position.set(0, 0, 5);

            // Renderer
            renderer = new THREE.WebGLRenderer({ antialias: true });
            renderer.setSize(container.offsetWidth, container.offsetHeight);
            renderer.shadowMap.enabled = true;
            renderer.shadowMap.type = THREE.PCFSoftShadowMap;
            
            // Clear container and add renderer
            container.innerHTML = '';
            container.appendChild(renderer.domElement);

            // Lighting
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
            scene.add(ambientLight);

            const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
            directionalLight.position.set(5, 5, 5);
            directionalLight.castShadow = true;
            scene.add(directionalLight);

            // Create initial placeholder
            createPlaceholder();
            
            // Start animation loop
            animate();
        }

        function createPlaceholder() {
            // Clear existing objects except lights
            while(scene.children.length > 2) {
                scene.remove(scene.children[2]);
            }

            // Create a simple wireframe cube as placeholder
            const geometry = new THREE.BoxGeometry(2, 2, 2);
            const material = new THREE.MeshBasicMaterial({ 
                color: 0x4ECDC4, 
                wireframe: true,
                transparent: true,
                opacity: 0.3
            });
            const cube = new THREE.Mesh(geometry, material);
            scene.add(cube);
            currentModel = cube;
        }

        function animate() {
            requestAnimationFrame(animate);
            
            // Rotate current model
            if (currentModel) {
                currentModel.rotation.y += 0.01;
            }
            
            renderer.render(scene, camera);
        }

        function selectOption(button) {
            if (button.classList.contains('disabled')) {
                return;
            }

            const type = button.dataset.type;
            const value = button.dataset.value;

            // Remove active class from siblings
            const siblings = button.parentElement.querySelectorAll('.option-btn');
            siblings.forEach(sibling => {
                sibling.classList.remove('active');
            });

            // Add active class to clicked button
            button.classList.add('active');

            // Update selected options
            if (type === 'fruit') {
                selectedOptions.fruit = value;
                updateFruitDisplay();
                
                // Show/hide sub-options
                document.querySelectorAll('.sub-options').forEach(sub => {
                    sub.classList.remove('show');
                });
                
                if (value === 'apple') {
                    document.getElementById('apple-options').classList.add('show');
                }
            } else if (type === 'apple-color') {
                selectedOptions.appleColor = value;
                updateFruitDisplay();
                
                // Show expression selection after apple color is complete
                showNextSection('expressionSection');
            } else if (type === 'expression') {
                selectedOptions.expression = value;
                updateExpressionDisplay(value);
            }

            // Check if all required options are selected
            checkSelectionComplete();
        }

        function showNextSection(sectionId) {
            const section = document.getElementById(sectionId);
            section.classList.remove('hidden-section');
            section.classList.add('show-section');
            
            // Smooth scroll to next section
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function updateFruitDisplay() {
            const display = document.getElementById('selected-fruit');
            if (selectedOptions.fruit === 'apple' && selectedOptions.appleColor) {
                display.textContent = `แอปเปิล${selectedOptions.appleColor === 'green' ? 'เขียว' : 'แดง'}`;
            } else if (selectedOptions.fruit) {
                display.textContent = 'แอปเปิล';
            } else {
                display.textContent = 'ยังไม่ได้เลือก';
            }
        }

        function updateExpressionDisplay(value) {
            const display = document.getElementById('selected-expression');
            const expressionNames = {
                'smile': 'ยิ้ม',
                'angry': 'โกรธ',
                'sad': 'ร้องไห้'
            };
            display.textContent = expressionNames[value] || 'ยังไม่ได้เลือก';
        }

        function checkSelectionComplete() {
            const isComplete = selectedOptions.fruit && 
                             (selectedOptions.fruit !== 'apple' || selectedOptions.appleColor) &&
                             selectedOptions.expression;
            
            const generateBtn = document.getElementById('generateBtn');
            generateBtn.disabled = !isComplete;
            
            // Update preview message
            const placeholder = document.querySelector('.preview-placeholder');
            if (placeholder) {
                if (!selectedOptions.fruit) {
                    placeholder.innerHTML = `
                        <i class="fas fa-cube"></i>
                        <h5>เลือกตัวเลือกเพื่อดูตัวอย่าง</h5>
                        <p class="text-muted mb-0">กรุณาเลือกผลไม้เพื่อเริ่มต้น</p>
                    `;
                } else if (!selectedOptions.appleColor) {
                    placeholder.innerHTML = `
                        <i class="fas fa-apple-alt"></i>
                        <h5>เลือกสีแอปเปิล</h5>
                        <p class="text-muted mb-0">กรุณาเลือกสีของแอปเปิล</p>
                    `;
                } else if (!selectedOptions.expression) {
                    placeholder.innerHTML = `
                        <i class="fas fa-smile"></i>
                        <h5>เลือกสีหน้าโมเดล</h5>
                        <p class="text-muted mb-0">กรุณาเลือกสีหน้าเพื่อดูตัวอย่าง</p>
                    `;
                } else {
                    placeholder.innerHTML = `
                        <i class="fas fa-magic"></i>
                        <h5>พร้อมสร้างโมเดล!</h5>
                        <p class="text-muted mb-0">กดปุ่ม "สร้างโมเดล 3D" เพื่อดูผลลัพธ์</p>
                    `;
                }
            }
        }

        function generate3DModel() {
            console.log('generate3DModel called'); // เพิ่ม debug log
            
            // Show loading
            showLoading(true);

            // Get model key
            const modelKey = getModelKey();
            console.log('Generating model:', modelKey);

            // Show notification
            showNotification('กำลังสร้างโมเดล3D...', 'success');

            // Simulate loading time then redirect to A-Frame page
            setTimeout(() => {
                showLoading(false);
                // Redirect to A-Frame page with model parameters
                const modelFileName = `${modelKey}.glb`;
                console.log('Redirecting to:', `A-Frame 3D Model Viewer.php?model=${encodeURIComponent(modelFileName)}`);
                // ลองเปลี่ยนเป็น Google ก่อนเพื่อทดสอบว่า redirect ทำงานหรือไม่
                // window.location.href = 'https://www.google.com';
                window.location.href = `A-Frame 3D Model Viewer.php?model=${encodeURIComponent(modelFileName)}`;
            }, 2000);
        }

        function getModelKey() {
            const fruitType = selectedOptions.fruit;
            const appleColor = selectedOptions.appleColor || '';
            const expression = selectedOptions.expression || 'smile';

            return `${fruitType}_${appleColor}_${expression}`;
        }

        function showLoading(show) {
            const spinner = document.querySelector('.loading-spinner');
            const placeholder = document.querySelector('.preview-placeholder');
            
            if (show) {
                if (spinner) spinner.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            } else {
                if (spinner) spinner.style.display = 'none';
                if (placeholder) placeholder.style.display = 'none';
            }
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
            `;
            
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            // Hide notification
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initThreeJS();
            
            // Handle window resize
            window.addEventListener('resize', function() {
                const container = document.getElementById('threejs-container');
                camera.aspect = container.offsetWidth / container.offsetHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(container.offsetWidth, container.offsetHeight);
            });
        });
    </script>
</body>
</html>