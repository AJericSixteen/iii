$(document).ready(function () {
    var table = $('#frequentItemsTable').DataTable({
      "pageLength": 10,
      "order": [[0, "desc"]]
    });

    $('#statusFilter').on('change', function () {
      const selectedStatus = $(this).val().toLowerCase();

      if (selectedStatus === "all") {
        table.search("").draw(); // Show all
      } else {
        table.column(3).search("^" + selectedStatus + "$", true, false).draw(); // Exact match on status column
      }
    });
  });