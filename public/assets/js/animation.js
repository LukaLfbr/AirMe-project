document.addEventListener("turbo:load", function () {
  let hamMenu = document.querySelector(".ham-menu");
  let offScreenMenu = document.querySelector(".off-menu");

  hamMenu.addEventListener("click", () => {
    hamMenu.classList.toggle("active");
    offScreenMenu.classList.toggle("active");
  });
});
