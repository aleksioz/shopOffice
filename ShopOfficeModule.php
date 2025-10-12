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

		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerCssFile("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css");
		Yii::app()->clientScript->registerScriptFile("https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", CClientScript::POS_END);
		
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
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
