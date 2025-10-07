<?php

class m251007_194235_invoice extends CDbMigration
{
	public function up()
	{
		$this->createTable('invoice', array(
			'id' => 'pk',
			'number' => 'varchar(50) NOT NULL UNIQUE',
			'date' => 'date NOT NULL',
			'status' => "enum('draft','closed') NOT NULL DEFAULT 'draft'",
			'total_net' => 'decimal(14,4) DEFAULT 0.0000',
			'total_vat' => 'decimal(14,4) DEFAULT 25.0000',
			'total_pp' => 'decimal(14,4) DEFAULT 3.0000',
			'total_gross' => 'decimal(14,4) DEFAULT 0.0000',
			'created_at' => 'datetime DEFAULT CURRENT_TIMESTAMP',
			'updated_at' => 'datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
	}

	public function down()
	{
		echo "m251007_194235_invoice does not support migration down.\n";
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