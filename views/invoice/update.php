<?php
/* @var $this InvoiceController */
/* @var $model Invoice */

$this->breadcrumbs=array(
	'Invoices'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Invoice', 'url'=>array('index')),
	array('label'=>'Create Invoice', 'url'=>array('create')),
	array('label'=>'View Invoice', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Invoice', 'url'=>array('admin')),
);

// Add Close Invoice button only if status is not closed
if($model->status !== 'closed') {
	$this->menu[] = array(
		'label'=>'Close Invoice', 
		'url'=>'#', 
		'linkOptions'=>array(
			'submit'=>array('close','id'=>$model->id),
			'confirm'=>'Are you sure you want to close this invoice? Once closed, it cannot be modified.',
			'class'=>'btn btn-warning'
		)
	);
}

$items = Item::model()->findAll();
$invoiceLines = InvoiceLine::model()->findAllByAttributes(['invoice_id'=>$model->id]);

?>

<h1>Update Invoice <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_existing_inv_lines', array('invoiceLines'=>$invoiceLines)); ?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>

<?php $this->renderPartial('_invoice_line', array('model'=>$model, 'items'=>$items)); ?>