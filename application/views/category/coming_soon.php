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

    <!-- 3D Background Container -->
    <div id="3d-background" class="fixed inset-0 -z-10"></div>

    <div class="container mx-auto px-4 py-12 md:py-16">
        <div class="max-w-xl mx-auto">
            <!-- Simple Hero Section -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-800 mb-4 mt-16">Coming Soon</h1>
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

        #3d-background {
            opacity: 0.15;
            pointer-events: none;
        }

        #3d-background canvas {
            width: 100% !important;
            height: 100% !important;
        }
    </style>

    <script>
        // Three.js initialization
        function initThreeJS() {
            const container = document.getElementById('3d-background');
            const width = window.innerWidth;
            const height = window.innerHeight;

            // Scene setup
            const scene = new THREE.Scene();
            scene.background = new THREE.Color(0xf9fafb);

            // Camera setup
            const camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);
            camera.position.z = 15;

            // Renderer setup
            const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            renderer.setSize(width, height);
            container.appendChild(renderer.domElement);

            // Create multiple random objects
            const objects = [];
            const numObjects = Math.floor(Math.random() * 10) + 5; // Random number between 5-15 objects

            for (let i = 0; i < numObjects; i++) {
                // Random position
                const x = (Math.random() - 0.5) * 30;
                const y = (Math.random() - 0.5) * 30;
                const z = (Math.random() - 0.5) * 10;

                // Random size
                const scale = Math.random() * 0.5 + 0.5;

                // Create pot geometry
                const potGeometry = new THREE.CylinderGeometry(1, 0.8, 1.5, 32);
                const potMaterial = new THREE.MeshPhongMaterial({ 
                    color: new THREE.Color(
                        Math.random() * 0.2 + 0.4, // R: 0.4-0.6
                        Math.random() * 0.2 + 0.6, // G: 0.6-0.8
                        Math.random() * 0.2 + 0.4  // B: 0.4-0.6
                    ),
                    shininess: 30,
                    flatShading: true
                });
                const pot = new THREE.Mesh(potGeometry, potMaterial);

                // Create plant geometry
                const plantGeometry = new THREE.ConeGeometry(0.5, 1, 32);
                const plantMaterial = new THREE.MeshPhongMaterial({ 
                    color: new THREE.Color(
                        Math.random() * 0.2 + 0.2, // R: 0.2-0.4
                        Math.random() * 0.2 + 0.6, // G: 0.6-0.8
                        Math.random() * 0.2 + 0.2  // B: 0.2-0.4
                    ),
                    shininess: 30,
                    flatShading: true
                });
                const plant = new THREE.Mesh(plantGeometry, plantMaterial);
                plant.position.y = 1.2;

                // Create group
                const potGroup = new THREE.Group();
                potGroup.add(pot);
                potGroup.add(plant);
                
                // Set random position and scale
                potGroup.position.set(x, y, z);
                potGroup.scale.set(scale, scale, scale);
                
                // Set random initial rotation
                potGroup.rotation.set(
                    Math.random() * Math.PI,
                    Math.random() * Math.PI,
                    Math.random() * Math.PI
                );

                // Add random rotation speed
                potGroup.userData = {
                    rotationSpeed: {
                        x: (Math.random() - 0.5) * 0.01,
                        y: (Math.random() - 0.5) * 0.01,
                        z: (Math.random() - 0.5) * 0.01
                    },
                    floatSpeed: Math.random() * 0.001 + 0.0005,
                    floatOffset: Math.random() * Math.PI * 2
                };

                scene.add(potGroup);
                objects.push(potGroup);
            }

            // Add lights
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
            scene.add(ambientLight);

            const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
            directionalLight.position.set(5, 5, 5);
            scene.add(directionalLight);

            // Animation
            function animate() {
                requestAnimationFrame(animate);
                
                // Update each object
                objects.forEach(obj => {
                    // Random rotation
                    obj.rotation.x += obj.userData.rotationSpeed.x;
                    obj.rotation.y += obj.userData.rotationSpeed.y;
                    obj.rotation.z += obj.userData.rotationSpeed.z;
                    
                    // Floating motion
                    obj.position.y += Math.sin(Date.now() * obj.userData.floatSpeed + obj.userData.floatOffset) * 0.01;
                });
                
                renderer.render(scene, camera);
            }

            // Handle window resize
            window.addEventListener('resize', () => {
                const newWidth = window.innerWidth;
                const newHeight = window.innerHeight;
                
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
