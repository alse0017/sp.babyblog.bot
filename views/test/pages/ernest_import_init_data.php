<?die();
$str = 'http://ernest-opt.ru/catalog/35	Женские визитницы	49
http://ernest-opt.ru/catalog/113	Женские зонты	52
http://ernest-opt.ru/catalog/36	Женские ключницы	49
http://ernest-opt.ru/catalog/37	Женские косметички	49
http://ernest-opt.ru/catalog/30	Женские кошельки	257
http://ernest-opt.ru/catalog/32	Женские кошельки	257
http://ernest-opt.ru/catalog/28	Женские кошельки	257
http://ernest-opt.ru/catalog/29	Женские кошельки	257
http://ernest-opt.ru/catalog/31	Женские кошельки	257
http://ernest-opt.ru/catalog/34	Женские обложки на документы	49
http://ernest-opt.ru/catalog/33	Женские обложки на документы	49
http://ernest-opt.ru/catalog/116	Женские обложки на документы	49
http://ernest-opt.ru/catalog/119	Женские рюкзаки	212
http://ernest-opt.ru/catalog/2	Женские сумки - кожаные	212
http://ernest-opt.ru/catalog/3	Женские сумки - кожзаменитель	212
http://ernest-opt.ru/catalog/9	Мужские барсетки - кожанные	317
http://ernest-opt.ru/catalog/11	Мужские барсетки - кожанные	317
http://ernest-opt.ru/catalog/16	Мужские барсетки - кожзаменитель	317
http://ernest-opt.ru/catalog/18	Мужские барсетки - кожзаменитель	317
http://ernest-opt.ru/catalog/47	Мужские визитницы	89
http://ernest-opt.ru/catalog/112	Мужские зонты	92
http://ernest-opt.ru/catalog/48	Мужские ключницы	89
http://ernest-opt.ru/catalog/41	Мужские кошельки	260
http://ernest-opt.ru/catalog/42	Мужские кошельки	260
http://ernest-opt.ru/catalog/43	Мужские кошельки	260
http://ernest-opt.ru/catalog/39	Мужские кошельки	260
http://ernest-opt.ru/catalog/46	Мужские обложки на документы	89
http://ernest-opt.ru/catalog/45	Мужские обложки на документы	89
http://ernest-opt.ru/catalog/44	Мужские обложки на документы	89
http://ernest-opt.ru/catalog/10	Мужские папки - кожанные	317
http://ernest-opt.ru/catalog/17	Мужские папки - кожзаменитель	317
http://ernest-opt.ru/catalog/6	Мужские портфели - кожаные	317
http://ernest-opt.ru/catalog/7	Мужские портфели - кожаные	317
http://ernest-opt.ru/catalog/13	Мужские портфели - кожзаменитель	317
http://ernest-opt.ru/catalog/14	Мужские портфели - кожзаменитель	317
http://ernest-opt.ru/catalog/120	Мужские рюкзаки	317
http://ernest-opt.ru/catalog/8	Мужские сумки - кожаные	317
http://ernest-opt.ru/catalog/15	Мужские сумки - кожзаменитель	317
http://ernest-opt.ru/catalog/23	ж|м сумки дорожные - кожаные	212|317
http://ernest-opt.ru/catalog/24	ж|м сумки дорожные - кожзаменитель	212|317';

$data = ['category_links' => []];
foreach(explode("\n", trim($str)) as $line)
{
	$item = [];
	list($item['url'], $item['name'], $item['babyblog_id']) = explode("	", trim($line));
	$data['category_links'][] = $item;
}

$supplier = \app\models\tables\Supplier::findOne(1);
$supplier->import_init_data = $data;
$supplier->import_data = $data;
var_dump($supplier->save());