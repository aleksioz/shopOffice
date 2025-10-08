<?php

class m251007_194101_invoice_line extends CDbMigration
{
	
	public function up()
	{
		$this->createTable('invoice_line', array(
			'id' => 'pk',
			'invoice_id' => 'int NOT NULL',
			'item_id' => 'int NULL',
			'sn' => 'int DEFAULT 0',
			'line_sku' => 'varchar(100) DEFAULT NULL',
			'line_name' => 'varchar(255) DEFAULT NULL',
			'quantity' => 'decimal(12,3) NOT NULL DEFAULT 1.000',
			'unit_price' => 'decimal(12,4) NOT NULL DEFAULT 0.0000',
			'vat_percent' => 'decimal(6,4) NOT NULL DEFAULT 25.0000',
			'pp_percent' => 'decimal(6,4) NOT NULL DEFAULT 3.0000',
			'line_net' => 'decimal(14,4) DEFAULT 0.0000',
			'line_vat' => 'decimal(14,4) DEFAULT 0.0000',
			'line_pp' => 'decimal(14,4) DEFAULT 0.0000',
			'line_gross' => 'decimal(14,4) DEFAULT 0.0000',
			'created_at' => 'datetime DEFAULT CURRENT_TIMESTAMP',
			'updated_at' => 'datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

		// Seed data - 150 invoice lines connected to random invoice IDs (1-10)
		$items = [
			['id' => 1, 'sku' => 'ART-001', 'name' => 'Čekić', 'price' => 12.50, 'vat_percent' => 25.0, 'pp_percent' => 3.0],
			['id' => 2, 'sku' => 'ART-002', 'name' => 'Odvijač', 'price' => 8.90, 'vat_percent' => 25.0, 'pp_percent' => 3.0],
			['id' => 3, 'sku' => 'ART-003', 'name' => 'Klešta', 'price' => 15.30, 'vat_percent' => 25.0, 'pp_percent' => 3.0],
			['id' => 4, 'sku' => 'ART-004', 'name' => 'Brusilica', 'price' => 89.99, 'vat_percent' => 25.0, 'pp_percent' => 3.0],
			['id' => 5, 'sku' => 'ART-005', 'name' => 'Bušilica', 'price' => 129.00, 'vat_percent' => 25.0, 'pp_percent' => 3.0],
			['id' => 6, 'sku' => 'ART-006', 'name' => 'Ekseri 5cm (100kom)', 'price' => 5.00, 'vat_percent' => 25.0, 'pp_percent' => 3.0],
			['id' => 7, 'sku' => 'ART-007', 'name' => 'Vijci 4x30mm (100kom)', 'price' => 6.50, 'vat_percent' => 25.0, 'pp_percent' => 3.0],
			['id' => 8, 'sku' => 'ART-008', 'name' => 'Rukavice zaštitne', 'price' => 4.20, 'vat_percent' => 25.0, 'pp_percent' => 3.0],
			['id' => 9, 'sku' => 'ART-009', 'name' => 'Maska za lice', 'price' => 2.70, 'vat_percent' => 25.0, 'pp_percent' => 3.0],
			['id' => 10, 'sku' => 'ART-010', 'name' => 'Metar 5m', 'price' => 3.80, 'vat_percent' => 25.0, 'pp_percent' => 3.0],
		];

		// Generate 150 invoice lines
		
		// Write to CSV file, exposing seed data for invoice seeding
		$csvFile = __DIR__ . '/temp_seed_inv_lines.csv';
		file_put_contents($csvFile, "invoice_id;item_id;sn;line_sku;line_name;quantity;unit_price;vat_percent;pp_percent;line_net;line_vat;line_pp;line_gross\n");
		
		for ($i = 1; $i <= 150; $i++) {
			// Random invoice ID between 1-10
			$invoiceId = rand(1, 10);
			
			// Random item
			$itemIndex = rand(0, 9);
			$item = $items[$itemIndex];
			
			// Random quantity between 1-5
			$quantity = rand(1, 5); // Add decimal precision
			
			// Use item's price with some variation (+/- 20%)
			$unitPrice = $item['price'];
			
			// Calculate amounts
			$lineNet = $quantity * $unitPrice;
			$lineVat = $lineNet * ($item['vat_percent'] / 100);
			$linePp = $lineNet * ($item['pp_percent'] / 100);
			$lineGross = $lineNet + $lineVat + $linePp;

			$this->insert('invoice_line', [
				'invoice_id' => $invoiceId,
				'item_id' => $item['id'],
				'sn' => $i,
				'line_sku' => $item['sku'],
				'line_name' => $item['name'],
				'quantity' => $quantity,
				'unit_price' => $unitPrice,
				'vat_percent' => $item['vat_percent'],
				'pp_percent' => $item['pp_percent'],
				'line_net' => $item['price'],
				'line_vat' => $item['vat_percent'],
				'line_pp' => $item['pp_percent'],
				'line_gross' => round($lineGross, 4),
			]);

			
			$csvData = [ $invoiceId, $item['id'], $i, $item['sku'],	$item['name'], $quantity, $unitPrice, $item['vat_percent'],	$item['pp_percent'], $item['price'], $item['vat_percent'], $item['pp_percent'],	round($lineGross, 4) ];

			file_put_contents($csvFile, implode(';', $csvData) . "\n", FILE_APPEND);

		}
	}

	public function down()
	{
		$this->dropTable('invoice_line');
	}
	
}