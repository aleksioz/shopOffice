<?php

class ShopOfficeModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'shopOffice.models.*',
			'shopOffice.components.*',
		));

		

	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{

			// Add menu items to main layout menu
			Yii::app()->getController()->widget('zii.widgets.CMenu', array(
				'items' => array(
				array('label'=>'Invoices', 'url'=>array('/shopOffice/invoice/index')),
				array('label'=>'Items', 'url'=>array('/shopOffice/item/index'))
				),
				'htmlOptions' => array('class' => 'menu')
			));

			// Set fullwidth layout for all controllers in this module
			// Use the module's layout path
			// $controller->layout = 'shopOffice.views.layouts.main';
			
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
