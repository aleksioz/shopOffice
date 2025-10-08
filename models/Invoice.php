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

        $this->updateTotals();

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
        if(!$lines) {
            throw new CHttpException(400, 'No invoice lines found to calculate totals, so invoice is empty.');
        }


        $totalNet = 0;
        $totalVat = 0;
        $totalPp = 0;
        $totalGross = 0;

        foreach($lines as $line) {
            $this->total_net += $line->line_net;
            $this->total_vat += $line->line_vat;
            $this->total_pp += $line->line_pp;
            $this->total_gross += $line->line_gross;
        }
    }

}