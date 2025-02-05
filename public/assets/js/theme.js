document.addEventListener("turbo:load", () => {
  const themeToggleButtons = document.querySelectorAll(".theme-toggle");
  const logo = document.querySelectorAll(".nav-logo");

  const toggleTheme = () => {
    const currentTheme = document.documentElement.getAttribute("data-theme");
    const newTheme = currentTheme === "dark" ? "light" : "dark";
    document.documentElement.setAttribute("data-theme", newTheme);
    document.cookie = `theme=${newTheme}; path=/; max-age=31536000`;
    logo.forEach((logo) => {
      newTheme === "dark"
        ? (logo.src = "/assets/img/AirMe-logo.png")
        : (logo.src = "/assets/img/AirMe-logo-white.png");
    });
  };

  if (document.documentElement.getAttribute("data-theme") === "dark") {
    logo.forEach((logo) => {
      logo.src = "/assets/img/AirMe-logo.png";
    });
  } else {
    logo.forEach((logo) => {
      logo.src = "/assets/img/AirMe-logo-white.png";
    });
  }

  themeToggleButtons.forEach((button) => {
    button.addEventListener("click", toggleTheme);
  });

  // If there is a cookie, set the theme
  // Then change the data-theme attribute
  const themeFromCookie = document.cookie
    .split("; ")
    .find((row) => row.startsWith("theme="));
  if (themeFromCookie) {
    const theme = themeFromCookie.split("=")[1];
    document.documentElement.setAttribute("data-theme", theme);
  }
});
