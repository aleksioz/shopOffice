<?php
/* @var $this InvoiceController */
/* @var $model Invoice */
?>

<?php if (!empty($invoiceLines)): ?>
    <?php foreach ($invoiceLines as $index => $line): ?>
        <div class="invoice-line" data-line-index="" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; padding: 10px; border: 1px solid #ddd;">
            <div style="flex: 2;">
                <label>Item:</label>
                <select name="InvoiceLine[item_id][]" class="item-id-input">
                    <option value="<?= $line->item_id ?>" selected><?= $line->line_name ?></option>
                </select>
            </div>
            <div style="flex: 1;">
                <label>Quantity:</label>
                <input type="number" style="width:60px" name="InvoiceLine[quantity][]" class="quantity-input" value="<?= $line->quantity ?>" />
            </div>
            <div style="flex: 1;">
                <label>Unit Price:</label>
                <input type="text" readonly style="width:60px" class="unit-price-input" value="<?= number_format($line->unit_price, 4); ?>" />
            </div>
            <div style="flex: 1;">
                <label>VAT %:</label>
                <input type="text" readonly style="width:60px" class="vat-percent-input" value="<?= number_format($line->vat_percent, 4); ?>" />
            </div>
            <div style="flex: 1;">
                <label>PP %:</label>
                <input type="text" readonly style="width:60px" class="pp-percent-input" value="<?= number_format($line->pp_percent, 4); ?>" />
            </div>
            <div style="flex: 1;">
                <label>Line Total:</label>
                <input type="text" readonly style="width:60px" class="line-total-input" value="<?= number_format($line->line_gross, 4); ?>" readonly />
            </div>
            <div style="flex: 0 0 auto;">
                <button type="button" class="remove-line-btn btn btn-danger">Remove</button>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

