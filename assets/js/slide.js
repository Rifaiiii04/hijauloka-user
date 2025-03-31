const slider = document.querySelector(".slider");
const images = document.querySelectorAll(".slider img");
let index = 0;

function moveSlide(step) {
  index = (index + step + images.length) % images.length;
  slider.style.transform = `translateX(-${index * 100}%)`;
}
document.querySelector(".prev").addEventListener("click", () => moveSlide(-1));
document.querySelector(".next").addEventListener("click", () => moveSlide(1));
