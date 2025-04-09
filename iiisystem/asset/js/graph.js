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

  // Sample months array
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

  // Fetch projects data from the server and create the projects chart
  $.ajax({
    url: "../../md_dashboard/dashboard/get_projects_data.php",  // Path to the PHP script
    method: "GET",
    dataType: "json", // Expect JSON response
    success: function (data) {
      console.log("Project data loaded:", data); // Log the data to see if March is included
  
      // Initialize the data array with fallback data (in case of an error)
      let projectData = [];
  
      // Check if the data is in the expected array format
      if (Array.isArray(data) && data.every(item => typeof item === 'number')) {
        // If data is an array of numbers, use it directly
        projectData = data;
      } else {
        // If the data format is unexpected, use fallback data and log the issue
        console.warn("Data format is incorrect or not as expected. Using fallback data.");
        projectData = [3, 5, 4, 6, 7, 8, 6, 5, 4, 6, 7, 5]; // Example fallback data
      }
  
      // Now, create the chart using the projectData (either fetched or fallback)
      createChart("projectsChart", "Projects", projectData, "#0c95b9", "rgba(12,149,185,0.2)");
    },
    error: function (xhr, status, error) {
      console.error("Failed to load projects data", error);
  
      // Use fallback data in case of an error in the AJAX request
      let fallbackData = [3, 5, 4, 6, 7, 8, 6, 5, 4, 6, 7, 5]; // Example fallback data
      createChart("projectsChart", "Projects", fallbackData, "#0c95b9", "rgba(12,149,185,0.2)");
    }
  });
  
  
  // Other charts (Sales and Stocks) with hardcoded data
  createChart("salesChart", "Sales", [6, 4, 7, 5, 6, 7, 5, 6, 7, 4, 6, 7], "#ffbb02", "rgba(255,187,2,0.2)");
  createChart("stocksChart", "Stocks", [4, 5, 3, 4, 6, 4, 5, 3, 4, 6, 5, 4], "#0077ff", "rgba(0,119,255,0.2)");

  // Prevent click events on the canvas elements
  $("canvas").css("pointer-events", "none");
});
