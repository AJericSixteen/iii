$(document).ready(function () {
    // Function to calculate total price for a row
    function calculateTotal(row) {
        let quantity = parseFloat($(row).find('.quantity').val()) || 0;
        let price = parseFloat($(row).find('.price').val()) || 0;
        let total = quantity * price;
        $(row).find('.total').val(total.toFixed(2));
    }

    // Update total when quantity or price changes
    $(document).on('input', '.quantity, .price', function () {
        let row = $(this).closest('.product-row');
        calculateTotal(row);
    });

    // Add new product row
    $('.addRow').click(function () {
        let newRow = `
        <div class="row product-row">
            <div class="p-2 col-md-3">
                <label class="form-label">Product and Services:</label> <span class="red"> * </span>
                <select name="services[]" class="form-control" required>
                    <option value="Banner">Banner</option>
                    <option value="Sign">Sign</option>
                    <option value="Lettering">Lettering</option>
                    <option value="Vehicles Signs">Vehicles Signs</option>
                    <option value="Decals">Decals</option>
                    <option value="Displays">Displays</option>
                    <option value="Event Management">Event Management</option>
                    <option value="Marketing Assessment">Marketing Assessment</option>
                </select>
            </div>
            <div class="p-2 col-md-2">
                <label class="form-label">Quantity</label> <span class="red"> * </span>
                <input type="number" name="quantity[]" class="form-control quantity" min="1" required>
            </div>
            <div class="p-2 col-md-2">
                <label class="form-label">Price Per Piece</label> <span class="red"> * </span>
                <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="number" name="price[]" class="form-control price" min="1" required>
                </div>
            </div>
            <div class="p-2 col-md-2">
                <label class="form-label">Total</label> 
                <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="text" class="form-control total" readonly>
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
            </div>
        </div>`;
        $('#productContainer').append(newRow);
    });

    // Remove a row
    $(document).on('click', '.removeRow', function () {
        $(this).closest('.product-row').remove();
        updateTotalCost(); // Update total when row is removed
    });

    // Add selected product to the table
    $('.addList').click(function () {
        let tableBody = $("#productTable tbody");
        tableBody.empty(); // Clear table before adding new list
        let totalCost = 0;

        $('.product-row').each(function () {
            let product = $(this).find('select[name="services[]"]').val();
            let quantity = parseFloat($(this).find('.quantity').val()) || 0;
            let price = parseFloat($(this).find('.price').val()) || 0;
            let total = quantity * price;

            if (quantity > 0 && price > 0) {
                totalCost += total;

                let tableRow = `
                <tr class="product-entry">
                    <td>${product}</td>
                    <td>${quantity}</td>
                    <td>₱${price.toFixed(2)}</td>
                    <td>₱${total.toFixed(2)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm removeItem">X</button>
                    </td>
                </tr>`;
                tableBody.append(tableRow);
            }
        });

        updateTotalCost();
    });

    // Remove item from table and update total
    $(document).on('click', '.removeItem', function () {
        $(this).closest('tr').remove();
        updateTotalCost();
    });

    // Function to update total project cost
    function updateTotalCost() {
        let totalCost = 0;
        $("#productTable tbody tr.product-entry").each(function () {
            let total = parseFloat($(this).find("td:eq(3)").text().replace("₱", "")) || 0;
            totalCost += total;
        });

        $("#totalRow").remove(); // Remove existing total row
        if (totalCost > 0) {
        let totalRow = `
            <tr id="totalRow">
                <td colspan="3" class="text-end"><strong>Total Project Cost:</strong></td>
                <td><strong>₱${totalCost.toFixed(2)}</strong></td>
                <td></td>
            </tr>
        `;
        $("#productTable tbody").append(totalRow);
    }
    }

    // Prevent submission if no items are added
    $('form').on('submit', function (e) {
        if ($('#productTable tbody tr').length === 0) {
            alert("You need to add at least one item to the list before submitting.");
            e.preventDefault(); // Prevent form submission
        }
    });
});
