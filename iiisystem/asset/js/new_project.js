function createProductRow() {
    return `
    <div class="row product-row mb-2 border p-2">
        <div class="p-2 col-md-2">
            <label class="form-label">Services</label>
            <select name="services[]" class="form-select" required>
                <option value="Banner">Banner</option>
                <option value="Lettering">Lettering</option>
                <option value="Sign">Sign</option>
                <option value="Display">Display</option>
                <option value="Vehicle Sign">Vehicle Sign</option>
                <option value="Decals">Decals</option>
            </select>
        </div>
        <div class="p-2 col-md-2">
            <label class="form-label">Matterial Type</label>
            <select name="tarp_type[]" class="form-select" required>
                <option value="Matte">Matte</option>
                <option value="Gloss">Gloss</option>
            </select>
        </div>
        <div class="p-2 col-md-3">
            <label class="form-label">Description</label>
            <textarea name="description[]" class="form-control" rows="3" placeholder="Enter description"></textarea>
        </div>
        <div class="p-2 col-md-2">
            <label class="form-label">Quantity</label>
            <input type="text" name="quantity[]" class="form-control" placeholder="Enter quantity" required>
        </div>
        <div class="p-2 col-md-2">
            <label class="form-label">Price Per Piece</label>
            <div class="input-group">
                <span class="input-group-text">₱</span>
                <input type="text" name="price[]" class="form-control" required>
            </div>
        </div>
        <div class="p-2 col-md-2">
            <label class="form-label">Total</label>
            <div class="input-group">
                <span class="input-group-text">₱</span>
                <input type="text" name="total[]" class="form-control" readonly>
            </div>
        </div>
        <div class="col-md-1 d-flex align-items-center">
            <button type="button" class="btn btn-danger btn-sm removeRow mt-4">X</button>
        </div>
    </div>
    `;
}

$(document).ready(function () {

    // Add new row
    $('.addRow').on('click', function () {
        $('#productContainer').append(createProductRow());
    });

    // Remove row
    $('#productContainer').on('click', '.removeRow', function () {
        $(this).closest('.product-row').remove();
    });

    // Change tarp types based on selected service
    $('#productContainer').on('change', 'select[name="services[]"]', function () {
        let row = $(this).closest('.product-row');
        let service = $(this).val();
        let tarpTypeSelect = row.find('select[name="tarp_type[]"]'); 
        
        // Clear existing options
        tarpTypeSelect.empty();

        // Use if-else to determine the available tarp types based on the service
        if (service === 'Banner') {
            // For Banner, Sign, and Vehicle Sign: both Matte and Gloss are available
            tarpTypeSelect.append('<option value="Matte">Matte</option>');
            tarpTypeSelect.append('<option value="Gloss">Gloss</option>');
        } else if (service === 'Lettering') {
            // For Lettering: only Matte is available
            tarpTypeSelect.append('<option value="Matte">Matte</option>');
        } else if (service === 'Display') {
            // For Display: only lGoss is available
            tarpTypeSelect.append('<option value="Gloss">Gloss</option>');
        } else if (service === 'Decals') {
            // For Decals: both Matte and Gloss are available
            tarpTypeSelect.append('<option value="Vinyl">Vinyl</option>');
            tarpTypeSelect.append('<option value="Clear">Clear</option>');
            tarpTypeSelect.append('<option value="Matte">Matte</option>');
            tarpTypeSelect.append('<option value="Reflectorized">Reflectorized</option>');
        } else if( service === 'Sign') {
            // For Sign: both Matte and Gloss are available
            tarpTypeSelect.append('<option value="Gloss">Gloss</option>');
            tarpTypeSelect.append('<option value="Matte">Matte</option>');
            tarpTypeSelect.append('<option value="Reflectorized">Reflectorized</option>');
            tarpTypeSelect.append('<option value="Clear">Clear</option>');
        }else if(service === 'Vehicle Sign') {
            // For Vehicle Sign: both Matte and Gloss are available
            tarpTypeSelect.append('<option value="Matte">Matte</option>');
            tarpTypeSelect.append('<option value="Gloss">Gloss</option>');
        } else {
            // Default case: no options available
            tarpTypeSelect.append('<option value="">No options available</option>');
        }
    });

    // Auto-calculate total
    $('#productContainer').on('input', 'input[name="price[]"], input[name="quantity[]"]', function () {
        let row = $(this).closest('.product-row');
        let price = parseFloat(row.find('input[name="price[]"]').val()) || 0;
        let qty = parseFloat(row.find('input[name="quantity[]"]').val()) || 0;
        let total = price * qty;
        row.find('input[name="total[]"]').val(total.toFixed(2));
    });

    // Add List - move inputs to table and reset product container
    $('.addList').on('click', function () {
        let rows = $('.product-row');

        if (rows.length === 0) {
            alert("No rows to add.");
            return;
        }

        let allRowData = [];

        rows.each(function () {
            let row = $(this);
            allRowData.push({
                service: row.find('select[name="services[]"]').val() || '',
                tarpType: row.find('select[name="tarp_type[]"]').val() || '',
                description: row.find('textarea[name="description[]"]').val() || '',
                height: row.find('input[name="height[]"]').val() || '',
                width: row.find('input[name="width[]"]').val() || '',
                quantity: row.find('input[name="quantity[]"]').val() || '',
                price: parseFloat(row.find('input[name="price[]"]').val()) || 0,
                total: parseFloat(row.find('input[name="total[]"]').val()) || 0
            });
        });

        // ✅ Now it's safe to clear the container
        $('#productContainer').empty();

        // Append rows to the summary table
        allRowData.forEach(function (data) {
            $('#productTable tbody').append(`
                <tr>
                    <td><input type="hidden" name="services[]" value="${data.service}">${data.service}</td>
                    <td><input type="hidden" name="tarp_type[]" value="${data.tarpType}">${data.tarpType}</td>
                    <td><input type="hidden" name="description[]" value="${data.description}">${data.description}</td>
                    <td><input type="hidden" name="quantity[]" value="${data.quantity}">${data.quantity}</td>
                    <td><input type="hidden" name="price[]" value="${data.price.toFixed(2)}">₱${data.price.toFixed(2)}</td>
                    <td><input type="hidden" name="total[]" value="${data.total.toFixed(2)}">₱${data.total.toFixed(2)}</td>
                    <td><button type="button" class="btn btn-danger btn-sm removeTableRow">X</button></td>
                </tr>
            `);
        });

        // Re-add a blank row for further inputs

        // Optional scroll to summary
        $('html, body').animate({
            scrollTop: $('#productTable').offset().top
        }, 500);
    });

    // Remove row from summary table
    $('#productTable').on('click', '.removeTableRow', function () {
        $(this).closest('tr').remove();
    });

});