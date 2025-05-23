<!-- Hero Section -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

<section class="relative md:top-22 mb-10 top-20 overflow-hidden flex h-[550px] mx-auto md:w-[1000px] w-full">
    <div class="w-1/2 flex relative left-10 flex-col">
        <h1 class="md:text-xl text-md text-black/70 ml-1 mt-5">
            Selamat Datang Di
            <span class="text-green-800 font-bold justify">HijauLoka</span>
        </h1>
        <h2 class="mt-5 font-bold md:text-6xl text-5xl w-52">
            Bring <span class="text-green-800">Nature</span> Into Your Home
        </h2>
        <div class="w-72 md:w-96 mt-5 h-0.5 bg-black/60"></div>
        <a href="<?= base_url('popular') ?>"
            class="mt-5 text-center flex items-center justify-center bg-green-700 w-48 rounded-full text-white shadow-lg shadow-black/20 h-14 hover:bg-green-200 hover:text-green-700 font-semibold transition-all duration-300 cursor-pointer">
            Eksplor Sekarang
        </a>
    </div>
    <div id="model-container" class="w-1/2 hidden md:flex h-[500px] items-center justify-center"></div>
</section>

<style>
@keyframes subtle-zoom {
    from { transform: scale(1.05); }
    to { transform: scale(1.15); }
}
@keyframes fade-in {
    0% { opacity: 0; transform: translateY(30px); }
    100% { opacity: 1; transform: translateY(0); }
}
.animate-subtle-zoom {
    animation: subtle-zoom 20s ease-in-out infinite alternate;
}
.animate-fade-in {
    animation: fade-in 1.2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

#model-container {
    position: relative;
}

#model-container canvas {
    width: 100% !important;
    height: 100% !important;
    animation: subtle-zoom 20s ease-in-out infinite alternate;
}
</style>

<script>
// Three.js initialization
function initThreeJS() {
    const container = document.getElementById('model-container');
    const width = container.clientWidth;
    const height = container.clientHeight;

    // Scene setup
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0xffffff);

    // Camera setup
    const camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);
    camera.position.set(0, 1, 5);

    // Renderer setup
    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.shadowMap.enabled = true;
    container.appendChild(renderer.domElement);

    // Add lights
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
    scene.add(ambientLight);

    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
    directionalLight.position.set(5, 5, 5);
    directionalLight.castShadow = true;
    scene.add(directionalLight);

    // Add ground plane for shadows
    const groundGeometry = new THREE.PlaneGeometry(10, 10);
    const groundMaterial = new THREE.MeshStandardMaterial({ 
        color: 0xffffff,
        transparent: true,
        opacity: 0.1
    });
    const ground = new THREE.Mesh(groundGeometry, groundMaterial);
    ground.rotation.x = -Math.PI / 2;
    ground.position.y = -1;
    ground.receiveShadow = true;
    scene.add(ground);

    // Load the model
    const loader = new THREE.GLTFLoader();
    let model;

    loader.load(
        '<?= site_url('assets/models/HijauLoka.glb') ?>',
        function (gltf) {
            model = gltf.scene;
            
            // Enable shadows for all meshes in the model
            model.traverse((node) => {
                if (node.isMesh) {
                    node.castShadow = true;
                    node.receiveShadow = true;
                }
            });

            // Center and scale the model
            const box = new THREE.Box3().setFromObject(model);
            const center = box.getCenter(new THREE.Vector3());
            const size = box.getSize(new THREE.Vector3());
            
            const maxDim = Math.max(size.x, size.y, size.z);
            const scale = 2 / maxDim;
            model.scale.multiplyScalar(scale);
            
            model.position.sub(center.multiplyScalar(scale));
            model.position.y -= 1; // Adjust height

            scene.add(model);
        },
        function (xhr) {
            console.log((xhr.loaded / xhr.total * 100) + '% loaded');
        },
        function (error) {
            console.error('An error happened loading the model:', error);
        }
    );

    // Add orbit controls
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.enableZoom = false;
    controls.autoRotate = true;
    controls.autoRotateSpeed = 1.0;

    // Animation
    function animate() {
        requestAnimationFrame(animate);
        controls.update();
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

// Existing scroll handling code
document.querySelectorAll('a[href="#produk_section"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetSection = document.querySelector('#produk_section');
        const offset = 80;
        const targetPosition = targetSection.getBoundingClientRect().top + window.pageYOffset - offset;
        
        window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
        });
    });
});
</script>