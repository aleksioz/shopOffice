<?php
/* @var $this InvoiceController */
/* @var $model Invoice */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'invoice-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'internal_number'); ?>
		<?php echo $form->textField($model,'internal_number',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'internal_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'payment_method'); ?>
		<?php echo $form->textField($model,'payment_method',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'payment_method'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'note'); ?>
		<?php echo $form->textField($model,'note',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'note'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<!-- Invoice Lines Section -->
	<div class="invoice-lines-section">
		<h3>Invoice Items</h3>
		<div id="invoice-lines">
			<!-- Existing invoice lines (if editing) -->
			 
			<?php if(!empty($invoiceLines)): ?>
			<?php $this->renderPartial('_existing_inv_lines', array('invoiceLines'=>$invoiceLines)); ?>
			<?php endif; ?>

		</div>
		<button type="button" id="add-line-btn" class="btn btn-secondary">Add Item</button>
	</div>

	<!-- Totals Section -->
	<div class="totals-section">

		<div class="row">
			<?php echo $form->labelEx($model,'total_net'); ?>
			<?php echo $form->textField($model,'total_net',array('size'=>14,'maxlength'=>14)); ?>
			<?php echo $form->error($model,'total_net'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'total_vat'); ?>
			<?php echo $form->textField($model,'total_vat',array('size'=>14,'maxlength'=>14)); ?>
			<?php echo $form->error($model,'total_vat'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'total_pp'); ?>
			<?php echo $form->textField($model,'total_pp',array('size'=>14,'maxlength'=>14)); ?>
			<?php echo $form->error($model,'total_pp'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'total_gross'); ?>
			<?php echo $form->textField($model,'total_gross',array('size'=>14,'maxlength'=>14)); ?>
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
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
		<?php if(!$model->isNewRecord): ?>
			<?php echo CHtml::button('Delete', array(
				'class'=>'delete-button',
				'style'=>'background-color: #d9534f; color: white; border: 1px solid #d43f3a; padding: 6px 12px; margin-left: 10px;',
				'onclick'=>'deleteItem('.$model->id.', '.$model->status.');'
			)); ?>
		<?php endif; ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


<?php if(!$model->isNewRecord): ?>
<script type="text/javascript">
function deleteItem(id, status='draft') {
	if(status !== 'draft') {
		alert('Closed Invoice cannot be deleted.');
		return;
	}
	if(confirm('Are you sure you want to delete this Invoice?')) {
		// Create a form to submit the DELETE request
		var form = document.createElement('form');
		form.method = 'POST';
		form.action = '<?php echo $this->createUrl('delete', array('id' => $model->id)); ?>';
		
		// Add CSRF token if available
		<?php if(Yii::app()->request->enableCsrfValidation): ?>
		var csrfInput = document.createElement('input');
		csrfInput.type = 'hidden';
		csrfInput.name = '<?php echo Yii::app()->request->csrfTokenName; ?>';
		csrfInput.value = '<?php echo Yii::app()->request->csrfToken; ?>';
		form.appendChild(csrfInput);
		<?php endif; ?>
		
		document.body.appendChild(form);
		form.submit();
	}
}
</script>
<?php endif; ?>


<?php
	$this->renderPartial('_invoice_line', array('items' => isset($items) ? $items : []));
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function initializeSelect2(element) {
        $(element).select2({
            ajax: {
                url: '<?php echo $this->createUrl("/shopOffice/item/list"); ?>',
                dataType: 'json',
                delay: 350,
                data: function (params) {
                    return {
                        search: params.term // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            placeholder: 'Search for an item',
            minimumInputLength: 3,
        });
    }

    $('#add-line-btn').click(function() {
        const template = $('#line-template').html();
        const newRow = template.replace(/invoice-line-hidden/g, 'invoice-line').replace(/\[\]/g, '[]');
        $('#invoice-lines').append(newRow);

        const newSelect = $('#invoice-lines .invoice-line:last .item-select');
        initializeSelect2(newSelect);
    });

    $(document).on('click', '.remove-line-btn', function() {
        $(this).closest('.invoice-line, .invoice-line-hidden').remove();
    });

    // Initialize Select2 for existing lines
    // $('.invoice-line select').each(function() {
    //     initializeSelect2(this);
    // });

	// Item selection change
    $(document).on('select2:select', '.item-select', function (e) {
		const line = $(this).closest('.invoice-line');
		const item = e.params.data;
		console.log('line:', line);	

        if (item) {
            line.find('.unit-price-input').val(parseFloat(item.price).toFixed(4));
            line.find('.vat-percent-input').val(parseFloat(item.vat_percent).toFixed(4));
            line.find('.pp-percent-input').val(parseFloat(item.pp_percent).toFixed(4));
			calculateLineTotal(line);
        }
    });
    
    // Quantity change
    $(document).on('input', '.quantity-input', function() {
        const line = $(this).closest('.invoice-line');
        calculateLineTotal(line);
    });
    
    function calculateLineTotal(line) {

        const quantity = parseFloat(line.find('.quantity-input').val()) || 0.0;
        const unitPrice = parseFloat(line.find('.unit-price-input').val()) || 0.0;
        const vatPercent = parseFloat(line.find('.vat-percent-input').val()) || 0.0;
        const ppPercent = parseFloat(line.find('.pp-percent-input').val()) || 0.0;

        const net = quantity * unitPrice;
        const vat = net * (vatPercent / 100);
        const pp = net * (ppPercent / 100);
        const gross = net + vat + pp;

		line.find('.line-pp-input').val(pp.toFixed(4));
		line.find('.line-vat-input').val(vat.toFixed(4));
		line.find('.line-net-input').val(net.toFixed(4));
        line.find('.line-total-input').val(gross.toFixed(4));
        calculateTotals();
    }
    
    function calculateTotals() {
        let totalNet = 0;
        let totalVat = 0;
        let totalPp = 0;
        let totalGross = 0;
        
        $('.invoice-line').each(function() {
            const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
            const unitPrice = parseFloat($(this).find('.unit-price-input').val()) || 0;
            const vatPercent = parseFloat($(this).find('.vat-percent-input').val()) || 0;
            const ppPercent = parseFloat($(this).find('.pp-percent-input').val()) || 0;

            const net = quantity * unitPrice;
            const vat = net * (vatPercent / 100);
            const pp = net * (ppPercent / 100);
            const gross = net + vat + pp;

            totalNet += net;
            totalVat += vat;
            totalPp += pp;
            totalGross += gross;
        });

		console.log('Totals calculated:', {
			totalNet: totalNet,
			totalVat: totalVat,
			totalPp: totalPp,
			totalGross: totalGross
		});

        $('#Invoice_total_net').val(totalNet.toFixed(4));
        $('#Invoice_total_vat').val(totalVat.toFixed(4));
        $('#Invoice_total_pp').val(totalPp.toFixed(4));
        $('#Invoice_total_gross').val(totalGross.toFixed(4));
    }
});
</script>