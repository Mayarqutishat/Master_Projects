// Using Bootstrap modal events
document.addEventListener("DOMContentLoaded", function() {
  const countrySelect = document.getElementById("country");
  
  document.querySelectorAll(".modal").forEach(modal => {
      modal.addEventListener("show.bs.modal", function() {
          fetchCountries(); // Fetch countries when the modal is about to be shown
      });
  });

  function fetchCountries() {
      fetch("https://restcountries.com/v3.1/all")
          .then((response) => {
              if (!response.ok) throw new Error("Network response was not ok");
              return response.json();
          })
          .then((data) => {
              countrySelect.innerHTML = "<option value='' disabled selected>Select your country</option>"; // Reset the select
              data.forEach((country) => {
                  const option = document.createElement("option");
                  option.value = country.name.common;
                  option.textContent = country.name.common;
                  countrySelect.appendChild(option);
              });
          })
          .catch((error) => console.error("Error fetching countries:", error));
  }
});
