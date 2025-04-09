$(document).ready(function () {

    // Function to calculate total for a row
    function calculateTotal(row) {
        var quantity = $(row).find('input[name="quantity[]"]').val();
        var price = $(row).find('input[name="price[]"]').val();
        var total = (quantity && price) ? (quantity * price) : 0;
        $(row).find('.total').val(total.toFixed(2));
    }

    // Event Listener for Price and Quantity Change
    $(document).on('input', 'input[name="quantity[]"], input[name="price[]"]', function () {
        var row = $(this).closest('.product-row');
        calculateTotal(row);
    });

    // Add New Row
    $(".addRow").click(function () {
        var newRow = `
        <div class="row product-row align-items-end">
            <div class="p-2 col-md-2">
                <select name="services[]" class="form-control">
                    <option value="Banner">Banner</option>
                    <option value="Sign">Sign</option>
                    <option value="Lettering">Lettering</option>
                    <option value="Vehicles Signs">Vehicles Signs</option>
                    <option value="Decals">Decals</option>
                    <option value="Displays">Displays</option>
                </select>
            </div>
            <div class="p-2 col-md-1">
                <input type="number" name="height[]" class="form-control" min="1" required>
            </div>
            <div class="p-2 col-md-1">
                <input type="number" name="width[]" class="form-control" min="1" required>
            </div>
            <div class="p-2 col-md-1">
                <input type="number" name="quantity[]" class="form-control" min="1" required>
            </div>
            <div class="p-2 col-md-2">
                <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="number" name="price[]" class="form-control price" min="1" required>
                </div>
            </div>
            <div class="p-2 col-md-2">
                <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="number" class="form-control total" disabled>
                </div>
            </div>
            <div class="p-2 col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
            </div>
        </div>
        `;
        $("#productContainer").append(newRow);
    });

    // Remove Row
    $(document).on("click", ".removeRow", function () {
        $(this).closest(".product-row").remove();
    });

    // Add to Table
    $(".addList").click(function () {
        $("#productContainer .product-row").each(function () {
            var service = $(this).find('select[name="services[]"]').val();
            var quantity = $(this).find('input[name="quantity[]"]').val();
            var price = $(this).find('input[name="price[]"]').val();
            var total = $(this).find('.total').val();

            if (service && quantity && price) {
                var row = `
                <tr>
                    <td>${service}</td>
                    <td>${quantity}</td>
                    <td>₱${parseFloat(price).toFixed(2)}</td>
                    <td>₱${parseFloat(total).toFixed(2)}</td>
                    <td><button type="button" class="btn btn-danger btn-sm removeItem">X</button></td>
                </tr>
                `;
                $("#productTable tbody").append(row);
            }
        });

        // Clear Input Fields After Adding
        $("#productContainer").html('');
        $(".addRow").click(); // Add an initial row back
    });

    // Remove Table Row
    $(document).on("click", ".removeItem", function () {
        $(this).closest("tr").remove();
    });

});
