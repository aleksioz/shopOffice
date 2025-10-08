<?php
/* @var $this InvoiceController */
/* @var $model Invoice */
/* @var $form CActiveForm */
/* @var $items Item[] */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'invoice-create-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number'); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'internal_number'); ?>
		<?php echo $form->textField($model,'internal_number'); ?>
		<?php echo $form->error($model,'internal_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date', array('value' => $model->date ? $model->date : date('Y-m-d'))); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status', array('draft'=>'Draft', 'closed'=>'Closed'), array('value' => 'draft')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<!-- Invoice Lines Section -->
	<div class="invoice-lines-section">
		<h3>Invoice Items</h3>
		<div id="invoice-lines">
			<!-- Existing invoice lines (if editing) -->
			<?php if(!empty($model->invoiceLines)): ?>
				<?php foreach($model->invoiceLines as $index => $line): ?>
					<div class="invoice-line" data-line-index="<?php echo $index; ?>">
						<div class="row">
							<label>Item:</label>
							<?php echo CHtml::dropDownList('InvoiceLine[' . $index . '][item_id]', $line->item_id, 
								CHtml::listData($items, 'id', 'name'), 
								array('empty' => 'Select Item...', 'class' => 'item-select')
							); ?>
						</div>
						<div class="row">
							<label>Quantity:</label>
							<?php echo CHtml::numberField('InvoiceLine[' . $index . '][quantity]', $line->quantity, array('step' => '0.01', 'min' => '0', 'class' => 'quantity-input')); ?>
						</div>
						<div class="row">
							<label>Unit Price:</label>
							<input type="text" name="unit_price_display" readonly class="unit-price-display" value="<?php echo $line->item ? number_format($line->item->price, 2) : ''; ?>" />
						</div>
						<div class="row">
							<label>VAT %:</label>
							<input type="text" name="vat_percent_display" readonly class="vat-percent-display" value="<?php echo $line->item ? number_format($line->item->vat_percent, 2) : ''; ?>" />
						</div>
						<div class="row">
							<label>PP %:</label>
							<input type="text" name="pp_percent_display" readonly class="pp-percent-display" value="<?php echo $line->item ? number_format($line->item->pp_percent, 2) : ''; ?>" />
						</div>
						<div class="row">
							<label>Line Total:</label>
							<input type="text" name="line_total_display" readonly class="line-total-display" value="<?php echo number_format($line->total_gross, 2); ?>" />
						</div>
						<div class="row">
							<button type="button" class="remove-line-btn btn btn-danger">Remove</button>
						</div>
						<hr />
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<button type="button" id="add-line-btn" class="btn btn-secondary">Add Item</button>
	</div>

	<!-- Totals Section -->
	<div class="totals-section">
		<div class="row">
			<?php echo $form->labelEx($model,'total_net'); ?>
			<?php echo $form->textField($model,'total_net', array('readonly' => true)); ?>
			<?php echo $form->error($model,'total_net'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'total_vat'); ?>
			<?php echo $form->textField($model,'total_vat', array('readonly' => true)); ?>
			<?php echo $form->error($model,'total_vat'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'total_pp'); ?>
			<?php echo $form->textField($model,'total_pp', array('readonly' => true)); ?>
			<?php echo $form->error($model,'total_pp'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'total_gross'); ?>
			<?php echo $form->textField($model,'total_gross', array('readonly' => true)); ?>
			<?php echo $form->error($model,'total_gross'); ?>
		</div>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at', array('readonly' => true, 'value' => date('Y-m-d H:i:s'))); ?>
		<?php echo $form->error($model,'created_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated_at'); ?>
		<?php echo $form->textField($model,'updated_at', array('readonly' => true, 'value' => date('Y-m-d H:i:s'))); ?>
		<?php echo $form->error($model,'updated_at'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Create Invoice'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<!-- Hidden template for invoice line -->
<div id="line-template" style="display: none;">
	<div class="invoice-line" data-line-index="" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; padding: 10px; border: 1px solid #ddd;">
		<div style="flex: 2;">
			<label>Item:</label>
			<?php echo CHtml::dropDownList('InvoiceLine[][item_id]', '', 
				CHtml::listData($items, 'id', 'name'), 
				array('empty' => 'Select Item...', 'class' => 'item-select')
			); ?>
		</div>
		<div style="flex: 1;">
			<label>Quantity:</label>
			<?php echo CHtml::numberField('InvoiceLine[][quantity]', '', array('step' => '0.01', 'min' => '0', 'class' => 'quantity-input')); ?>
		</div>
		<div style="flex: 1;">
			<label>Unit Price:</label>
			<input type="text" name="unit_price_display" readonly class="unit-price-display" />
		</div>
		<div style="flex: 1;">
			<label>VAT %:</label>
			<input type="text" name="vat_percent_display" readonly class="vat-percent-display" />
		</div>
		<div style="flex: 1;">
			<label>PP %:</label>
			<input type="text" name="pp_percent_display" readonly class="pp-percent-display" />
		</div>
		<div style="flex: 1;">
			<label>Line Total:</label>
			<input type="text" name="line_total_display" readonly class="line-total-display" />
		</div>
		<div style="flex: 0 0 auto;">
			<button type="button" class="remove-line-btn btn btn-danger">Remove</button>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    var lineIndex = 0;
    var itemsData = {
        <?php foreach($items as $item): ?>
        <?php echo $item->id; ?>: {
            name: '<?php echo addslashes($item->name); ?>',
            price: <?php echo $item->price; ?>,
            vat_percent: <?php echo $item->vat_percent; ?>,
            pp_percent: <?php echo $item->pp_percent; ?>
        },
        <?php endforeach; ?>
    };
    
    // Add new line
    $('#add-line-btn').click(function() {
        let template = $('#line-template').html();
        template = template.replace(/data-line-index=""/g, 'data-line-index="' + lineIndex + '"');
        template = template.replace(/InvoiceLine\[\]\[/g, 'InvoiceLine[' + lineIndex + '][');
        $('#invoice-lines').append(template);
        lineIndex++;
        attachLineEvents();
    });
    
    // Remove line
    $(document).on('click', '.remove-line-btn', function() {
        $(this).closest('.invoice-line').remove();
        calculateTotals();
    });
    
    // Item selection change
    $(document).on('change', '.item-select', function() {
        var itemId = $(this).val();
        var line = $(this).closest('.invoice-line');
        
        if (itemId && itemsData[itemId]) {
            var item = itemsData[itemId];
            line.find('.unit-price-display').val(parseFloat(item.price).toFixed(2));
            line.find('.vat-percent-display').val(parseFloat(item.vat_percent).toFixed(2));
            line.find('.pp-percent-display').val(parseFloat(item.pp_percent).toFixed(2));
        } else {
            line.find('.unit-price-display').val('');
            line.find('.vat-percent-display').val('');
            line.find('.pp-percent-display').val('');
        }
        calculateLineTotal(line);
    });
    
    // Quantity change
    $(document).on('input', '.quantity-input', function() {
        var line = $(this).closest('.invoice-line');
        calculateLineTotal(line);
    });
    
    function calculateLineTotal(line) {
        var quantity = parseFloat(line.find('.quantity-input').val()) || 0;
        var unitPrice = parseFloat(line.find('.unit-price-display').val()) || 0;
        var vatPercent = parseFloat(line.find('.vat-percent-display').val()) || 0;
        var ppPercent = parseFloat(line.find('.pp-percent-display').val()) || 0;
        
        var net = quantity * unitPrice;
        var vat = net * (vatPercent / 100);
        var pp = net * (ppPercent / 100);
        var gross = net + vat + pp;
        
        line.find('.line-total-display').val(gross.toFixed(2));
        calculateTotals();
    }
    
    function calculateTotals() {
        var totalNet = 0;
        var totalVat = 0;
        var totalPp = 0;
        var totalGross = 0;
        
        $('.invoice-line').each(function() {
            var quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
            var unitPrice = parseFloat($(this).find('.unit-price-display').val()) || 0;
            var vatPercent = parseFloat($(this).find('.vat-percent-display').val()) || 0;
            var ppPercent = parseFloat($(this).find('.pp-percent-display').val()) || 0;
            
            var net = quantity * unitPrice;
            var vat = net * (vatPercent / 100);
            var pp = net * (ppPercent / 100);
            var gross = net + vat + pp;
            
            totalNet += net;
            totalVat += vat;
            totalPp += pp;
            totalGross += gross;
        });
        
        $('#Invoice_total_net').val(totalNet.toFixed(2));
        $('#Invoice_total_vat').val(totalVat.toFixed(2));
        $('#Invoice_total_pp').val(totalPp.toFixed(2));
        $('#Invoice_total_gross').val(totalGross.toFixed(2));
    }
    
    function attachLineEvents() {
        // Re-attach events to newly added elements if needed
    }
    
    // Add first line by default
    $('#add-line-btn').click();
});
</script>