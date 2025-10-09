<?php
// Path to Yii framework
$yii = '/var/www/html/yii/framework/yii.php';

// Path to your app config
$config = dirname(__FILE__) . '/../../../config/main.php';

// --------------------------------------------------
// bootstrap.php — for modern PHPUnit + Yii 1.1
// --------------------------------------------------

require_once($yii);

Yii::createConsoleApplication($config);

// 5. (Optional) load your module explicitly
Yii::app()->getModule('shopOffice');

// 6. Optional performance tweaks
Yii::app()->setComponent('log', null);

// Done — no old PHPUnit integration, no Yii test classes