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
            if($old && $old->status === 'closed' && $this->status !== 'closed'){
                // allow changing status only to closed, but not re-opening:
                throw new CHttpException(400, 'Closed Invoice cannot be modified.');
            }
        }
        return parent::beforeSave();
    }
}