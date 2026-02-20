<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>3D Model Viewer - ArtToy Paradise</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&family=Fredoka:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- A-Frame -->
    <script src="https://aframe.io/releases/1.4.0/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/donmccurdy/aframe-extras@v6.1.1/dist/aframe-extras.min.js"></script>

    <style>
        :root {
            --primary-color: #FF6B9D;
            --secondary-color: #4ECDC4;
            --accent-color: #FFE66D;
            --dark-color: #2C3E50;
            --light-bg: #F8F9FA;
        }

        body {
            font-family: 'Kanit', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* A-Frame Scene Styling */
        a-scene {
            width: 100vw;
            height: 100vh;
        }

        /* UI Overlay */
        .ui-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-bottom: 3px solid var(--primary-color);
            padding: 15px 0;
        }

        .navbar-brand {
            font-family: 'Fredoka', cursive;
            font-weight: 600;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
            text-decoration: none;
        }

        .brand-text {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Control Panel */
        .control-panel {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            display: flex;
            gap: 15px;
            align-items: center;
            border: 3px solid var(--secondary-color);
        }

        .control-btn {
            background: linear-gradient(45deg, var(--secondary-color), #6FE7DD);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 120px;
            justify-content: center;
        }

        .control-btn:hover {
            background: linear-gradient(45deg, #6FE7DD, var(--secondary-color));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 205, 196, 0.4);
        }

        .control-btn.secondary {
            background: linear-gradient(45deg, var(--primary-color), #FF8FB3);
        }

        .control-btn.secondary:hover {
            background: linear-gradient(45deg, #FF8FB3, var(--primary-color));
            box-shadow: 0 5px 15px rgba(255, 107, 157, 0.4);
        }

        /* Model Info Panel */
        .info-panel {
            position: fixed;
            top: 100px;
            left: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            max-width: 300px;
            border: 3px solid var(--accent-color);
        }

        .info-panel h5 {
            color: var(--dark-color);
            font-family: 'Fredoka', cursive;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: var(--dark-color);
        }

        .info-value {
            color: var(--primary-color);
            font-weight: 500;
        }

        /* Loading Screen */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            color: white;
            transition: opacity 0.5s ease;
        }

        .loading-screen.hide {
            opacity: 0;
            pointer-events: none;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            font-family: 'Fredoka', cursive;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .loading-subtitle {
            font-size: 1rem;
            opacity: 0.8;
        }

        /* Error Message */
        .error-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1500;
            background: rgba(220, 53, 69, 0.95);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            max-width: 400px;
            display: none;
        }

        .error-message h4 {
            font-family: 'Fredoka', cursive;
            margin-bottom: 15px;
        }

        /* Instructions */
        .instructions {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            max-width: 250px;
            border: 3px solid var(--primary-color);
        }

        .instructions h6 {
            color: var(--dark-color);
            font-family: 'Fredoka', cursive;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .instructions ul {
            padding-left: 20px;
            margin: 0;
        }

        .instructions li {
            margin-bottom: 5px;
            color: var(--dark-color);
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .control-panel {
                flex-direction: column;
                gap: 10px;
                padding: 15px;
                left: 10px;
                right: 10px;
                transform: none;
            }

            .info-panel, .instructions {
                position: relative;
                left: auto;
                right: auto;
                top: auto;
                margin: 10px;
                max-width: none;
            }

            .control-btn {
                min-width: 100px;
                padding: 10px 15px;
                font-size: 0.9rem;
            }
        }

        /* Hide A-Frame VR button */
        .a-enter-vr {
            display: none !important;
        }
    </style>
</head>

<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-spinner"></div>
        <div class="loading-text">กำลังโหลดโมเดล 3D</div>
        <div class="loading-subtitle">กรุณารอสักครู่...</div>
    </div>

    <!-- Error Message -->
    <div class="error-message" id="errorMessage">
        <h4><i class="fas fa-exclamation-triangle"></i> เกิดข้อผิดพลาด</h4>
        <p>ไม่สามารถโหลดโมเดล 3D ได้ กรุณาลองใหม่อีกครั้ง</p>
        <button class="control-btn" onclick="goBack()">
            <i class="fas fa-arrow-left"></i> กลับไปเลือกใหม่
        </button>
    </div>

    <!-- UI Overlay -->
    <div class="ui-overlay">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col">
                    <a href="./" class="navbar-brand">
                        <i class="fas fa-robot" style="color: var(--primary-color); font-size: 1.5rem; margin-right: 10px;"></i>
                        <span class="brand-text font-weight-bold">ArtToy Paradise</span>
                    </a>
                </div>
                <div class="col-auto">
                    <span style="color: var(--dark-color); font-weight: 500;">
                        <i class="fas fa-cube me-2"></i>3D Model Viewer
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Model Info Panel -->
    <div class="info-panel" id="infoPanel">
        <h5><i class="fas fa-info-circle me-2"></i>ข้อมูลโมเดล</h5>
        <div class="info-item">
            <span class="info-label">ประเภท:</span>
            <span class="info-value" id="modelType">-</span>
        </div>
        <div class="info-item">
            <span class="info-label">สี:</span>
            <span class="info-value" id="modelColor">-</span>
        </div>
        <div class="info-item">
            <span class="info-label">สีหน้า:</span>
            <span class="info-value" id="modelExpression">-</span>
        </div>
        <div class="info-item">
            <span class="info-label">ไฟล์:</span>
            <span class="info-value" id="modelFile">-</span>
        </div>
    </div>

    <!-- Instructions Panel -->
    <div class="instructions">
        <h6><i class="fas fa-question-circle me-2"></i>วิธีใช้งาน</h6>
        <ul>
            <li>ลากเมาส์เพื่อหมุนโมเดล</li>
            <li>ใช้ล้อเมาส์ซูมเข้า-ออก</li>
            <li>กดคลิกขวาเพื่อเลื่อนมุมมอง</li>
            <li>กดปุ่มควบคุมด้านล่างเพื่อใช้ฟีเจอร์เพิ่มเติม</li>
        </ul>
    </div>

    <!-- A-Frame Scene -->
    <a-scene 
        background="color: #87CEEB"
        vr-mode-ui="enabled: false"
        device-orientation-permission-ui="enabled: false"
        id="scene">
        
        <!-- Assets -->
        <a-assets>
            <!-- Models will be loaded dynamically -->
        </a-assets>

        <!-- Environment -->
        <a-plane 
            position="0 -2 0" 
            rotation="-90 0 0" 
            width="20" 
            height="20" 
            color="#ffffff"
            shadow="receive: true">
        </a-plane>

        <!-- Lighting -->
        <a-light type="ambient" color="#ffffff" intensity="0.4"></a-light>
        <a-light 
            type="directional" 
            position="5 5 5" 
            color="#ffffff" 
            intensity="0.8"
            shadow="cast: true; mapSize: 2048 2048">
        </a-light>
        <a-light type="point" position="-5 5 -5" color="#4ECDC4" intensity="0.3"></a-light>

        <!-- 3D Model (will be loaded dynamically) -->
        <a-entity 
            id="mainModel"
            position="0 0 0"
            rotation="0 0 0"
            scale="2 2 2"
            shadow="cast: true; receive: false"
            animation="property: rotation; to: 0 360 0; loop: true; dur: 20000; easing: linear">
        </a-entity>

        <!-- Camera with controls -->
        <a-camera 
            id="camera"
            position="0 1.6 5"
            look-controls="enabled: true"
            wasd-controls="enabled: false">
        </a-camera>

    </a-scene>

    <!-- Control Panel -->
    <div class="control-panel">
        <button class="control-btn" onclick="toggleAnimation()">
            <i class="fas fa-play" id="animationIcon"></i>
            <span id="animationText">หยุดหมุน</span>
        </button>
        <button class="control-btn" onclick="resetView()">
            <i class="fas fa-refresh"></i>
            รีเซ็ตมุมมอง
        </button>
        <button class="control-btn secondary" onclick="goBack()">
            <i class="fas fa-arrow-left"></i>
            กลับไปแก้ไข
        </button>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Global variables
        let isAnimating = true;
        let modelData = null;

        // Model information mapping
        const modelInfo = {
            'apple_green_smile': {
                type: 'แอปเปิล',
                color: 'เขียว',
                expression: 'ยิ้ม'
            },
            'apple_green_angry': {
                type: 'แอปเปิล',
                color: 'เขียว',
                expression: 'โกรธ'
            },
            'apple_green_sad': {
                type: 'แอปเปิล',
                color: 'เขียว',
                expression: 'เศร้า'
            },
            'apple_red_smile': {
                type: 'แอปเปิล',
                color: 'แดง',
                expression: 'ยิ้ม'
            },
            'apple_red_angry': {
                type: 'แอปเปิล',
                color: 'แดง',
                expression: 'โกรธ'
            },
            'apple_red_sad': {
                type: 'แอปเปิล',
                color: 'แดง',
                expression: 'เศร้า'
            }
        };

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Get model parameter from URL
            const urlParams = new URLSearchParams(window.location.search);
            const modelFileName = urlParams.get('model');
            
            if (modelFileName) {
                loadModel(modelFileName);
            } else {
                showError('ไม่พบข้อมูลโมเดลที่ต้องการแสดง');
            }
        });

        function loadModel(modelFileName) {
            console.log('Loading model:', modelFileName);
            
            // Extract model key from filename
            const modelKey = modelFileName.replace('.glb', '');
            modelData = modelInfo[modelKey];
            
            if (!modelData) {
                console.warn('Model info not found for:', modelKey);
                modelData = {
                    type: 'ไม่ทราบ',
                    color: 'ไม่ทราบ',
                    expression: 'ไม่ทราบ'
                };
            }
            
            // Update info panel
            updateInfoPanel(modelKey, modelFileName);
            
            // Try to load the actual GLB file
            const modelPath = `./models/${modelFileName}`;
            
            // Check if the model file exists by trying to load it
            loadGLBModel(modelPath)
                .then(() => {
                    hideLoading();
                    console.log('Model loaded successfully');
                })
                .catch((error) => {
                    console.warn('GLB file not found, using fallback 3D model');
                    createFallbackModel(modelKey);
                    hideLoading();
                });
        }

        function loadGLBModel(modelPath) {
            return new Promise((resolve, reject) => {
                const scene = document.querySelector('a-scene');
                const assets = document.querySelector('a-assets');
                const mainModel = document.querySelector('#mainModel');
                
                // Create asset reference
                const modelAsset = document.createElement('a-asset-item');
                modelAsset.setAttribute('id', 'mainModelAsset');
                modelAsset.setAttribute('src', modelPath);
                
                // Handle load events
                modelAsset.addEventListener('loaded', () => {
                    console.log('Asset loaded successfully');
                    
                    // Add GLTF model component to entity
                    mainModel.setAttribute('gltf-model', '#mainModelAsset');
                    resolve();
                });
                
                modelAsset.addEventListener('error', (error) => {
                    console.error('Failed to load asset:', error);
                    reject(error);
                });
                
                // Add to assets
                assets.appendChild(modelAsset);
                
                // Set timeout for loading
                setTimeout(() => {
                    reject(new Error('Model loading timeout'));
                }, 10000);
            });
        }

        function createFallbackModel(modelKey) {
            const mainModel = document.querySelector('#mainModel');
            
            // Parse model parameters
            const params = modelKey.split('_');
            const fruitType = params[0];
            const color = params[1];
            const expression = params[2];
            
            // Color mapping
            const colors = {
                'green': '#228B22',
                'red': '#DC143C'
            };
            
            const selectedColor = colors[color] || '#FF6B9D';
            
            // Clear existing components
            mainModel.innerHTML = '';
            
            // Create main body (apple shape)
            const body = document.createElement('a-sphere');
            body.setAttribute('radius', '1.2');
            body.setAttribute('color', selectedColor);
            body.setAttribute('position', '0 0 0');
            body.setAttribute('scale', '1 0.9 1');
            body.setAttribute('metalness', '0.2');
            body.setAttribute('roughness', '0.8');
            mainModel.appendChild(body);
            
            if (fruitType === 'apple') {
                // Add stem
                const stem = document.createElement('a-cylinder');
                stem.setAttribute('radius', '0.05');
                stem.setAttribute('height', '0.4');
                stem.setAttribute('color', '#8B4513');
                stem.setAttribute('position', '0 1.4 0');
                mainModel.appendChild(stem);
                
                // Add leaf
                const leaf = document.createElement('a-sphere');
                leaf.setAttribute('radius', '0.3');
                leaf.setAttribute('color', '#228B22');
                leaf.setAttribute('position', '0.2 1.3 0');
                leaf.setAttribute('scale', '2 0.5 0.5');
                leaf.setAttribute('rotation', '0 0 30');
                mainModel.appendChild(leaf);
            }
            
            // Add face elements based on expression
            createFace(mainModel, expression);
        }

        function createFace(parentEntity, expression) {
            // Eyes
            const leftEye = document.createElement('a-sphere');
            leftEye.setAttribute('radius', '0.15');
            leftEye.setAttribute('color', 'black');
            leftEye.setAttribute('position', '-0.3 0.3 1.0');
            parentEntity.appendChild(leftEye);
            
            const rightEye = document.createElement('a-sphere');
            rightEye.setAttribute('radius', '0.15');
            rightEye.setAttribute('color', 'black');
            rightEye.setAttribute('position', '0.3 0.3 1.0');
            parentEntity.appendChild(rightEye);
            
            // Mouth based on expression
            if (expression === 'smile') {
                // Smiling mouth
                const mouth = document.createElement('a-torus');
                mouth.setAttribute('radius-outer', '0.3');
                mouth.setAttribute('radius-tubular', '0.05');
                mouth.setAttribute('color', 'black');
                mouth.setAttribute('position', '0 -0.2 1.0');
                mouth.setAttribute('rotation', '0 0 180');
                parentEntity.appendChild(mouth);
                
                // Add cheeks
                const leftCheek = document.createElement('a-sphere');
                leftCheek.setAttribute('radius', '0.1');
                leftCheek.setAttribute('color', '#ff9999');
                leftCheek.setAttribute('opacity', '0.7');
                leftCheek.setAttribute('position', '-0.6 0 0.8');
                parentEntity.appendChild(leftCheek);
                
                const rightCheek = document.createElement('a-sphere');
                rightCheek.setAttribute('radius', '0.1');
                rightCheek.setAttribute('color', '#ff9999');
                rightCheek.setAttribute('opacity', '0.7');
                rightCheek.setAttribute('position', '0.6 0 0.8');
                parentEntity.appendChild(rightCheek);
                
            } else if (expression === 'angry') {
                // Angry mouth
                const mouth = document.createElement('a-torus');
                mouth.setAttribute('radius-outer', '0.2');
                mouth.setAttribute('radius-tubular', '0.05');
                mouth.setAttribute('color', 'black');
                mouth.setAttribute('position', '0 -0.3 1.0');
                parentEntity.appendChild(mouth);
                
                // Angry eyebrows
                const leftBrow = document.createElement('a-box');
                leftBrow.setAttribute('width', '0.4');
                leftBrow.setAttribute('height', '0.05');
                leftBrow.setAttribute('depth', '0.05');
                leftBrow.setAttribute('color', 'black');
                leftBrow.setAttribute('position', '-0.3 0.5 1.0');
                leftBrow.setAttribute('rotation', '0 0 30');
                parentEntity.appendChild(leftBrow);
                
                const rightBrow = document.createElement('a-box');
                rightBrow.setAttribute('width', '0.4');
                rightBrow.setAttribute('height', '0.05');
                rightBrow.setAttribute('depth', '0.05');
                rightBrow.setAttribute('color', 'black');
                rightBrow.setAttribute('position', '0.3 0.5 1.0');
                rightBrow.setAttribute('rotation', '0 0 -30');
                parentEntity.appendChild(rightBrow);
                
            } else if (expression === 'sad') {
                // Sad mouth
                const mouth = document.createElement('a-torus');
                mouth.setAttribute('radius-outer', '0.25');
                mouth.setAttribute('radius-tubular', '0.05');
                mouth.setAttribute('color', 'black');
                mouth.setAttribute('position', '0 -0.4 1.0');
                mouth.setAttribute('rotation', '0 0 0');
                parentEntity.appendChild(mouth);
                
                // Tears
                const leftTear = document.createElement('a-sphere');
                leftTear.setAttribute('radius', '0.08');
                leftTear.setAttribute('color', '#87ceeb');
                leftTear.setAttribute('opacity', '0.8');
                leftTear.setAttribute('position', '-0.3 0.1 1.0');
                leftTear.setAttribute('scale', '0.5 1 0.5');
                parentEntity.appendChild(leftTear);
                
                const rightTear = document.createElement('a-sphere');
                rightTear.setAttribute('radius', '0.08');
                rightTear.setAttribute('color', '#87ceeb');
                rightTear.setAttribute('opacity', '0.8');
                rightTear.setAttribute('position', '0.3 0.1 1.0');
                rightTear.setAttribute('scale', '0.5 1 0.5');
                parentEntity.appendChild(rightTear);
            }
        }

        function updateInfoPanel(modelKey, fileName) {
            document.getElementById('modelType').textContent = modelData.type;
            document.getElementById('modelColor').textContent = modelData.color;
            document.getElementById('modelExpression').textContent = modelData.expression;
            document.getElementById('modelFile').textContent = fileName;
        }

        function hideLoading() {
            const loadingScreen = document.getElementById('loadingScreen');
            loadingScreen.classList.add('hide');
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 500);
        }

        function showError(message) {
            hideLoading();
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.querySelector('p').textContent = message;
            errorMessage.style.display = 'block';
        }

        function toggleAnimation() {
            const mainModel = document.querySelector('#mainModel');
            const animationIcon = document.getElementById('animationIcon');
            const animationText = document.getElementById('animationText');
            
            if (isAnimating) {
                // Stop animation
                mainModel.removeAttribute('animation');
                animationIcon.className = 'fas fa-play';
                animationText.textContent = 'เริ่มหมุน';
                isAnimating = false;
            } else {
                // Start animation
                mainModel.setAttribute('animation', 'property: rotation; to: 0 360 0; loop: true; dur: 20000; easing: linear');
                animationIcon.className = 'fas fa-pause';
                animationText.textContent = 'หยุดหมุน';
                isAnimating = true;
            }
        }

        function resetView() {
            const camera = document.querySelector('#camera');
            camera.setAttribute('position', '0 1.6 5');
            camera.setAttribute('rotation', '0 0 0');
            
            // Show notification
            showNotification('รีเซ็ตมุมมองสำเร็จ!');
        }

        function goBack() {
            // Go back to customization page
            window.history.back();
        }

        function showNotification(message) {
            // Create temporary notification
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(45deg, #28a745, #20c997);
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                font-weight: 500;
                z-index: 3000;
                min-width: 250px;
                transform: translateX(100%);
                transition: transform 0.3s ease;
            `;
            notification.innerHTML = `<i class="fas fa-check-circle me-2"></i>${message}`;
            
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Hide notification
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Handle A-Frame scene loading
        document.querySelector('a-scene').addEventListener('loaded', function() {
            console.log('A-Frame scene loaded');
        });

        // Handle model loading events
        document.querySelector('#mainModel').addEventListener('model-loaded', function() {
            console.log('Model loaded in A-Frame');
        });

        // Handle VR mode
        document.querySelector('a-scene').addEventListener('enter-vr', function() {
            console.log('Entered VR mode');
        });

        document.querySelector('a-scene').addEventListener('exit-vr', function() {
            console.log('Exited VR mode');
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(event) {
            switch(event.key) {
                case ' ': // Spacebar - toggle animation
                    event.preventDefault();
                    toggleAnimation();
                    break;
                case 'r': // R key - reset view
                case 'R':
                    event.preventDefault();
                    resetView();
                    break;
                case 'Escape': // Escape - go back
                    event.preventDefault();
                    goBack();
                    break;
            }
        });

        // Mobile touch gestures
        let touchStartY = 0;
        let touchStartX = 0;

        document.addEventListener('touchstart', function(event) {
            touchStartY = event.touches[0].clientY;
            touchStartX = event.touches[0].clientX;
        });

        document.addEventListener('touchend', function(event) {
            const touchEndY = event.changedTouches[0].clientY;
            const touchEndX = event.changedTouches[0].clientX;
            
            const deltaY = touchStartY - touchEndY;
            const deltaX = touchStartX - touchEndX;
            
            // Swipe up gesture - toggle animation
            if (Math.abs(deltaY) > Math.abs(deltaX) && deltaY > 100) {
                toggleAnimation();
            }
            
            // Swipe down gesture - reset view
            if (Math.abs(deltaY) > Math.abs(deltaX) && deltaY < -100) {
                resetView();
            }
            
            // Swipe right gesture - go back
            if (Math.abs(deltaX) > Math.abs(deltaY) && deltaX < -100) {
                goBack();
            }
        });
    </script>
</body>
</html>