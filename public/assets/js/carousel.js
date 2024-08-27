document.addEventListener("DOMContentLoaded", () => {
  const carousel = document.querySelector(".carousel-list");
  const prevButton = document.querySelector(".carousel-control.prev");
  const nextButton = document.querySelector(".carousel-control.next");
  let offset = 0;

  prevButton.addEventListener("click", () => {
    if (offset > 0) {
      offset--;
    } else {
      offset = carousel.children.length - 1;
    }
    updateCarousel();
  });

  nextButton.addEventListener("click", () => {
    if (offset < carousel.children.length - 1) {
      offset++;
    } else {
      offset = 0;
    }
    updateCarousel();
  });

  function updateCarousel() {
    carousel.style.transform = `translateX(-${offset * 100}%)`;
  }
});
