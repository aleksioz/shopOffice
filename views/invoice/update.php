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

$items = Item::model()->findAll();
$invoiceLines = InvoiceLine::model()->findAllByAttributes(['invoice_id'=>$model->id]);

?>

<h1>Update Invoice <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_existing_inv_lines', array('invoiceLines'=>$invoiceLines)); ?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>

<?php $this->renderPartial('_invoice_line', array('model'=>$model, 'items'=>$items)); ?>