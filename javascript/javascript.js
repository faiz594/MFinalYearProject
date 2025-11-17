
// Bootstrap validation + password match + show/hide toggles
(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    // --- Bootstrap built-in validation + password match ---
    const forms = document.querySelectorAll(".needs-validation");

    Array.from(forms).forEach(function (form) {
      form.addEventListener(
        "submit",
        function (event) {
          const pwd = document.getElementById("password");
          const cpwd = document.getElementById("confirmPassword");

          // custom password match check
          if (pwd && cpwd) {
            if (pwd.value !== cpwd.value) {
              cpwd.setCustomValidity("Passwords do not match");
            } else {
              cpwd.setCustomValidity("");
            }
          }

          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add("was-validated");
        },
        false
      );
    });

    // --- Password Toggle with Bootstrap Icons ---
    function setupToggle(toggleId, inputId) {
      const btn = document.getElementById(toggleId);
      const input = document.getElementById(inputId);
      if (!btn || !input) return;

      btn.addEventListener("click", (e) => {
        e.preventDefault(); // prevent accidental form submit
        const show = input.type === "password";
        input.type = show ? "text" : "password";

        // toggle Bootstrap icon
        const icon = btn.querySelector("i");
        if (icon) {
          icon.classList.toggle("bi-eye");
          icon.classList.toggle("bi-eye-slash");
        }

        input.focus(); // keep focus on input (better UX)
      });
    }

    // Attach to your password fields
    setupToggle("togglePassword", "password");
    setupToggle("toggleConfirmPassword", "confirmPassword");
  });
})();



// Populate Batch dropdown
document.addEventListener("DOMContentLoaded", function () {
  const batchSelect = document.getElementById("batch");
  if (batchSelect) {
    const startYear = 2021;
    const endYear = 2027; // You can extend this later

    for (let year = startYear; year <= endYear; year++) {
      const option = document.createElement("option");
      option.value = `Batch ${year}-${year + 4}`;
      option.textContent = `Batch ${year}-${year + 4}`;
      batchSelect.appendChild(option);
    }
  }
});

// alumni card hover effect
document.querySelectorAll(".alumni.card").forEach((card) => {
  card.addEventListener("mouseenter", () => {
    card.style.transform = "scale(1.05)";
    card.style.transition = "0.3s ease";
  });
  card.addEventListener("mouseleave", () => {
    card.style.transform = "scale(1)";
  });
});

const sections = document.querySelectorAll("section");
const navLinks = document.querySelectorAll(".navbar-nav .nav-link");

window.addEventListener("scroll", () => {
  let current = "";
  sections.forEach((section) => {
    const sectionTop = section.offsetTop - 80; // offset for navbar
    if (scrollY >= sectionTop) {
      current = section.getAttribute("id");
    }
  });

  navLinks.forEach((link) => {
    link.classList.remove("active");
    if (link.getAttribute("href") === "#" + current) {
      link.classList.add("active");
    }
  });
});

// Search Option of Alumni among many in alumni.php
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("alumniSearch");
  const alumniCards = document.querySelectorAll(".alumni");

  searchInput.addEventListener("keyup", function () {
    let filter = searchInput.value.toLowerCase().trim();

    alumniCards.forEach((card) => {
      let name = card.querySelector("h5").textContent.toLowerCase();
      if (name.includes(filter)) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  });
});

// popup for index registration successful
document.addEventListener("DOMContentLoaded", function () {
  const successPopup = document.getElementById("successPopup");
  if (successPopup) {
    successPopup.classList.add("show");
    setTimeout(() => {
      successPopup.classList.remove("show");
    }, 3000);
  }
});
