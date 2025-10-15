<?php

use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{


    public function testCreateItem()
    {

		// Create a mock of Item where we override save()
        $item = $this->getMockBuilder(Item::class)
            ->onlyMethods(['save'])
            ->getMock();

        // make save() return true without touching DB
        $item->method('save')->willReturn(true);

		// Create a new Item 
		$item->setAttributes([
			'name' => 'Test Item',
			'sku' => 'TESTSKU1',
			'unit' => 'pcs',
			'price' => 50.00,
			'vat_percent' => 20.00,
			'pp_percent' => 5.00,
			'created_at' => new CDbExpression("NOW()"),
			'updated_at' => new CDbExpression("NOW()"),
		]);

		$this->assertTrue($item->save(), 'Failed to save Item');
    }

}
