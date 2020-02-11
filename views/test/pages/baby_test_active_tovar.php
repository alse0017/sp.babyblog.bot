<?
/*$product = \app\models\tables\BabyblogProduct::findOne(3);
//$product->importDeactivate();
$product->importActivate();*/

$supplier = \app\models\tables\Supplier::findOne(1);
$supplier->activateBabyblogProducts();