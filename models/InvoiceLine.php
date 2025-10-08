<?php
class InvoiceLine extends CActiveRecord
{
    public static function model($className=__CLASS__){ return parent::model($className); }
    public function tableName(){ return 'invoice_line'; }

    public function rules(){
        return [
            ['invoice_id, quantity, unit_price, vat_percent, pp_percent', 'required'],
            ['quantity, unit_price, vat_percent, pp_percent', 'numerical'],
            ['line_name', 'length', 'max'=>512],
            ['created_at, updated_at', 'safe'],
        ];
    }

    public function relations(){
        return [
            'invoice' => [self::BELONGS_TO, 'Invoice', 'invoice_id'],
            'item' => [self::BELONGS_TO, 'Item', 'item_id'],
        ];
    }

    public function attributeLabels(){
        return [
			'id'=>'ID',
            'sn'=>'Rbr',
            'line_sku'=>'Oznaka',
            'line_name'=>'Artikal/Usluga',
			'quantity'=>'Kol.',
            'line_unit'=>'J.m.',
            'unit_price'=>'Cijena bez PDV',
			'line_vat'=>'% PDV',
			'line_pp'=>'% PP',
            'line_gross'=>'Iznos',
		];
    }

    // Helper: calculate amounts for this line
    public function calculateLineTotals()
    {
        // net
        $net = round((float)$this->unit_price * (float)$this->quantity, 4);
        // vat amount = net * vat% / 100
        $vat = round($net * ((float)$this->vat_percent / 100), 4);
        // pp amount = net * pp% / 100
        $pp = round($net * ((float)$this->pp_percent / 100), 4);
        // gross = net + vat + pp
        $gross = round($net + $vat + $pp, 4);

        return [
            'line_gross' => $gross
        ];
    }

    protected function beforeSave(){
        // Always ensure amounts are up-to-date (for consistency)
        $a = $this->calculateLineTotals();
        $this->line_gross = $a['line_gross'];
        return parent::beforeSave();
    }
}
