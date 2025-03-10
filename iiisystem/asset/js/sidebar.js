document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.querySelector("#sidebar");
  const hamBurger = document.querySelector(".toggle-btn");

  // Ensure sidebar starts expanded
  if (!sidebar.classList.contains("expand")) {
      sidebar.classList.add("expand");
  }

  // Toggle sidebar on button click
  hamBurger.addEventListener("click", function () {
      sidebar.classList.toggle("expand");
  });
});
