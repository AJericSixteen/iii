$(document).ready(function () {
  // Ensure graphs are always shown
  $(".graph-container").show();

  // Disable click events on cards (to prevent hiding the graphs)
  $(".card-toggle").off("click");

  // Chart.js options to fully disable interactions
  const chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
          legend: { display: false },
          tooltip: { enabled: false } // Disable tooltips
      },
      hover: { mode: null }, // Disable hover effects
      onClick: null, // Ensure clicks are ignored
      interaction: { mode: null }, // Disables any interaction mode
      events: [] // This disables all mouse and touch events on the chart
  };

  // Sample data for charts
  const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

  // Function to create a chart
  function createChart(ctxId, label, data, borderColor, bgColor) {
      const ctx = document.getElementById(ctxId).getContext("2d");
      return new Chart(ctx, {
          type: "line",
          data: {
              labels: months,
              datasets: [{
                  label: label,
                  data: data,
                  borderColor: borderColor,
                  backgroundColor: bgColor,
                  tension: 0.4,
                  fill: true
              }]
          },
          options: chartOptions
      });
  }

  // Initialize charts with non-clickable settings
  createChart("projectsChart", "Projects", [2, 3, 1, 4, 2, 5, 3, 2, 4, 3, 2, 5], "#0c95b9", "rgba(12,149,185,0.2)");
  createChart("salesChart", "Sales", [6, 4, 7, 5, 6, 7, 5, 6, 7, 4, 6, 7], "#ffbb02", "rgba(255,187,2,0.2)");
  createChart("stocksChart", "Stocks", [4, 5, 3, 4, 6, 4, 5, 3, 4, 6, 5, 4], "#0077ff", "rgba(0,119,255,0.2)");

  // Prevent click events on the canvas elements
  $("canvas").css("pointer-events", "none");
});
