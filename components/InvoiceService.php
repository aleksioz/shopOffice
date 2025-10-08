<?php
class InvoiceService
{
    /**
     * Zatvori račun, postavi status = closed.
     * @param Invoice $invoice
     * @throws Exception
     */
    public static function closeInvoice(Invoice $invoice)
    {
        if($invoice->status === 'closed'){
            throw new Exception('Invoice already closed.');
        }

        $invoice->status = 'closed';

        if(!$invoice->save(false, ['status'])){
            throw new Exception('Failed to close invoice.');
        }

        return true;
    }
}
