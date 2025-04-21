$(document).ready(function () {
  // Ensure graphs are always shown
  $(".graph-container").show();

  // Disable click events on cards (to prevent hiding the graphs)
  $(".card-toggle").off("click");

  // Chart.js options to fully disable interactions and show tooltips on hover
  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        enabled: true, // Enable the tooltip
        mode: 'nearest', // Tooltip will appear when hovering the nearest point
        intersect: false, // Make sure tooltip shows for all points in a line
        callbacks: {
          // Customize the tooltip to display the value
          label: function(tooltipItem) {
            return `Value: ${tooltipItem.raw}`; // Show the raw value of the data point (numeric value)
          }
        }
      }
    },
    hover: { mode: 'nearest' }, // Change hover mode to nearest to show tooltip on the closest point
    onClick: null,
    interaction: { mode: 'nearest' }, // Adjust the interaction mode to show the closest point's tooltip
    events: []
  };

  // Generate last 12 months ending at the current month
  function getLast12Months() {
    const allMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                       "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    const months = [];
    const current = new Date();

    for (let i = 11; i >= 0; i--) {
      const d = new Date(current.getFullYear(), current.getMonth() - i, 1);
      months.push(allMonths[d.getMonth()]);
    }

    return months;
  }

  const months = getLast12Months();

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

  // Fetch both project count and sales data
  $.ajax({
    url: "../../md_dashboard/dashboard/get_projects_data.php",
    method: "GET",
    dataType: "json",
    success: function (data) {
      console.log("Dashboard data loaded:", data);

      let projectData = Array.isArray(data.projects) ? data.projects : Array(12).fill(0);
      let salesData = Array.isArray(data.sales) ? data.sales : Array(12).fill(0);

      createChart("projectsChart", "Projects", projectData, "#0c95b9", "rgba(12,149,185,0.2)");
      createChart("salesChart", "Sales", salesData, "#ffbb02", "rgba(255,187,2,0.2)");
    },
    error: function (xhr, status, error) {
      console.error("Failed to load dashboard data", error);

      const fallback = Array(12).fill(0);
      createChart("projectsChart", "Projects", fallback, "#0c95b9", "rgba(12,149,185,0.2)");
      createChart("salesChart", "Sales", fallback, "#ffbb02", "rgba(255,187,2,0.2)");
    }
  });

  // Static fallback for stocks (or make this dynamic later)
  createChart("stocksChart", "Stocks", [2, 3, 2, 4, 3, 2, 1, 2, 3, 2, 1, 2], "#0077ff", "rgba(0,119,255,0.2)");

  // Prevent click events on the canvas elements
  $("canvas").css("pointer-events", "none");
});
  