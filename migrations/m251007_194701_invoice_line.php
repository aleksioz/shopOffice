<?php

class m251007_194701_invoice_line extends CDbMigration
{
	public function up()
	{
		$this->createTable('invoice_line', array(
			'id' => 'pk',
			'invoice_id' => 'int NOT NULL',
			'item_id' => 'int NULL',
			'quantity' => 'decimal(12,3) NOT NULL DEFAULT 1.000',
			'line_net' => 'decimal(14,4) DEFAULT 0.0000',
			'line_vat' => 'decimal(14,4) DEFAULT 0.0000',
			'line_pp' => 'decimal(14,4) DEFAULT 0.0000',
			'line_gross' => 'decimal(14,4) DEFAULT 0.0000',
			'created_at' => 'datetime DEFAULT CURRENT_TIMESTAMP',
			'updated_at' => 'datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

		$this->addForeignKey('fk_invoice_line_invoice_id', 'invoice_line', 'invoice_id', 'invoice', 'id', 'CASCADE', 'RESTRICT');
		$this->addForeignKey('fk_invoice_line_item_id', 'invoice_line', 'item_id', 'item', 'id', 'SET NULL', 'RESTRICT');
	}

	public function down()
	{
		echo "m251007_194701_invoice_line does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}