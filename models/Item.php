<?php
class Item extends CActiveRecord
{
    public static function model($className=__CLASS__){ return parent::model($className); }
    public function tableName(){ return 'item'; }

    public function rules(){
        return [
            ['name, sku, unit, price, vat_percent, pp_percent', 'required'],
            ['price, vat_percent, pp_percent', 'numerical'],
            ['name', 'length', 'max'=>255],
            ['sku', 'length', 'max'=>100],	
            ['unit', 'length', 'max'=>50],
            ['created_at, updated_at', 'safe'],
        ];
    }

    public function attributeLabels(){
        return [
            'id'=>'ID',
			'name'=>'Naziv',
			'sku'=>'Oznaka',
			'unit'=>'Jedinica mjere',
			'price'=>'Cijena bez PDV',
            'vat_percent'=>'PDV %',
			'pp_percent'=>'PP %',
			'created_at'=>'Kreirano',
			'updated_at'=>'Ažurirano'
        ];
    }
}
