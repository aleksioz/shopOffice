<?php

class m251007_193937_item extends CDbMigration
{

	public function up()
	{
		$this->createTable('item', array(
			'id' => 'pk',
			'name' => 'string NOT NULL',
			'sku' => 'varchar(100) DEFAULT NULL',
			'unit' => 'varchar(50) NOT NULL', // Jedinica mjere
			'price' => 'decimal(12,4) NOT NULL DEFAULT 0.0000',
			'vat_percent' => 'decimal(6,4) NOT NULL DEFAULT 25.0000',
			'pp_percent' => 'decimal(6,4) NOT NULL DEFAULT 3.0000',
			'created_at' => 'datetime DEFAULT CURRENT_TIMESTAMP',
			'updated_at' => 'datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

		// Seed podaci (10 artikala)
		$items = [
			['name' => 'Čekić', 'sku' => 'ART-001', 'unit' => 'kom', 'price' => 12.50],
			['name' => 'Odvijač', 'sku' => 'ART-002', 'unit' => 'kom', 'price' => 8.90],
			['name' => 'Klešta', 'sku' => 'ART-003', 'unit' => 'kom', 'price' => 15.30],
			['name' => 'Brusilica', 'sku' => 'ART-004', 'unit' => 'kom', 'price' => 89.99],
			['name' => 'Bušilica', 'sku' => 'ART-005', 'unit' => 'kom', 'price' => 129.00],
			['name' => 'Ekseri 5cm (100kom)', 'sku' => 'ART-006', 'unit' => 'pak', 'price' => 5.00],
			['name' => 'Vijci 4x30mm (100kom)', 'sku' => 'ART-007', 'unit' => 'pak', 'price' => 6.50],
			['name' => 'Rukavice zaštitne', 'sku' => 'ART-008', 'unit' => 'par', 'price' => 4.20],
			['name' => 'Maska za lice', 'sku' => 'ART-009', 'unit' => 'kom', 'price' => 2.70],
			['name' => 'Metar 5m', 'sku' => 'ART-010', 'unit' => 'kom', 'price' => 3.80],
		];

		foreach ($items as $item) {
			$this->insert('item', array_merge($item, [
				'vat_percent' => 25.0,
				'pp_percent' => 3.0,
			]));
		}
	}

	public function down()
	{
		$this->dropTable('item');
	}
}