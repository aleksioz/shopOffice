<?php

use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{


	protected function setUp(): void
	{
		parent::setUp();
		
		$db = Yii::app()->db;

		$db->createCommand("
			CREATE TABLE IF NOT EXISTS item (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				name VARCHAR(255) NOT NULL,
				sku VARCHAR(100) UNIQUE NOT NULL,
				unit VARCHAR(50) NOT NULL,
				price DECIMAL(10,2) NOT NULL,
				vat_percent DECIMAL(5,2) DEFAULT 0,
				pp_percent DECIMAL(5,2) DEFAULT 0,
				created_at DATETIME,
				updated_at DATETIME
			)
		")->execute();
	}


    public $fixtures = ['items' => 'Item'];

    public function testCreateItem()
    {

		// Create a new Item 
		$item = new Item();
		$item->name = 'Test Item';
		$item->sku = 'TESTSKU1';
		$item->unit = 'pcs';
		$item->price = 50.00;
		$item->vat_percent = 20.00;
		$item->pp_percent = 5.00;
		$item->created_at = new CDbExpression("datetime('now')");
		$item->updated_at = new CDbExpression("datetime('now')");

		$this->assertTrue($item->save(), 'Failed to save Item');
    }


	protected function tearDown(): void
	{
		Yii::app()->db
        ->createCommand("DROP TABLE IF EXISTS item")
        ->execute();

    	parent::tearDown();
	}

}
