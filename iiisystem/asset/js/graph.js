$(document).ready(function(){
    // Toggle each graph when the card is clicked
    $("#projectsCard").click(function(){
      $("#projectsGraph").slideToggle();
    });
    $("#salesCard").click(function(){
      $("#salesGraph").slideToggle();
    });
    $("#stocksCard").click(function(){
      $("#stocksGraph").slideToggle();
    });
    
    // Sample data for charts
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    
    // Projects Chart
    const projectsCtx = document.getElementById('projectsChart').getContext('2d');
    const projectsChart = new Chart(projectsCtx, {
      type: 'line',
      data: {
        labels: months,
        datasets: [{
          label: 'Projects',
          data: [2, 3, 1, 4, 2, 5, 3, 2, 4, 3, 2, 5],
          borderColor: '#0c95b9',
          backgroundColor: 'rgba(12,149,185,0.2)',
          tension: 0.4,
          fill: true,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { mode: 'index', intersect: false },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0 }
          }
        }
      }
    });
    
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
      type: 'line',
      data: {
        labels: months,
        datasets: [{
          label: 'Sales',
          data: [6, 4, 7, 5, 6, 7, 5, 6, 7, 4, 6, 7],
          borderColor: '#ffbb02',
          backgroundColor: 'rgba(255,187,2,0.2)',
          tension: 0.4,
          fill: true,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { mode: 'index', intersect: false },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0 }
          }
        }
      }
    });
    
    // Stocks Chart
    const stocksCtx = document.getElementById('stocksChart').getContext('2d');
    const stocksChart = new Chart(stocksCtx, {
      type: 'line',
      data: {
        labels: months,
        datasets: [{
          label: 'Stocks',
          data: [4, 5, 3, 4, 6, 4, 5, 3, 4, 6, 5, 4],
          borderColor: '#0077ff',
          backgroundColor: 'rgba(0,119,255,0.2)',
          tension: 0.4,
          fill: true,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { mode: 'index', intersect: false },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0 }
          }
        }
      }
    });
  });