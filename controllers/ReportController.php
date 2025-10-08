<?php
class ReportController extends Controller
{
    /**
     * actionSalesReport: prima date_from i date_to (YYYY-MM-DD)
     */
    public function actionSalesReport()
    {
        $from = Yii::app()->request->getParam('date_from');
        $to = Yii::app()->request->getParam('date_to');

        if(!$from) $from = date('Y-01-01'); // default pocetak godine
        if(!$to) $to = date('Y-m-d');

        $sql = "SELECT SUM(i.total_net) as total_net, SUM(i.total_vat) as total_vat, SUM(i.total_pp) as total_pp, SUM(i.total_gross) as total_gross
                FROM invoice i
                WHERE i.status = 'closed' AND i.date BETWEEN :from AND :to";

        $row = Yii::app()->db->createCommand($sql)->queryRow(true, [':from'=>$from, ':to'=>$to]);

        $this->render('salesReport', [
            'from'=>$from,'to'=>$to,'totals'=>$row
        ]);
    }
}
