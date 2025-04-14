<!-- Hero Section -->
<section class="relative top-20 overflow-hidden" style="height: 85vh;">
  <!-- Background Image with Parallax -->
  <div class="absolute inset-0">
    <img src="<?= base_url('assets/plant/hero-bg.png') ;?>" 
         class="w-full h-full object-cover scale-105 motion-safe:animate-subtle-zoom brightness-[0.85]" 
         alt="Background" />
    <div class="absolute inset-0 bg-black/50"></div>
  </div>

  <!-- Content -->
  <div class="relative h-full flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="text-center text-white animate-fade-in max-w-4xl">
      <span class="inline-block px-4 py-1 rounded-full bg-green-800/40 text-green-300 text-sm uppercase tracking-[0.15em] mb-3 backdrop-blur-sm border border-green-600/20">
        Tanaman Hias Berkualitas
      </span>
      <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold mb-4 tracking-tight drop-shadow-xl">
        Hijaukan Ruangan Anda dengan <span class="bg-gradient-to-r from-green-400 to-emerald-400 text-transparent bg-clip-text">HijauLoka</span>
      </h1>
      <p class="text-base sm:text-lg mb-6 max-w-2xl mx-auto font-light text-gray-200/90 drop-shadow-md">
        Koleksi tanaman hias terbaik untuk mempercantik interior rumah Anda
      </p>
      <a href="#produk_section" 
         class="inline-flex items-center p-3 cursor-pointer bg-green-600 text-white text-lg font-medium rounded-full transition-all duration-300 hover:bg-green-700 hover:shadow-lg hover:-translate-y-0.5">
        Eksplor Sekarang
      </a>
        <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-emerald-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
      </a>
    </div>
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