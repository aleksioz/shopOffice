<?php
/* @var $this InvoiceController */
/* @var $model Invoice */
?>

<!-- Hidden template for invoice line -->
<div id="line-template" style="display: none;">
	<div class="invoice-line-hidden" data-line-index="" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; padding: 10px; border: 1px solid #ddd;">
		<div style="flex: 2;">
			<label>Item:</label>
			<?php echo CHtml::dropDownList('InvoiceLine[item_id][]', '', 
				CHtml::listData($items, 'id', 'name'), 
				array('empty' => 'Select Item...', 'class' => 'item-select')
			); ?>
		</div>
		<div style="flex: 1;">
			<label>Quantity:</label>
			<?php echo CHtml::numberField('InvoiceLine[quantity][]', '', array('size'=>'60px', 'step' => '0.01', 'min' => '0', 'class' => 'quantity-input')); ?>
		</div>
		<div style="flex: 1;">
			<label>Unit Price:</label>
			<input type="text" style="width:60px" readonly class="unit-price-input" />
		</div>
		<div style="flex: 1;">
			<label>VAT %:</label>
			<input type="text" style="width:60px" readonly class="vat-percent-input" />
		</div>
		<div style="flex: 1;">
			<label>PP %:</label>
			<input type="text" style="width:60px" readonly class="pp-percent-input" />
		</div>
		<div style="flex: 1;">
			<label>Line Total:</label>
			<input type="text" style="width:60px" readonly class="line-total-input" />
		</div>
		<div style="flex: 0 0 auto;">
			<button type="button" class="remove-line-btn btn btn-danger">Remove</button>
		</div>
	</div>
</div>
