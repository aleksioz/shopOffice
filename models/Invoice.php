<?php
class Invoice extends CActiveRecord
{

    public static function model($className=__CLASS__){ return parent::model($className); }
    public function tableName(){ return 'invoice'; }

    public function rules(){
        return [
            ['number, internal_number, date', 'required'],
            ['number, internal_number', 'length', 'max' => 50],
            ['status', 'in', 'range' => ['draft','closed']],
            ['total_net, total_vat, total_pp, total_gross', 'numerical'],
            ['date, created_at, updated_at', 'safe'],
        ];
    }

    public function relations(){
        return [
            'lines' => [self::HAS_MANY, 'InvoiceLine', 'invoice_id'],
        ];
    }

    public function attributeLabels(){
        return [
			'id'=>'ID',
			'number'=>'Račun broj:',
			'internal_number'=>'Interni Broj',
			'payment_method'=>'Način plaćanja',
			'note'=>'Napomena',
			'date'=>'Datum i vrijeme',
			'total_net'=>'Osnovica',
			'total_vat'=>'PDV',
			'total_pp'=>'PP',
			'total_gross'=>'Ukupno',
			'status'=>'Status',
		];
    }

    // Prevent changes if closed
    protected function beforeSave(){

        if(!$this->isNewRecord){
            $old = self::model()->findByPk($this->id);
            if($old->status === 'closed'){
                throw new CHttpException(400, 'Closed Invoice cannot be modified.');
            }
        }
        return parent::beforeSave();
    }


    // Update totals based on lines
    public function updateTotals(){
        
        $lines = InvoiceLine::model()->findAllByAttributes(['invoice_id'=>$this->id]);

        if( !$lines || !is_array($lines) ) 
            throw new CHttpException(400, 'No invoice lines found to calculate totals, so invoice is empty.');
	
        $totalNet = 0;
        $totalVat = 0;
        $totalPp = 0;
        $totalGross = 0;

        foreach($lines as $line) {
            $totalNet += (float)$line->line_net;
            $totalVat += (float)$line->line_vat;
            $totalPp += (float)$line->line_pp;
            $totalGross += (float)$line->line_gross;
        }

        $this->total_net = $totalNet;
        $this->total_vat = $totalVat;
        $this->total_pp = $totalPp;
        $this->total_gross = $totalGross;
    }

	/**
	 * Saves an invoice to the database or storage system.
	 *
	 * This function handles the creation or updating of invoice records,
	 * including validation of invoice data and persistence to the data store.
	 *
	 * @param array $invoiceData The invoice data to be saved
	 * @return bool|int Returns true on successful save, or invoice ID if newly created, false on failure
	 * @throws InvalidArgumentException When invoice data is invalid
	 * @throws DatabaseException When database operation fails
	 */
	public function saveInvoice(){

		$this->attributes=$_POST['Invoice'];
		
		// Sanitize and validate input data
		$this->payment_method = isset($_POST['Invoice']['payment_method']) ? 
			CHtml::encode(strip_tags($_POST['Invoice']['payment_method'])) : '';
		$this->note = isset($_POST['Invoice']['note']) ? 
			CHtml::encode(strip_tags($_POST['Invoice']['note'])) : '';

		// Start transaction for saving invoice and its lines
		$transaction = Yii::app()->db->beginTransaction();

		try {
			// Handle invoice lines if provided
			if(!isset($_POST['InvoiceLine']) || !is_array($_POST['InvoiceLine']) || count($_POST['InvoiceLine']) != 2)
				throw new Exception('Invalid invoice line data');

			// First, delete existing lines for update scenarios
			InvoiceLine::model()->deleteAllByAttributes(['invoice_id' => $this->id]);

			do {
				$item_id = array_shift($_POST['InvoiceLine']['item_id']);
				$quantity = array_shift($_POST['InvoiceLine']['quantity']);
				
				if( empty($item_id) || empty($quantity) ) break;

				// Get item details to populate line data, we want Items from our DB not user input
				$item = Item::model()->findByPk($item_id);
			
				$line = new InvoiceLine();
				$line->invoice_id = $this->id;
				$line->item_id = $item_id;
				$line->quantity = $quantity;

				$line->unit_price = $item->price;
				$line->vat_percent = $item->vat_percent;
				$line->pp_percent = $item->pp_percent;
				$line->line_name = $item->name;

				if(!$line->save()) 
					throw new Exception('Failed to save invoice line');

			}
			while (!empty($_POST['InvoiceLine']['item_id']) || !empty($_POST['InvoiceLine']['quantity']));

			$this->updateTotals();

			if(!$this->save()) {
				$transaction->rollback();
				throw new Exception('Failed to save invoice');
			}

			$transaction->commit();

		} catch(Exception $e) {
			$transaction->rollback();
			Yii::app()->user->setFlash('error', 'Error creating iiinvoice: ' . $e->getMessage());
			echo $e->getMessage();
		}
	}

}