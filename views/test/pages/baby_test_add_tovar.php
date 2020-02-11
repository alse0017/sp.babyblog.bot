<?
return;

$data = [
	'shop_id'      => 49925,
	'buyout_id'    => 293795,
	'product_id'   => $productId,
	'name'         => 'Сумка женская David Jones',
	'article'      => '777777',
	'price'        => '990',
	'category_id'  => 212,
	'description'  => '<p>Материал верх/подклад: ПВХ / текстиль</p><p>Пол: Женщинам</p><p>Габариты (д/ш/в):24 х 8 х 16</p>',
	'field'        => [
		1 => [
			'df787b',
		],
	],
	'microtime'    => microtime(true),
	'photos'       => [
		'https://cdn2.imgbb.ru/sp/user/330/3309935/202001/73f15e2db48476fc961aacf6fc6af278.jpg',
		'https://cdn2.imgbb.ru/sp/user/330/3309935/202001/fc495d7b3fd3f28e367bfe04e3c22a62.jpg',
		'https://cdn5.imgbb.ru/sp/user/330/3309935/202001/45a0f9c2ccbe2ccc82eaddaaf2bc5266.jpg',
	],
	'photo_width'  => [
		666,
		666,
		666,
	],
	'photo_height' => [
		1000,
		1000,
		1000,
	],
	'main_photo'   => 'https://cdn2.imgbb.ru/sp/user/330/3309935/202001/73f15e2db48476fc961aacf6fc6af278.jpg',
];

$babyblog = \app\components\babyblog\Babyblog::getInstance();

$client = $babyblog->getCurlClient('https://sp.babyblog.ru/org/wizard/ajax_set_product');
$client->referer = 'https://sp.babyblog.ru/org/shop/wizard/49925/293795/product'.($productId ? '/'.$productId:'');
$client->data = http_build_query($data);

$result = $client->exec();
var_dump(json_decode($result, true));