<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Face Attendance - Sistem Absensi Wajah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #4361ee;
            --secondary-blue: #3a56d4;
            --light-blue: #4895ef;
            --lighter-blue: #4cc9f0;
            --success: #4ade80;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #94a3b8;
            --shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #e6f0ff 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: var(--dark);
        }
        
        .container {
            width: 100%;
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 30px;
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow);
            overflow: hidden;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(160deg, var(--primary-blue) 0%, var(--lighter-blue) 100%);
            color: white;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            top: -150px;
            right: -100px;
        }
        
        .sidebar::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            bottom: -80px;
            left: -50px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            z-index: 1;
        }
        
        .logo-icon {
            font-size: 32px;
            background: rgba(255, 255, 255, 0.2);
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        
        .logo-text h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        
        .logo-text p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .instructions {
            margin-top: 20px;
            z-index: 1;
        }
        
        .instructions h3 {
            font-size: 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .instructions ul {
            list-style: none;
        }
        
        .instructions li {
            margin-bottom: 18px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 15px;
            line-height: 1.5;
        }
        
        .instructions li i {
            background: rgba(255, 255, 255, 0.2);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        /* Main Content Styles */
        .main-content {
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
        }
        
        .header {
            margin-bottom: 30px;
        }
        
        .header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }
        
        .header p {
            color: var(--gray);
            font-size: 16px;
        }
        
        /* Camera Section */
        .camera-section {
            margin-bottom: 30px;
        }
        
        .camera-container {
            position: relative;
            width: 100%;
            height: 320px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            background: #1e293b;
            margin-bottom: 20px;
        }
        
        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scaleX(-1); /* Mirror effect for front camera */
        }
        
        #canvas {
            display: none;
        }
        
        .camera-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            pointer-events: none;
        }
        
        .detection-indicator {
            align-self: flex-end;
            background: rgba(76, 175, 80, 0.9);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(-10px);
            transition: var(--transition);
        }
        
        .detection-indicator.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        .face-counter {
            align-self: center;
            background: rgba(255, 255, 255, 0.9);
            color: var(--dark);
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Controls Section */
        .controls {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .btn {
            padding: 16px 32px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            outline: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--light-blue) 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.4);
        }
        
        .btn-primary:disabled {
            background: var(--gray);
            box-shadow: none;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-secondary {
            background: white;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
        }
        
        .btn-secondary:hover {
            background: var(--primary-blue);
            color: white;
        }
        
        .btn-danger {
            background: white;
            color: var(--danger);
            border: 2px solid var(--danger);
        }
        
        .btn-danger:hover {
            background: var(--danger);
            color: white;
        }
        
        /* Loading State */
        .loading {
            display: none;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 30px;
            background: #f8fafc;
            border-radius: 20px;
            margin-bottom: 30px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.2); }
            70% { box-shadow: 0 0 0 20px rgba(67, 97, 238, 0); }
            100% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0); }
        }
        
        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(67, 97, 238, 0.1);
            border-top: 4px solid var(--primary-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading p {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }
        
        /* Results Section */
        .result {
            display: none;
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 30px;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .result.success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-left: 5px solid var(--success);
        }
        
        .result.error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left: 5px solid var(--danger);
        }
        
        .result-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }
        
        .result-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .success .result-icon {
            background: var(--success);
            color: white;
        }
        
        .error .result-icon {
            background: var(--danger);
            color: white;
        }
        
        .result-header h3 {
            font-size: 20px;
            font-weight: 700;
        }
        
        .result-content {
            padding-left: 52px;
        }
        
        .result-content p {
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .result-details {
            background: rgba(255, 255, 255, 0.7);
            padding: 15px;
            border-radius: 12px;
            margin-top: 15px;
        }
        
        /* Navigation */
        .navigation {
            display: flex;
            justify-content: center;
        }
        
        .btn-back {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            background: white;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            border-radius: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .btn-back:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.3);
        }
        
        /* Face Box */
        .face-box {
            position: absolute;
            border: 3px solid var(--success);
            border-radius: 12px;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.3), 0 0 25px rgba(76, 175, 80, 0.5);
            pointer-events: none;
            animation: pulseBox 2s infinite;
        }

        .mobile-instructions {
            display: none;
        }

        .notification-indicator {
            align-self: center;
            background: rgba(255, 255, 255, 0.95);
            color: var(--dark);
            padding: 12px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            opacity: 0;
            transform: translateY(20px);
            transition: var(--transition);
            max-width: 90%;
            text-align: center;
            pointer-events: none;
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            z-index: 10;
        }

        .notification-indicator.active {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .notification-indicator.success {
            background: rgba(74, 222, 128, 0.95);
            color: white;
            border: 2px solid var(--success);
        }

        .notification-indicator.error {
            background: rgba(239, 68, 68, 0.95);
            color: white;
            border: 2px solid var(--danger);
        }

        .notification-indicator.warning {
            background: rgba(245, 158, 11, 0.95);
            color: white;
            border: 2px solid var(--warning);
        }

        .notification-indicator.info {
            background: rgba(67, 97, 238, 0.95);
            color: white;
            border: 2px solid var(--primary-blue);
        }
        
        /* Countdown styling */
        .face-counter .fa-clock {
            color: var(--warning);
            animation: pulse 1s infinite;
        }

        .face-counter .fa-hourglass-half {
            color: var(--warning);
            animation: rotate 2s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes pulseBox {
            0% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(76, 175, 80, 0); }
            100% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0); }
        }
        
        /* Responsive */
        @media (max-width: 900px) {
            .container {
                grid-template-columns: 1fr;
                max-width: 600px;
            }
            
            .sidebar {
                padding: 30px 20px;
            }

            .sidebar .instructions {
                display: none;
            }

            .mobile-instructions {
                display: block;
                background: linear-gradient(160deg, var(--primary-blue) 0%, var(--lighter-blue) 100%);
                color: white;
                padding: 25px;
                border-radius: 20px;
                margin: 20px 0;
                box-shadow: var(--shadow);
            }

            .mobile-instructions h3 {
                font-size: 18px;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .mobile-instructions ul {
                list-style: none;
            }

            .mobile-instructions li {
                margin-bottom: 15px;
                display: flex;
                align-items: flex-start;
                gap: 12px;
                font-size: 14px;
                line-height: 1.5;
            }

            .mobile-instructions li i {
                background: rgba(255, 255, 255, 0.2);
                width: 22px;
                height: 22px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                margin-top: 2px;
                font-size: 12px;
            }
        }
        
        @media (max-width: 600px) {
            .controls {
                flex-direction: column;
            }
            
            .btn {
                justify-content: center;
            }
            
            .camera-container {
                height: 280px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="logo-text">
                    <h1>Face Attendance</h1>
                    <p>Sistem Absensi Digital</p>
                </div>
            </div>
            
            <div class="instructions">
                <h3><i class="fas fa-info-circle"></i> Panduan Penggunaan</h3>
                <ul>
                    <li>
                        <i class="fas fa-camera"></i>
                        <span>Pastikan wajah Anda terlihat jelas di area kamera</span>
                    </li>
                    <li>
                        <i class="fas fa-lightbulb"></i>
                        <span>Cahaya yang cukup akan meningkatkan akurasi</span>
                    </li>
                    <li>
                        <i class="fas fa-user"></i>
                        <span>Jaga jarak yang wajar dari kamera (50-100 cm)</span>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <span>Foto akan otomatis diambil setelah 5 detik wajah terdeteksi</span>
                    </li>
                    <li>
                        <i class="fas fa-hand-pointer"></i>
                        <span>Klik "Ambil Foto" untuk mengambil foto manual dan menghentikan countdown</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Sistem akan mengirim data absensi setelah foto diambil</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>Absensi Dengan Wajah</h2>
                <p>Ambil foto wajah Anda untuk melakukan absensi secara otomatis</p>
            </div>
            
            <!-- Camera Section -->
            <div class="camera-section">
                <div class="camera-container">
                    <video id="video" autoplay playsinline></video>
                    <canvas id="canvas"></canvas>
                    
                    <div class="camera-overlay">
                        <div class="detection-indicator" id="detectionIndicator">
                            <i class="fas fa-check-circle"></i>
                            Wajah Terdeteksi
                        </div>
                        
                        <div class="notification-indicator" id="notificationIndicator">
                            <i class="fas fa-info-circle"></i>
                            <span id="notificationText">Pesan Notifikasi</span>
                        </div>
                        <div class="face-counter" id="faceCounter">
                            <i class="fas fa-users"></i>
                            <span>0 Wajah Terdeteksi</span>
                        </div>
                    </div>
                </div>
                
                <div class="controls">
                    <button class="btn btn-primary" id="captureBtn" onclick="captureFace()" disabled>
                        <i class="fas fa-camera"></i>
                        Ambil Foto
                    </button>
                    <button class="btn btn-secondary" id="startBtn" onclick="startCamera()">
                        <i class="fas fa-video"></i>
                        Mulai Kamera
                    </button>
                    <button class="btn btn-danger" id="stopBtn" onclick="stopCamera()" style="display:none;">
                        <i class="fas fa-stop"></i>
                        Stop Kamera
                    </button>
                </div>
            </div>
            
            <!-- Loading State -->
            <div class="loading" id="loading">
                <div class="loading-spinner"></div>
                <p>Mengidentifikasi wajah...</p>
                <p style="font-size: 14px; color: var(--gray);">Harap tunggu sebentar</p>
            </div>
            
            <!-- Results Section -->
            <div id="result" class="result"></div>

            <!-- Instruksi untuk Mobile -->
            <div class="mobile-instructions">
                <h3><i class="fas fa-info-circle"></i> Panduan Penggunaan</h3>
                <ul>
                    <li><i class="fas fa-camera"></i><span>Pastikan wajah Anda terlihat jelas di area kamera</span></li>
                    <li><i class="fas fa-lightbulb"></i><span>Cahaya yang cukup akan meningkatkan akurasi</span></li>
                    <li><i class="fas fa-user"></i><span>Jaga jarak yang wajar dari kamera (50-100 cm)</span></li>
                    <li><i class="fas fa-clock"></i><span>Foto akan otomatis diambil setelah 5 detik wajah terdeteksi</span></li>
                    <li><i class="fas fa-hand-pointer"></i><span>Klik "Ambil Foto" untuk mengambil foto manual dan menghentikan countdown</span></li>
                    <li><i class="fas fa-check-circle"></i><span>Sistem akan mengirim data absensi setelah foto diambil</span></li>
                </ul>
            </div>
            
            <!-- Navigation -->
            <div class="navigation">
                <a href="{{ route('dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Load TensorFlow.js and MediaPipe Face Detection -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs-core"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs-backend-webgl"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision"></script>
    
    <script>
        // DOM Elements
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const detectionIndicator = document.getElementById('detectionIndicator');
        const faceCounter = document.getElementById('faceCounter');
        const captureBtn = document.getElementById('captureBtn');
        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const loading = document.getElementById('loading');
        const resultDiv = document.getElementById('result');
        
        // State variables
        let faceDetector;
        let isCameraOn = false;
        let stream;
        let animationFrameId;
        let faceCount = 0;
        let autoCaptureTimer = null; // Timer untuk auto-capture
        let isAutoCaptureActive = false; // Status auto-capture
        let autoCaptureTime = 5; // Waktu dalam detik
        let countdownValue = autoCaptureTime; // Nilai countdown
        let faceDetectionStableCount = 0; // Counter untuk stabilitas deteksi wajah

        window.isCooldownActive = false; // Status cooldown global
        window.cooldownEndTime = null; // Waktu berakhir cooldown
        
        // Mapping nama ke UID
        const nameToUid = {
            'Bimo Satria Pandapotan': '9B796950',
            'Abiyyu Abdiffatir Al Majid': '93E8AAAA',
            'Oktajoan Yuna Diartanael': 'CBC93250',
            'Aryan Rostiansyach': 'DBF66850',
            'Dzulqornain Syaiful Haq': 'F31CA2AA'
        };

        // Fungsi untuk memulai countdown auto-capture
        function startAutoCaptureCountdown() {
            if (isAutoCaptureActive) return; // Jika sudah aktif, jangan mulai lagi
            
            isAutoCaptureActive = true;
            countdownValue = autoCaptureTime;
            
            // Update countdown setiap detik
            autoCaptureTimer = setInterval(() => {
                countdownValue--;
                
                if (countdownValue > 0) {
                    // Update face counter untuk menampilkan countdown
                    faceCounter.innerHTML = `<i class="fas fa-clock"></i><span>${countdownValue} detik lagi...</span>`;
                }
                
                if (countdownValue <= 0) {
                    clearInterval(autoCaptureTimer);
                    isAutoCaptureActive = false;
                    // Auto-capture foto
                    captureFaceAuto();
                }
            }, 1000);
        }

        // Fungsi untuk menghentikan countdown auto-capture
        function stopAutoCaptureCountdown() {
            if (autoCaptureTimer) {
                clearInterval(autoCaptureTimer);
                autoCaptureTimer = null;
            }
            isAutoCaptureActive = false;
            
            // Reset face counter ke jumlah wajah terdeteksi
            updateFaceCounterDisplay();
        }

        // Fungsi untuk update tampilan face counter
        function updateFaceCounterDisplay() {
            faceCounter.innerHTML = `<i class="fas fa-users"></i><span>${faceCount} Wajah Terdeteksi</span>`;
        }

        // Fungsi untuk auto-capture (dipanggil oleh timer)
        function captureFaceAuto() {
            showNotification('📸 Mengambil foto otomatis...', 'info', 2000);
            captureFaceProcess();
        }
        
        // Initialize face detector
        async function initFaceDetector() {
            try {
                await tf.ready();
                console.log('TensorFlow.js ready');
                
                // Dynamically import MediaPipe
                const vision = await import('https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@latest/+esm');
                const { FaceDetector, FilesetResolver } = vision;
                
                const filesetResolver = await FilesetResolver.forVisionTasks(
                    "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@latest/wasm"
                );
                
                faceDetector = await FaceDetector.createFromOptions(filesetResolver, {
                    baseOptions: {
                        modelAssetPath: "https://storage.googleapis.com/mediapipe-models/face_detector/blaze_face_short_range/float16/1/blaze_face_short_range.tflite",
                        delegate: "GPU"
                    },
                    runningMode: "VIDEO"
                });
                
                console.log('Face detector loaded successfully');
                return true;
            } catch (error) {
                console.error('Error loading face detector:', error);
                showResult('error', 'Gagal memuat detektor wajah. Silakan refresh halaman.', '', '');
                return false;
            }
        }
        
        // Start camera
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: { ideal: 640 },
                        height: { ideal: 480 },
                        facingMode: 'user'
                    }
                });
                
                video.srcObject = stream;
                isCameraOn = true;
                
                // Update UI
                startBtn.style.display = 'none';
                stopBtn.style.display = 'flex';
                captureBtn.disabled = true;
                
                // Initialize face detector
                const detectorLoaded = await initFaceDetector();
                
                if (detectorLoaded) {
                    detectFaces();
                }
            } catch (error) {
                console.error('Error accessing camera:', error);
                showResult('error', 'Tidak dapat mengakses kamera. Pastikan Anda memberikan izin kamera.', '', '');
            }
        }
        
        // Detect faces
        async function detectFaces() {
            if (!isCameraOn || !faceDetector) return;
            
            // Cek apakah masih dalam cooldown
            if (window.isCooldownActive) {
                
                // Tetap lanjutkan deteksi tapi jangan mulai auto-capture
                animationFrameId = requestAnimationFrame(detectFaces);
                return;
            }
            
            try {
                const startTimeMs = performance.now();
                const detectionResult = await faceDetector.detectForVideo(video, startTimeMs);
                
                // Remove previous face boxes
                const existingBoxes = document.querySelectorAll('.face-box');
                existingBoxes.forEach(box => box.remove());
                
                // Update face count
                const newFaceCount = detectionResult.detections ? detectionResult.detections.length : 0;
                
                // Jika jumlah wajah berubah
                if (newFaceCount !== faceCount) {
                    faceCount = newFaceCount;
                    updateFaceCounterDisplay();
                    
                    // Reset stabilitas counter jika wajah berubah
                    faceDetectionStableCount = 0;
                    
                    // Jika ada wajah terdeteksi dan auto-capture belum aktif DAN TIDAK DALAM COOLDOWN
                    if (faceCount > 0 && !isAutoCaptureActive && !window.isCooldownActive) {
                        // Tunggu 2 detik untuk stabilitas sebelum mulai countdown
                        faceDetectionStableCount = 0;
                    }
                    
                    // Jika tidak ada wajah terdeteksi, hentikan auto-capture
                    if (faceCount === 0) {
                        stopAutoCaptureCountdown();
                        faceDetectionStableCount = 0;
                    }
                } else if (faceCount > 0) {
                    // Jika wajah tetap terdeteksi, tambah counter stabilitas
                    faceDetectionStableCount++;
                    
                    // Jika sudah stabil selama 2 detik (sekitar 60 frame) dan auto-capture belum aktif DAN TIDAK DALAM COOLDOWN
                    if (faceDetectionStableCount > 60 && !isAutoCaptureActive && !window.isCooldownActive) {
                        startAutoCaptureCountdown();
                    }
                }
                
                // Show/hide detection indicator
                if (faceCount > 0) {
                    detectionIndicator.classList.add('active');
                    captureBtn.disabled = false;
                } else {
                    detectionIndicator.classList.remove('active');
                    captureBtn.disabled = true;
                }
                
                // Draw face boxes
                if (detectionResult.detections && detectionResult.detections.length > 0) {
                    detectionResult.detections.forEach((detection, index) => {
                        const boundingBox = detection.boundingBox;
                        const videoRect = video.getBoundingClientRect();
                        const containerRect = document.querySelector('.camera-container').getBoundingClientRect();
                        
                        const x = videoRect.width - ((boundingBox.originX / video.videoWidth) * videoRect.width) - ((boundingBox.width / video.videoWidth) * videoRect.width);
                        const y = (boundingBox.originY / video.videoHeight) * videoRect.height;
                        const width = (boundingBox.width / video.videoWidth) * videoRect.width;
                        const height = (boundingBox.height / video.videoHeight) * videoRect.height;
                        
                        const faceBox = document.createElement('div');
                        faceBox.className = 'face-box';
                        faceBox.style.left = `${x}px`;
                        faceBox.style.top = `${y}px`;
                        faceBox.style.width = `${width}px`;
                        faceBox.style.height = `${height}px`;
                        
                        document.querySelector('.camera-container').appendChild(faceBox);
                    });
                }
                
                // Continue detection
                animationFrameId = requestAnimationFrame(detectFaces);
            } catch (error) {
                console.error('Error detecting faces:', error);
                setTimeout(detectFaces, 100);
            }
        }
        
        // Capture face (dipanggil dari tombol)
        async function captureFace() {
            // Hentikan auto-capture countdown jika aktif
            if (isAutoCaptureActive) {
                stopAutoCaptureCountdown();
                showNotification('📸 Mengambil foto manual...', 'info', 2000);
            } else {
                showNotification('📸 Mengambil foto wajah...', 'info', 2000);
            }
            
            if (!isCameraOn) {
                showResult('error', 'Silakan nyalakan kamera terlebih dahulu', '', '');
                showNotification('❌ Gagal mengambil foto', 'error', 3000);
                return;
            }
            
            captureFaceProcess();
        }

        // Proses capture foto (digunakan oleh manual dan auto)
        async function captureFaceProcess() {
            // Set canvas size
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Draw video frame to canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Get image data
            const imageData = canvas.toDataURL('image/jpeg', 0.9);
            
            // Show loading state
            loading.style.display = 'flex';
            resultDiv.style.display = 'none';
            captureBtn.disabled = true;
            detectionIndicator.classList.remove('active');
            
            // Reset auto-capture
            stopAutoCaptureCountdown();
            isAutoCaptureActive = false;
            faceDetectionStableCount = 0;
            
            // AKTIFKAN COOLDOWN
            window.isCooldownActive = true;
            window.cooldownEndTime = Date.now() + (5 * 1000); // 5 detik cooldown
            
            
            try {
                // Convert base64 to blob
                const blob = await base64ToBlob(imageData);
                
                // Send to Laravel controller
                await sendToLaravelController(blob);
            } catch (error) {
                console.error('Error capturing face:', error);
                showResult('error', `Terjadi kesalahan: ${error.message}`, '', '');
                loading.style.display = 'none';
                captureBtn.disabled = false;
                
                // Reset cooldown jika error
                window.isCooldownActive = false;
                window.cooldownEndTime = null;
            }
        }
        
        // Convert base64 to blob
        function base64ToBlob(base64) {
            const byteString = atob(base64.split(',')[1]);
            const mimeString = base64.split(',')[0].split(':')[1].split(';')[0];
            const ab = new ArrayBuffer(byteString.length);
            const ia = new Uint8Array(ab);
            
            for (let i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            
            return new Blob([ab], { type: mimeString });
        }
        
        // Send to Laravel controller dengan timeout
        async function sendToLaravelController(imageBlob) {
            const formData = new FormData();
            formData.append('image', imageBlob, 'face.jpg');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            // Buat promise untuk timeout
            const timeoutPromise = new Promise((_, reject) => {
                setTimeout(() => {
                    reject(new Error('Timeout: Server tidak merespons dalam 8 detik'));
                }, 8000); // 8 detik timeout
            });
            
            // Buat promise untuk fetch request
            const fetchPromise = fetch('{{ route("face.process") }}', {
                method: 'POST',
                body: formData,
            }).then(async (response) => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Gagal mengenali wajah');
                }
                return data;
            });
            
            try {
                // Race antara fetch dan timeout
                const data = await Promise.race([fetchPromise, timeoutPromise]);
                
                if (data.status === 'success') {
                    // Periksa jika wajah tidak dikenali
                    if (data.detected_name === 'Unknown') {
                        showResult('error',
                            'Absensi Gagal',
                            `Wajah tidak dikenali oleh sistem.<br>
                            Nama: <strong>-</strong><br>
                            UID: <strong>-</strong><br>
                            Status: <span style="color: var(--danger); font-weight: bold;">Gagal</span><br><br>
                            <small>Silakan pastikan wajah Anda terlihat jelas dan coba lagi.</small>`,
                            'Unknown'
                        );
                    } else {
                        // Kirim ke endpoint absensi hanya jika wajah dikenali
                        await sendToAbsensiEndpoint(data.detected_name);
                    }
                } else {
                    showResult('error', 'Gagal mengenali wajah', data.message || '', '');
                }
            } catch (error) {
                // Tangani error timeout atau error lainnya
                if (error.message.includes('Timeout')) {
                    showResult('error',
                        'Waktu Habis',
                        `Server tidak merespons dalam waktu 8 detik.<br>
                        <small>Silakan coba lagi nanti atau periksa koneksi jaringan Anda.</small>`,
                        ''
                    );
                } else {
                    showResult('error', 'Gagal memproses wajah', error.message, '');
                }
                
                // Log error untuk debugging
                console.error('Error in sendToLaravelController:', error);
            }
        }
        
        // Send to Laravel controller untuk proses lengkap
        async function sendToAbsensiEndpoint(detectedName) {
            showNotification('📤 Mengirim data absensi...', 'info', 2000);
            
            try {
                const formData = new FormData();
                const imageBlob = await base64ToBlob(canvas.toDataURL('image/jpeg', 0.9));
                
                formData.append('image', imageBlob, 'face.jpg');
                formData.append('detected_name', detectedName);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                
                // Kirim ke controller Laravel untuk diproses
                const response = await fetch('{{ route("face.process") }}', {
                    method: 'POST',
                    body: formData,
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    showResult('success', 
                        `Absensi berhasil!`,
                        `Sistem telah mengenali wajah Anda.<br>
                        Nama: <strong>${data.detected_name}</strong><br>
                        UID: <strong>${data.uid}</strong><br>
                        Waktu: <strong>${new Date().toLocaleTimeString()}</strong><br>
                        Status: <span style="color: var(--success);">${data.absensi_message || 'Berhasil'}</span>`,
                        data.detected_name
                    );
                } else if (data.status === 'partial_success') {
                    showResult('warning',
                        'Data tersimpan secara lokal',
                        `Wajah berhasil dikenali, tetapi gagal mengirim ke server absensi.<br>
                        Nama: <strong>${data.detected_name}</strong><br>
                        UID: <strong>${data.uid}</strong><br>
                        Status: <span style="color: var(--warning); font-weight: bold;">Data disimpan lokal</span><br><br>
                        <small>${data.message}</small>`,
                        data.detected_name
                    );
                } else {
                    showResult('error',
                        'Absensi Gagal',
                        `Terjadi kesalahan: ${data.message}`,
                        detectedName || 'Unknown'
                    );
                }
            } catch (error) {
                console.error('Error in sendToAbsensiEndpoint:', error);
                showResult('error',
                    'Koneksi jaringan bermasalah',
                    `Gagal mengirim data ke server.<br>
                    Error: ${error.message}`,
                    detectedName || 'Unknown'
                );
            }
        }
        
        // Show result
        function showResult(type, title, message, detectedName = '') {
            loading.style.display = 'none';
            captureBtn.disabled = false;
            
            // Reset auto-capture state
            stopAutoCaptureCountdown();
            isAutoCaptureActive = false;
            faceDetectionStableCount = 0;
            updateFaceCounterDisplay();

            let cooldownTime = 5; // 5 detik cooldown
            let cooldownActive = true;
            
            const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            const resultTitle = type === 'success' ? '✅ ' + title : '❌ ' + title;
            
            resultDiv.className = `result ${type}`;
            resultDiv.innerHTML = `
                <div class="result-header">
                    <div class="result-icon">
                        <i class="${icon}"></i>
                    </div>
                    <h3>${resultTitle}</h3>
                </div>
                <div class="result-content">
                    <p>${message}</p>
                </div>
            `;
            resultDiv.style.display = 'block';

            // Tampilkan notifikasi di camera overlay
            if (type === 'success') {
                showNotification('✅ Absensi berhasil! Wajah dikenali: ' + detectedName, 'success', 5000);
            } else if (type === 'error') {
                // Ambil nama dari pesan error jika ada
                const nameMatch = message.match(/Nama: <strong>([^<]+)<\/strong>/);
                const nameFromMessage = nameMatch ? nameMatch[1] : 'Wajah tidak dikenali';
                showNotification('❌ Absensi gagal: ' + nameFromMessage, 'error', 5000);
            }

            let cooldownTimer = setTimeout(() => {
                cooldownActive = false;
            }, cooldownTime * 1000);
            
            // Auto-close camera after successful recognition
            if (type === 'success') {
                setTimeout(() => {
                    stopCamera();
                    startBtn.style.display = 'flex';
                    stopBtn.style.display = 'none';
                }, 5000);
            }

            window.isCooldownActive = true;
            window.cooldownEndTime = Date.now() + (cooldownTime * 1000);
            
            // Timer untuk mengakhiri cooldown
            setTimeout(() => {
                window.isCooldownActive = false;
            }, cooldownTime * 1000);
        }
        
        // Stop camera
        function stopCamera() {
            // Hentikan auto-capture timer jika ada
            stopAutoCaptureCountdown();
            isAutoCaptureActive = false;
            faceDetectionStableCount = 0;
            
            // RESET COOLDOWN
            window.isCooldownActive = false;
            window.cooldownEndTime = null;
            
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                isCameraOn = false;
                
                if (animationFrameId) {
                    cancelAnimationFrame(animationFrameId);
                }
                
                // Remove face boxes
                const faceBoxes = document.querySelectorAll('.face-box');
                faceBoxes.forEach(box => box.remove());
                
                video.srcObject = null;
                startBtn.style.display = 'flex';
                stopBtn.style.display = 'none';
                captureBtn.disabled = true;
                detectionIndicator.classList.remove('active');
                updateFaceCounterDisplay();
            }
        }

        // Fungsi untuk menampilkan notifikasi di camera overlay
        function showNotification(message, type = 'info', duration = 5000) {
            const notificationIndicator = document.getElementById('notificationIndicator');
            const notificationText = document.getElementById('notificationText');
            
            // Set pesan dan tipe notifikasi
            notificationText.textContent = message;
            notificationIndicator.className = 'notification-indicator';
            notificationIndicator.classList.add(type);
            
            // Tampilkan notifikasi
            setTimeout(() => {
                notificationIndicator.classList.add('active');
            }, 100);
            
            // Sembunyikan setelah durasi tertentu
            if (duration > 0) {
                setTimeout(() => {
                    hideNotification();
                }, duration);
            }
        }

        // Fungsi untuk menyembunyikan notifikasi
        function hideNotification() {
            const notificationIndicator = document.getElementById('notificationIndicator');
            notificationIndicator.classList.remove('active');
            
            // Reset setelah animasi selesai
            setTimeout(() => {
                notificationIndicator.className = 'notification-indicator';
            }, 300);
        }
        
        // Event listeners
        window.addEventListener('beforeunload', stopCamera);
        
        // Auto-start camera when page loads
        window.addEventListener('DOMContentLoaded', function() {
            startCamera().catch(error => {
                console.error('Failed to start camera:', error);
                startBtn.disabled = false;
                showResult('error', 'Kamera tidak dapat diakses', 'Silakan klik tombol "Mulai Kamera" untuk mencoba lagi.', '');
            });
        });
    </script>
</body>
</html>