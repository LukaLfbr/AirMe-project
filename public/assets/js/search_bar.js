document.addEventListener("turbo:load", () => {
  const autocompleteField = document.querySelector(
    "#events_autocomplete_names"
  );

  if (autocompleteField) {
    autocompleteField.addEventListener("change", (event) => {
      const selectedOption = event.target.value;
      if (selectedOption) {
        window.location.href = `event/${selectedOption}`;
      }
    });
  }
});
