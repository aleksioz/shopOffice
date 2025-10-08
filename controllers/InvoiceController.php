<?php
class InvoiceController extends Controller
{
    public function actionIndex(){
        $model = new Invoice();
        $invoices = $model->findAll();
        $this->render('index', ['invoices'=>$invoices]);
    }

    public function actionView($id){
        $invoice = $this->loadModel($id);
        $this->render('view', ['model'=>$invoice]);
    }

    public function actionCreate(){
        $model = new Invoice();
        $model->date = date('Y-m-d');
        $model->status = 'draft';
        
        // Get all available items for selection
        $items = Item::model()->findAll();
        
        if(isset($_POST['Invoice'])) // form submitted
        {
            $model->attributes = $_POST['Invoice'];
            
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
                var_dump('ERR:', $e->getMessage());
                $transaction->rollback();
                Yii::app()->user->setFlash('error', 'Error creating invoice: ' . $e->getMessage());
            }
        }
        
        $this->render('create', array('model'=>$model, 'items'=>$items));
    }

    public function actionUpdate($id){
        $model = $this->loadModel($id);
        if($model->status === 'closed'){
            throw new CHttpException(400, 'Ne možete uređivati zatvoren račun.');
        }

        if(isset($_POST['Invoice'])){
            $model->attributes = $_POST['Invoice'];
            if($model->save()) $this->redirect(['view','id'=>$model->id]);
        }

        // učitaj dostupne artikle za dodavanje linija
        $items = Item::model()->findAll();

        $this->render('update', ['model'=>$model,'items'=>$items]);
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

    protected function loadModel($id){
        $m = Invoice::model()->findByPk($id);
        if($m===null) throw new CHttpException(404,'Not found');
        return $m;
    }
}
