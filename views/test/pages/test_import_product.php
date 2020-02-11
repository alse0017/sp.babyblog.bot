<?
$product = \app\models\tables\BabyblogProduct::findOne(4);

$product->import();

pre($product->toArray());