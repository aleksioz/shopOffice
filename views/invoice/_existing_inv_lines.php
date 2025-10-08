<?php
/* @var $this InvoiceController */
/* @var $model Invoice */
?>


<?php if (!empty($invoiceLines)): ?>
    <?php foreach ($invoiceLines as $index => $line): ?>
        <div class="invoice-line" data-line-index="<?php echo $index; ?>" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; padding: 10px; border: 1px solid #ddd;">
            <div style="flex: 2;">
                <label>Item:</label>
                <?= $line->line_name ?>
            </div>
            <div style="flex: 1;">
                <label>Quantity:</label>
                <span class="quantity-input" value="<?= $line->quantity ?>"><?= $line->quantity ?></span>
            </div>
            <div style="flex: 1;">
                <label>Unit Price:</label>
                <span class="unit-price-display" value="<?= number_format($line->unit_price, 4); ?>"><?= number_format($line->unit_price, 4); ?></span>
            </div>
            <div style="flex: 1;">
                <label>VAT %:</label>
                <span class="vat-percent-display" value="<?= number_format($line->vat_percent, 4); ?>"><?= number_format($line->vat_percent, 4); ?></span>
            </div>
            <div style="flex: 1;">
                <label>PP %:</label>
                <span class="pp-percent-display" value="<?= number_format($line->pp_percent, 4); ?>"><?= number_format($line->pp_percent, 4); ?></span>
            </div>
            <div style="flex: 1;">
                <label>Line Total:</label>
                <span class="line-total-display" value="<?= number_format($line->line_gross, 4); ?>"><?= number_format($line->line_gross, 4); ?></span>
            </div>
            <div style="flex: 0 0 auto;">
                <button type="button" class="remove-line-btn btn btn-danger">Remove</button>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

