function changeQRToAdd(stockId) {
    let qrCodeCell = document.getElementById("qr_code_" + stockId);
    qrCodeCell.innerHTML = `
<div>
    <img src="qr_code.php?code=add_${stockId}" alt="Add Stock QR" width="100px">
    <span class="badge bg-success ms-2">Add Stock</span>
</div>`;
}

function changeQRToDeduct(stockId) {
    let qrCodeCell = document.getElementById("qr_code_" + stockId);
    qrCodeCell.innerHTML = `
<div>
    <img src="qr_code.php?code=deduct_${stockId}" alt="Deduct Stock QR" width="100px">
    <span class="badge bg-danger ms-2">Deduct Stock</span>
</div>`;
}