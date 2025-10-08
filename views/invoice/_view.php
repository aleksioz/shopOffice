<?php
/* @var $this InvoiceController */
/* @var $data Invoice */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('number')); ?>:</b>
	<?php echo CHtml::encode($data->number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('internal_number')); ?>:</b>
	<?php echo CHtml::encode($data->internal_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('payment_method')); ?>:</b>
	<?php echo CHtml::encode($data->payment_method); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::encode($data->note); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('total_net')); ?>:</b>
	<?php echo CHtml::encode($data->total_net); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_vat')); ?>:</b>
	<?php echo CHtml::encode($data->total_vat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_pp')); ?>:</b>
	<?php echo CHtml::encode($data->total_pp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total_gross')); ?>:</b>
	<?php echo CHtml::encode($data->total_gross); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_at')); ?>:</b>
	<?php echo CHtml::encode($data->updated_at); ?>
	<br />

	*/ ?>

</div>