<?
//anishincko@yandex.ru
//&%DOF*YGIBuonp

$babyblog = \app\components\babyblog\Babyblog::getInstance();
$html = $babyblog->getCurlClient('https://sp.babyblog.ru/')->exec();

if(!$babyblog->checkAuthByHtml($html))
{
	if($babyblog->auth())
	{
		$html = $babyblog->getCurlClient('https://sp.babyblog.ru/')->exec();
	}
}

echo $html;

//echo (new \app\components\CurlClient('https://sp.babyblog.ru/', ['cookieName' => 'babyblog']))->exec();