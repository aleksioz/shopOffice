<?php
/* @var $this ItemControllerController */
/* @var $model Item */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'item-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sku'); ?>
		<?php echo $form->textField($model,'sku',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'sku'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'unit'); ?>
		<?php echo $form->textField($model,'unit',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'unit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vat_percent'); ?>
		<?php echo $form->textField($model,'vat_percent',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'vat_percent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pp_percent'); ?>
		<?php echo $form->textField($model,'pp_percent',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'pp_percent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
		<?php echo $form->error($model,'created_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated_at'); ?>
		<?php echo $form->textField($model,'updated_at'); ?>
		<?php echo $form->error($model,'updated_at'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
		<?php if(!$model->isNewRecord): ?>
			<?php echo CHtml::button('Delete', array(
				'class'=>'delete-button',
				'style'=>'background-color: #d9534f; color: white; border: 1px solid #d43f3a; padding: 6px 12px; margin-left: 10px;',
				'onclick'=>'deleteItem('.$model->id.');'
			)); ?>
		<?php endif; ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php if(!$model->isNewRecord): ?>
<script type="text/javascript">
function deleteItem(id) {
	if(confirm('Are you sure you want to delete this item?')) {
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