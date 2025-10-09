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
							<input type="text" name="unit_price_display" readonly class="unit-price-display" value="<?php echo $line->item ? number_format($line->item->price, 4) : ''; ?>" />
						</div>
						<div class="row">
							<label>VAT %:</label>
							<input type="text" name="vat_percent_display" readonly class="vat-percent-display" value="<?php echo $line->item ? number_format($line->item->vat_percent, 4) : ''; ?>" />
						</div>
						<div class="row">
							<label>PP %:</label>
							<input type="text" name="pp_percent_display" readonly class="pp-percent-display" value="<?php echo $line->item ? number_format($line->item->pp_percent, 4) : ''; ?>" />
						</div>
						<div class="row">
							<label>Line Total:</label>
							<input type="text" name="line_total_display" readonly class="line-total-display" value="<?php echo number_format($line->total_gross, 4); ?>" />
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