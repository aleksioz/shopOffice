<?php
class InvoiceService
{
    /**
     * Zatvori račun: izračunaj totals i postavi status = closed.
     * @param Invoice $invoice
     * @throws Exception
     */
    public static function closeInvoice(Invoice $invoice)
    {
        if($invoice->status === 'closed'){
            throw new Exception('Invoice already closed.');
        }

        $lines = $invoice->lines;
        if(empty($lines)){
            throw new Exception('Invoice has no lines.');
        }

        $totalNet = '0.00';
        $totalVat = '0.00';
        $totalPp  = '0.00';
        $totalGross = '0.00';

        foreach($lines as $line){
            // recalculate (guards against stale values)
            $a = $line->calculateAmounts();
            $totalNet = bcadd($totalNet, $a['line_net'], 2);
            $totalVat = bcadd($totalVat, $a['line_vat'], 2);
            $totalPp  = bcadd($totalPp,  $a['line_pp'], 2);
            $totalGross = bcadd($totalGross, $a['line_gross'], 2);

            // save line values to DB to freeze them (in case they weren't)
            $line->line_net = $a['line_net'];
            $line->line_vat = $a['line_vat'];
            $line->line_pp  = $a['line_pp'];
            $line->line_gross = $a['line_gross'];
            if(!$line->save(false, ['line_net','line_vat','line_pp','line_gross'])){
                throw new Exception('Failed to save invoice line #' . $line->id);
            }
        }

        // save totals on invoice
        $invoice->total_net = $totalNet;
        $invoice->total_vat = $totalVat;
        $invoice->total_pp  = $totalPp;
        $invoice->total_gross = $totalGross;
        $invoice->status = 'closed';

        if(!$invoice->save(false, ['total_net','total_vat','total_pp','total_gross','status'])){
            throw new Exception('Failed to close invoice.');
        }

        return true;
    }
}
