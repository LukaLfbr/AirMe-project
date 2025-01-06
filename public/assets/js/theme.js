document.addEventListener("turbo:load", () => {
  const themeToggleButtons = document.querySelectorAll(".theme-toggle");

  const toggleTheme = () => {
    const currentTheme = document.documentElement.getAttribute("data-theme");
    const newTheme = currentTheme === "dark" ? "light" : "dark";
    document.documentElement.setAttribute("data-theme", newTheme);
    document.cookie = `theme=${newTheme}; path=/; max-age=31536000`; // expire dans un an
  };

  themeToggleButtons.forEach((button) => {
    button.addEventListener("click", toggleTheme);
  });

  const themeFromCookie = document.cookie
    .split("; ")
    .find((row) => row.startsWith("theme="));
  if (themeFromCookie) {
    const theme = themeFromCookie.split("=")[1];
    document.documentElement.setAttribute("data-theme", theme);
  }
});
