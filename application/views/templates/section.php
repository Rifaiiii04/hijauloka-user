<!-- Hero Section -->
<!-- Hero Section -->
<script
			type="module"
			src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"
		></script>
<section
			class="relative md:top-22 mb-10 top-20 overflow-hidden flex h-[550px] mx-auto w-[1200px]"
		>
			<div class="w-1/2 flex relative left-10 flex-col">
				<h1 class="md:text-xl text-md text-black/70 ml-1 mt-5">
					Selamat Datang Di
					<span class="text-green-800 font-bold justify">HijauLoka</span>
				</h1>
				<h2 class="mt-5 font-bold md:text-6xl text-5xl w-52">
					Bring <span class="text-green-800">Nature</span> Into Your Home
				</h2>
				<div class="w-72 md:w-96 mt-5 h-0.5 bg-black/60"></div>
				<button
					class="mt-5 bg-green-700 w-48 rounded-full text-white shadow-lg shadow-black/20 h-14 hover:bg-green-200 hover:text-green-700 font-semibold transition-all duration-300 cursor-pointer"
				>
					Eksplor Sekarang
				</button>
			</div>
			<div class="w-1/2">
				<model-viewer
					alt="Neil Armstrong's Spacesuit from the Smithsonian Digitization Programs Office and National Air and Space Museum"
					src="<?= site_url('assets/models/plant2.glb')?> " 
					ar
					shadow-intensity="1"
					camera-controls
					auto-rotate
					touch-action="pan-y"
					class="w-full h-[500px] hidden sm:flex items-center justify-center"
				></model-viewer>
			</div>
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
</style>

<script>
document.querySelectorAll('a[href="#produk_section"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetSection = document.querySelector('#produk_section');
        const offset = 80; // Adjust this value based on your header height
        const targetPosition = targetSection.getBoundingClientRect().top + window.pageYOffset - offset;
        
        window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
        });
    });
});
</script>