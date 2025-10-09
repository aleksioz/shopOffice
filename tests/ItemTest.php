<?php

use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public $fixtures = ['items' => 'Item'];

    public function testCreateItem()
    {
		$item = new Item();
		$item->name = 'Test Item';
		$item->sku = 'TESTSKU';
		$item->unit = 'pcs';
		$item->price = 50.00;
		$item->vat_percent = 20.00;
		$item->pp_percent = 5.00;
		$item->created_at = new CDbExpression('NOW()');
		$item->updated_at = new CDbExpression('NOW()');

		$this->assertTrue($item->save(), 'Failed to save Item');
    }

}
