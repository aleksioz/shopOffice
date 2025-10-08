<?php
class InvoiceController extends Controller
{
    /**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Invoice;
        $model->date = date('Y-m-d');
        $model->status = 'draft';

		if(isset($_POST['Invoice']))
		{
			$model->attributes=$_POST['Invoice'];
			
			// Sanitize and validate input data
			$model->payment_method = isset($_POST['Invoice']['payment_method']) ? 
				CHtml::encode(strip_tags($_POST['Invoice']['payment_method'])) : '';
			$model->note = isset($_POST['Invoice']['note']) ? 
				CHtml::encode(strip_tags($_POST['Invoice']['note'])) : '';

			// Start transaction for saving invoice and its lines
            $transaction = Yii::app()->db->beginTransaction();

            try {
                if($model->save()) {
                
                    // Handle invoice lines if provided
                    if(isset($_POST['InvoiceLine']) && is_array($_POST['InvoiceLine'])) {
                        foreach($_POST['InvoiceLine'] as $lineData) {
                            if(!empty($lineData['item_id']) && !empty($lineData['quantity'])) {
                                $line = new InvoiceLine();	
                                
                                $line->invoice_id = $model->id;
                                $line->item_id = $lineData['item_id'];
                                $line->quantity = $lineData['quantity'];
                                
                                // Get item details to populate line data
                                $item = Item::model()->findByPk($lineData['item_id']);
                                
                                if($item) {
                                    $line->unit_price = $item->price;
                                    $line->vat_percent = $item->vat_percent;
                                    $line->pp_percent = $item->pp_percent;
                                    $line->line_name = $item->name;
                                }

                                if(!$line->save()) {
                                    throw new Exception('Failed to save invoice line');
                                }
                            }
                        }
                    }
                    
                    $transaction->commit();
                    $this->redirect(['update','id'=>$model->id]);
                } else {
                    $transaction->rollback();
                }
            } catch(Exception $e) {
                $transaction->rollback();
                Yii::app()->user->setFlash('error', 'Error creating invoice: ' . $e->getMessage());
                echo $e->getMessage();
            }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Invoice']))
		{
			$model->attributes=$_POST['Invoice'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

    /**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}


    /**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Invoice');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}



	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Invoice('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Invoice']))
			$model->attributes=$_GET['Invoice'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}




	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Invoice the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Invoice::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	/**
	 * Performs the AJAX validation.
	 * @param Invoice $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='invoice-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionAddLine($invoiceId){
        $invoice = $this->loadModel($invoiceId);
        if($invoice->status === 'closed') throw new CHttpException(400,'Invoice closed.');

        $line = new InvoiceLine();
        if(isset($_POST['InvoiceLine'])){
            $line->attributes = $_POST['InvoiceLine'];
            $line->invoice_id = $invoice->id;
            // ako je postavljen item_id, prenesi cijenu i porese
            if($line->item_id){
                $item = Item::model()->findByPk($line->item_id);
                if($item){
                    $line->unit_price = $item->price;
                    $line->vat_percent = $item->vat_percent;
                    $line->pp_percent = $item->pp_percent;
                    $line->description = $item->name;
                }
            }
            if($line->save()) $this->redirect(['update','id'=>$invoice->id]);
        }
        $this->renderPartial('_line_form', ['model'=>$line, 'invoice'=>$invoice]);
    }

    public function actionRemoveLine($id){
        $line = InvoiceLine::model()->findByPk($id);
        if(!$line) throw new CHttpException(404,'Not found');
        $invoice = $line->invoice;
        if($invoice->status === 'closed') throw new CHttpException(400,'Invoice closed.');
        $line->delete();
        $this->redirect(['update','id'=>$invoice->id]);
    }

    public function actionClose($id){
        $invoice = $this->loadModel($id);
        try{
            InvoiceService::closeInvoice($invoice);
            Yii::app()->user->setFlash('success','Invoice closed.');
        } catch(Exception $e){
            Yii::app()->user->setFlash('error','Failed to close: '.$e->getMessage());
        }
        $this->redirect(['view','id'=>$id]);
    }

}
