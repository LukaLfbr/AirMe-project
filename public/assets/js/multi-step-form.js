if (!localStorage.getItem("refreshed")) {
  localStorage.setItem("refreshed", "true");
  location.reload();
} else {
  localStorage.removeItem("refreshed");
}

document.addEventListener("turbo:load", function () {
  const formContainer = document.querySelector(".form-container");
  let currentStep = 0;

  document.querySelectorAll(".next").forEach((btn) => {
    btn.addEventListener("click", () => {
      if (currentStep < 2) {
        currentStep++;
        formContainer.style.transform = `translateX(-${currentStep * 100}%)`;
      }
    });
  });

  document.querySelectorAll(".previous").forEach((btn) => {
    btn.addEventListener("click", () => {
      if (currentStep > 0) {
        currentStep--;
        formContainer.style.transform = `translateX(-${currentStep * 100}%)`;
      }
    });
  });
});
