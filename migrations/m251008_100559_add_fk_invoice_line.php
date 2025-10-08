<?php

class m251008_100559_add_fk_invoice_line extends CDbMigration
{
	public function up()
	{
		$this->addForeignKey('fk_invoice_line_invoice_id', 'invoice_line', 'invoice_id', 'invoice', 'id', 'CASCADE', 'RESTRICT');
		$this->addForeignKey('fk_invoice_line_item_id', 'invoice_line', 'item_id', 'item', 'id', 'SET NULL', 'RESTRICT');
	}

	public function down()
	{
		$this->dropForeignKey('fk_invoice_line_item_id', 'invoice_line');
		$this->dropForeignKey('fk_invoice_line_invoice_id', 'invoice_line');
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