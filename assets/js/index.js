document.addEventListener("DOMContentLoaded", () => {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");
  const toggleBtn = document.getElementById("toggleSidebar");
  const line1 = document.getElementById("line1");
  const line2 = document.getElementById("line2");
  const line3 = document.getElementById("line3");

  function toggleSidebar() {
    sidebar.classList.toggle("-translate-x-full");
    overlay.classList.toggle("hidden");

    line1.classList.toggle("rotate-45");
    line1.classList.toggle("translate-y-2.5");

    line3.classList.toggle("-rotate-45");
    line3.classList.toggle("-translate-y-3");

    line2.classList.toggle("opacity-0");
  }

  toggleBtn.addEventListener("click", toggleSidebar);

  overlay.addEventListener("click", toggleSidebar);
});

document.addEventListener("DOMContentLoaded", function () {
  var swiper = new Swiper(".mySwiper", {
    loop: true, // Looping terus-menerus
    autoplay: {
      delay: 5000, // Ganti slide setiap 5 detik
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    effect: "fade", // Efek transisi fade
    fadeEffect: {
      crossFade: true,
    },
  });
});

const searchIcon = document.getElementById("search-icon");
const searchInput = document.getElementById("search-input");

searchIcon.addEventListener("click", (e) => {
  e.preventDefault();
  searchInput.classList.toggle("w-0");
  searchInput.classList.toggle("opacity-0");
  searchInput.classList.toggle("w-48");
  searchInput.classList.toggle("opacity-100");
  if (!searchInput.classList.contains("w-0")) {
    searchInput.focus();
  }
});

document.addEventListener("click", (e) => {
  if (!searchIcon.contains(e.target) && !searchInput.contains(e.target)) {
    searchInput.classList.add("w-0", "opacity-0");
    searchInput.classList.remove("w-48", "opacity-100");
  }
});

// Kuantitas
const decrease = document.getElementById("decrease");
const increase = document.getElementById("increase");
const quantity = document.getElementById("quantity");

decrease.addEventListener("click", () => {
  let value = parseInt(quantity.value);
  if (value > 1) {
    quantity.value = value - 1;
  }
});

increase.addEventListener("click", () => {
  let value = parseInt(quantity.value);
  quantity.value = value + 1;
});

const wishlistIcon = document.querySelector(".wishlist-icon");

wishlistIcon.addEventListener("click", function () {
  this.classList.toggle("wishlist-active");
});
