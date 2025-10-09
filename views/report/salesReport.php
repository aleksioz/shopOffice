<?php
/* @var $this ReportController */
/* @var $from string */
/* @var $to string */
/* @var $totals array */

$this->breadcrumbs=array(
	'Reports'=>array('salesReport'),
	'Sales Report',
);

$this->menu=array(
	array('label'=>'Back to Shop Office', 'url'=>array('/shopOffice/default/index')),
	array('label'=>'Invoices', 'url'=>array('/shopOffice/invoice/index')),
);
?>

<h1>Sales Report</h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sales-report-form',
	'enableAjaxValidation'=>false,
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo CHtml::label('Date From', 'date_from'); ?>
		<?php echo CHtml::dateField('date_from', $from, array('class'=>'form-control')); ?>
	</div>

	<div class="row">
		<?php echo CHtml::label('Date To', 'date_to'); ?>
		<?php echo CHtml::dateField('date_to', $to, array('class'=>'form-control')); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Generate Report', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<div class="report-results">
	<h2>Sales Summary</h2>
	<p><strong>Report Period:</strong> <?php echo CHtml::encode($from); ?> to <?php echo CHtml::encode($to); ?></p>
	
	<?php if($totals): ?>
	<table class="items table table-striped">
		<thead>
			<tr>
				<th>Metric</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><strong>Total Net (Osnovica)</strong></td>
				<td><?php echo number_format((float)$totals['total_net'], 2, ',', '.'); ?> Euro</td>
			</tr>
			<tr>
				<td><strong>Total VAT (PDV)</strong></td>
				<td><?php echo number_format((float)$totals['total_vat'], 2, ',', '.'); ?> Euro</td>
			</tr>
			<tr>
				<td><strong>Total PP</strong></td>
				<td><?php echo number_format((float)$totals['total_pp'], 2, ',', '.'); ?> Euro</td>
			</tr>
			<tr class="total-row">
				<td><strong>Total Gross (Ukupno)</strong></td>
				<td><strong><?php echo number_format((float)$totals['total_gross'], 2, ',', '.'); ?> Euro</strong></td>
			</tr>
		</tbody>
	</table>
	<?php else: ?>
	<div class="alert alert-info">
		<p>No sales data found for the selected period.</p>
	</div>
	<?php endif; ?>
</div>

<style>
.form .row {
	margin-bottom: 15px;
}

.form label {
	display: inline-block;
	width: 120px;
	font-weight: bold;
}

.form input[type="date"] {
	padding: 5px;
	border: 1px solid #ccc;
	border-radius: 3px;
	width: 200px;
}

.btn {
	padding: 8px 16px;
	border: none;
	border-radius: 3px;
	cursor: pointer;
}

.btn-primary {
	background-color: #337ab7;
	color: white;
}

.btn-primary:hover {
	background-color: #286090;
}

.report-results {
	margin-top: 30px;
	padding: 20px;
	border: 1px solid #ddd;
	border-radius: 5px;
	background-color: #f9f9f9;
}

.table {
	width: 100%;
	border-collapse: collapse;
	margin-top: 15px;
}

.table th,
.table td {
	padding: 10px;
	text-align: left;
	border-bottom: 1px solid #ddd;
}

.table th {
	background-color: #f5f5f5;
	font-weight: bold;
}

.table-striped tbody tr:nth-child(even) {
	background-color: #f9f9f9;
}

.total-row {
	background-color: #e8f4fd !important;
	font-size: 1.1em;
}

.alert {
	padding: 15px;
	margin-bottom: 20px;
	border: 1px solid transparent;
	border-radius: 4px;
}

.alert-info {
	color: #31708f;
	background-color: #d9edf7;
	border-color: #bce8f1;
}
</style>