<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon - HijauLoka</title>
    <link rel="stylesheet" href="<?= base_url('assets/') ;?>css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php $this->load->view('templates/navbar'); ?>

    <div class="container mx-auto px-4 py-12 md:py-16">
        <div class="max-w-xl mx-auto">
            <!-- 3D Object Container -->
            <div id="3d-container" class="w-48 h-48 mx-auto mb-8"></div>

            <!-- Simple Hero Section -->
            <div class="text-center mb-10">
                <i class="fas fa-seedling text-6xl text-green-500 mb-6"></i>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">Coming Soon</h1>
                <p class="text-gray-600">
                    <?php if (isset($category) && $category === 'seeds'): ?>
                        Kategori benih tanaman akan segera hadir. Kami sedang menyiapkan koleksi benih berkualitas untuk Anda.
                    <?php else: ?>
                        Kategori pot tanaman akan segera hadir. Kami sedang menyiapkan koleksi pot cantik untuk tanaman Anda.
                    <?php endif; ?>
                </p>
            </div>
            
            <!-- Simple Notification Form -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <form id="notifyForm" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" 
                               id="email"
                               placeholder="Masukkan email Anda" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               required>
                    </div>
                    <button type="submit" 
                            class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors">
                        Dapatkan Notifikasi
                    </button>
                </form>
            </div>

            <!-- Simple Action Buttons -->
            <div class="flex flex-col gap-3">
                <a href="<?= base_url('category/plants') ?>" 
                   class="text-center bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors">
                    Lihat Tanaman Hias
                </a>
                <a href="<?= base_url() ?>" 
                   class="text-center bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <style>
        /* Simple animations */
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in {
            animation: fade-in 0.5s ease-out forwards;
        }

        /* Success message */
        .success-message {
            animation: fade-in 0.3s ease-out forwards;
        }

        #3d-container {
            position: relative;
            perspective: 1000px;
        }

        #3d-container canvas {
            width: 100% !important;
            height: 100% !important;
        }
    </style>

    <script>
        // Three.js initialization
        function initThreeJS() {
            const container = document.getElementById('3d-container');
            const width = container.clientWidth;
            const height = container.clientHeight;

            // Scene setup
            const scene = new THREE.Scene();
            scene.background = new THREE.Color(0xf9fafb); // Light gray background

            // Camera setup
            const camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);
            camera.position.z = 5;

            // Renderer setup
            const renderer = new THREE.WebGLRenderer({ antialias: true });
            renderer.setSize(width, height);
            container.appendChild(renderer.domElement);

            // Create pot geometry
            const potGeometry = new THREE.CylinderGeometry(1, 0.8, 1.5, 32);
            const potMaterial = new THREE.MeshPhongMaterial({ 
                color: 0x4ade80, // Green color
                shininess: 30,
                flatShading: true
            });
            const pot = new THREE.Mesh(potGeometry, potMaterial);

            // Create plant geometry
            const plantGeometry = new THREE.ConeGeometry(0.5, 1, 32);
            const plantMaterial = new THREE.MeshPhongMaterial({ 
                color: 0x22c55e, // Darker green
                shininess: 30,
                flatShading: true
            });
            const plant = new THREE.Mesh(plantGeometry, plantMaterial);
            plant.position.y = 1.2;

            // Create group for pot and plant
            const potGroup = new THREE.Group();
            potGroup.add(pot);
            potGroup.add(plant);
            scene.add(potGroup);

            // Add lights
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
            scene.add(ambientLight);

            const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
            directionalLight.position.set(5, 5, 5);
            scene.add(directionalLight);

            // Animation
            function animate() {
                requestAnimationFrame(animate);
                
                // Rotate the pot group
                potGroup.rotation.y += 0.005;
                
                // Gentle floating motion
                potGroup.position.y = Math.sin(Date.now() * 0.001) * 0.1;
                
                renderer.render(scene, camera);
            }

            // Handle window resize
            window.addEventListener('resize', () => {
                const newWidth = container.clientWidth;
                const newHeight = container.clientHeight;
                
                camera.aspect = newWidth / newHeight;
                camera.updateProjectionMatrix();
                
                renderer.setSize(newWidth, newHeight);
            });

            animate();
        }

        // Initialize Three.js when the page loads
        window.addEventListener('load', initThreeJS);

        document.getElementById('notifyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            const button = this.querySelector('button');
            
            // Show loading state
            button.innerHTML = 'Mengirim...';
            button.disabled = true;

            // Simulate API call
            setTimeout(() => {
                // Show success message
                const successMessage = document.createElement('div');
                successMessage.className = 'success-message mt-4 p-3 bg-green-50 text-green-700 rounded-lg text-center text-sm';
                successMessage.textContent = 'Terima kasih! Kami akan mengirimkan notifikasi ke email Anda.';
                
                this.appendChild(successMessage);
                this.reset();
                button.innerHTML = 'Dapatkan Notifikasi';
                button.disabled = false;

                // Remove success message after 3 seconds
                setTimeout(() => successMessage.remove(), 3000);
            }, 1000);
        });
    </script>
</body>
</html>
