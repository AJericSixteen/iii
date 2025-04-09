// Handle the change event on the status filter dropdown
document.getElementById('statusFilter').addEventListener('change', function () {
    let selectedStatus = this.value.toLowerCase(); // Ensure lowercase comparison
    let rows = document.querySelectorAll('.project-row');

    rows.forEach(function (row) {
      let rowStatus = row.getAttribute('data-status').toLowerCase(); // Convert both to lowercase for comparison
      if (selectedStatus === 'all' || rowStatus === selectedStatus) {
        row.style.display = ''; // Show row
      } else {
        row.style.display = 'none'; // Hide row
      }
    });
  });

  // Ensure the 'all' option is selected by default when page loads
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('statusFilter').value = 'all';
    let rows = document.querySelectorAll('.project-row');
    rows.forEach(function (row) {
      row.style.display = ''; // Show all rows by default
    });
  });