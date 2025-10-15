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
		$item = new Item();
		$item->name = 'Test Item';
		$item->sku = 'TESTSKU1';
		$item->unit = 'pcs';
		$item->price = 50.00;
		$item->vat_percent = 20.00;
		$item->pp_percent = 5.00;
		$item->created_at = new CDbExpression("NOW()");
		$item->updated_at = new CDbExpression("NOW()");

		$this->assertTrue($item->save(), 'Failed to save Item');
    }

}
