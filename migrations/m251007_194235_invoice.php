<?php

class m251007_194235_invoice extends CDbMigration
{

	
	// Use safeUp/safeDown to do migration with transaction
	public function up()
	{
		$this->createTable('invoice', array(
			'id' => 'pk',
			'number' => 'varchar(50) NOT NULL UNIQUE',
			'internal_number' => 'varchar(50) NOT NULL UNIQUE',
			'payment_method' => 'text NULL', // e.g. "Transakcija", "kreditna kartica", "gotovina"
			'note' => 'text NULL',
			'date' => 'date NOT NULL',
			'status' => "enum('draft','closed') NOT NULL DEFAULT 'draft'",
			'total_net' => 'decimal(14,4) DEFAULT 0.0000',
			'total_vat' => 'decimal(14,4) DEFAULT 25.0000',
			'total_pp' => 'decimal(14,4) DEFAULT 3.0000',
			'total_gross' => 'decimal(14,4) DEFAULT 0.0000',
			'created_at' => 'datetime DEFAULT CURRENT_TIMESTAMP',
			'updated_at' => 'datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

		// Seed data - 10 invoices with IDs 1-10
		$paymentMethods = ['Transakcija', 'Kreditna kartica', 'Gotovina', 'Ček', 'Virman'];
		$statuses = ['draft', 'closed'];
		$notes = [
			'Redovna nabavka alata',
			'Hitna narudžba',
			'Sezonska nabavka',
			'Popravka opreme',
			'Novo otvaranje objekta',
			'Redovno održavanje',
			'Vanredna nabavka',
			'Zamjenska oprema',
			'Dodatna oprema',
			'Mjesečna nabavka'
		];

		// Get invoice line totals from exposed CSV file
		$csvFile = __DIR__ . '/temp_seed_inv_lines.csv';
		$invoiceLineTotals = [];
		if (file_exists($csvFile)) {
			if (($handle = fopen($csvFile, 'r')) !== false) {
				$header = fgetcsv($handle, 1000, ';');
				while (($data = fgetcsv($handle, 1000, ';')) !== false) {
					$row = array_combine($header, $data);
					$invId = $row['invoice_id'];
					if (!isset($invoiceLineTotals[$invId])) {
						$invoiceLineTotals[$invId] = [
							'net_amount' => 0.0,		
							'vat_amount' => 0.0,
							'pp_amount' => 0.0,
							'gross_amount' => 0.0,
						];
					}
					$invoiceLineTotals[$invId]['net_amount'] += (float)$row['line_net'];
					$invoiceLineTotals[$invId]['vat_amount'] += (float)$row['line_vat'];
					$invoiceLineTotals[$invId]['pp_amount'] += (float)$row['line_pp'];
					$invoiceLineTotals[$invId]['gross_amount'] += (float)$row['line_gross'];
				}
				fclose($handle);
			}
		}

		for ($i = 1; $i <= 10; $i++) {
			// Generate invoice date (last 30 days)
			$daysBack = rand(0, 30);
			$invoiceDate = date('Y-m-d', strtotime("-{$daysBack} days"));
			
			// Random payment method and status
			$paymentMethod = $paymentMethods[array_rand($paymentMethods)];
			$status = $statuses[array_rand($statuses)];
			$note = $notes[$i - 1]; // Use sequential notes
			
			// Generate invoice numbers
			$number = 'INV-2025-' . str_pad($i, 4, '0', STR_PAD_LEFT);
			$internalNumber = 'INT-' . date('Y') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT);

			// First insert the invoice with zero totals
			$this->insert('invoice', [
				'number' => $number,
				'internal_number' => $internalNumber,
				'payment_method' => $paymentMethod,
				'note' => $note,
				'date' => $invoiceDate,
				'status' => $status,
				'total_net' => $invoiceLineTotals[$i]['net_amount'] ?? 0.0000,
				'total_vat' => $invoiceLineTotals[$i]['vat_amount'] ?? 0.0000,
				'total_pp' => $invoiceLineTotals[$i]['pp_amount'] ?? 0.0000,
				'total_gross' => $invoiceLineTotals[$i]['gross_amount'] ?? 0.0000
			]);

		}

		// Remove temp CSV file
		if (file_exists($csvFile)) {
			unlink($csvFile);	
		}

	}

	public function down()
	{
		$this->dropTable('invoice');
	}
	
}