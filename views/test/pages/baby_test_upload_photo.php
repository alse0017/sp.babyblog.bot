<?
return;
$data = [
	'imageurl' => 'http://ernest-opt.ru/sites/default/files/styles/product_full/public/img_5778-2.jpg',
];

$babyblog = \app\components\babyblog\Babyblog::getInstance();

$client = $babyblog->getCurlClient('https://sp.babyblog.ru/org/wizard/urlload');
$client->referer = 'https://sp.babyblog.ru/org/shop/wizard/49925/293795/product';
$client->data = http_build_query($data);

$result = $client->exec();
pre(json_decode($result, true));