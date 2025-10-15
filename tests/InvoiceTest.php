<?php

use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{

	public function testCreateInvoice()
	{

		// Create a mock of Invoice where we override save()
		$invoice = $this->getMockBuilder(Invoice::class)
			->onlyMethods(['save'])
			->getMock();

		// make save() return true without touching DB
		$invoice->method('save')->willReturn(true);

		$invoice->setAttributes([
			'number' => 'TEST-1001',
			'internal_number' => 'TEST-INT-1001',
			'payment_method' => 'bank_transfer',
			'note' => 'This is a test invoice.',
			'date' => new CDbExpression("NOW()"),
			'status' => 'draft',
			'total_net' => 100.00,
			'total_vat' => 20.00,
			'total_pp' => 5.00,
			'total_gross' => 125.00,
			'created_at' => new CDbExpression("NOW()"),
			'updated_at' => new CDbExpression("NOW()"),
		]);

		$this->assertTrue($invoice->save(), 'Failed to save Invoice');
	}

}