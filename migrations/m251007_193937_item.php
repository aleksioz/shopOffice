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

/*
Multiplacation SQL

INSERT INTO item (name, sku, unit, price, vat_percent, pp_percent, created_at, updated_at)
SELECT 
    CONCAT(el.name, ' ', seq.i) AS name,
    CONCAT('ART-', LPAD(seq.i,3,'0')) AS sku,
    el.unit,
    ROUND(2.5 + RAND() * 250, 2) AS price,
    ELT(FLOOR(1 + RAND() * 2), 20.0, 25.0) AS vat_percent,
    ROUND(2 + RAND() * 3, 2) AS pp_percent,
    DATE_ADD('2025-10-01 10:00:00', INTERVAL FLOOR(RAND()*240) HOUR) AS created_at,
    DATE_ADD('2025-10-01 10:00:00', INTERVAL FLOOR(RAND()*240) HOUR) AS updated_at
FROM (
    SELECT 'Brusilica' AS name, 'kom' AS unit UNION ALL
    SELECT 'Bušilica','kom' UNION ALL
    SELECT 'Ekseri 5cm (100kom)','pak' UNION ALL
    SELECT 'Vijci 4x30mm (100kom)','pak' UNION ALL
    SELECT 'Rukavice zaštitne','par' UNION ALL
    SELECT 'Maska za lice','kom' UNION ALL
    SELECT 'Metar 5m','kom' UNION ALL
    SELECT 'Čekić','kom' UNION ALL
    SELECT 'Klešta','kom' UNION ALL
    SELECT 'Tester napona','kom' UNION ALL
    SELECT 'Libela','kom' UNION ALL
    SELECT 'Nož za kablove','kom' UNION ALL
    SELECT 'Odvijač','kom' UNION ALL
    SELECT 'Ključ 13mm','kom' UNION ALL
    SELECT 'Ključ 17mm','kom' UNION ALL
    SELECT 'Set burgija','pak' UNION ALL
    SELECT 'Rezač','kom' UNION ALL
    SELECT 'Letva aluminijska','kom' UNION ALL
    SELECT 'Kanta za boju','kom' UNION ALL
    SELECT 'Lepak univerzalni','pak'
) AS el
JOIN (
    SELECT @rownum := @rownum + 1 AS i FROM
    (SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL 
     SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a,
    (SELECT 0 UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL 
     SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b,
    (SELECT @rownum := 0) r
    LIMIT 150
) AS seq;
*/