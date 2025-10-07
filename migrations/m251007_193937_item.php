<?php

class m251007_193937_item extends CDbMigration
{
	public function up()
	{
		$this->createTable('item', array(
			'id' => 'pk',
			'name' => 'string NOT NULL',
			'sku' => 'string(100) DEFAULT NULL',
			'unit' => 'string(50) NOT NULL',
			'price' => 'decimal(12,4) NOT NULL DEFAULT 0.0000',
			'vat_percent' => 'decimal(5,4) NOT NULL DEFAULT 25.0000',
			'pp_percent' => 'decimal(5,4) NOT NULL DEFAULT 3.0000',
			'created_at' => 'datetime DEFAULT CURRENT_TIMESTAMP',
			'updated_at' => 'datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
	}

	public function down()
	{
		echo "m251007_193937_item does not support migration down.\n";
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