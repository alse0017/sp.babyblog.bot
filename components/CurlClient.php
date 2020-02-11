<?php
/**
 * Created by PhpStorm.
 * User: Alexeenko Sergey Aleksandrovich
 * Phone: +79231421947
 * Email: sergei_alekseenk@list.ru
 * Date: 19.01.2020
 * Time: 9:39
 */

namespace app\components;

use yii\base\Component;
use yii\base\Exception;

class CurlClient extends Component
{
	/** @var string */
	public $url;

	/** @var string */
	public $referer;

	/** @var string */
	public $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36';

	/** @var array */
	public $header = [];

	/** @var array|string */
	public $data;

	/** @var string */
	public $cookieName;

	/** @var resource */
	protected $ch;

	/** @var string */
	protected $resultHeader;

	/** @var string */
	protected $resultData;

	/** @var string */
	protected $resultHttpCode;

	public function __construct($url, array $config = [])
	{
		$config['url'] = $url;
		parent::__construct($config);
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function exec()
	{
		$this->curlInit();

		$result = curl_exec($this->ch);
		$error = curl_error($this->ch);

		if(!$error)
		{
			$headerSize = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
			$this->resultHeader = mb_substr($result, 0, $headerSize - 4);
			$this->resultData = mb_substr($result, $headerSize);
			$this->resultHttpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

			if (stristr($this->resultHeader,": gzip"))
			{
				$this->resultData = gzinflate(mb_substr($this->resultData, 10));
			}
		}
		else
		{
			throw new Exception($error);
		}

		curl_close($this->ch);

		return $this->resultData;
	}

	protected function curlInit()
	{
		$urlInfo = parse_url($this->url);

		$curlOpts = array(
			CURLOPT_HEADER => true,
			CURLOPT_CONNECTTIMEOUT => 30,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_REFERER => $this->referer,
			CURLOPT_USERAGENT => $this->userAgent,
			CURLOPT_HTTPHEADER => $this->header,

			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS => 10,
		);

		if($cookiePath = $this->getCookiePath())
		{
			$curlOpts[CURLOPT_COOKIEFILE] = $cookiePath;
			$curlOpts[CURLOPT_COOKIEJAR] = $cookiePath;
		}

		if($urlInfo['scheme'] == 'https')
		{
			$curlOpts[CURLOPT_SSL_VERIFYPEER] = false;
			$curlOpts[CURLOPT_SSL_VERIFYHOST] = false;
		}

		if ($this->data)
		{
			$curlOpts[CURLOPT_POST] = true;
			$curlOpts[CURLOPT_POSTFIELDS] = $this->data;
		}

		$this->ch = curl_init($this->url);
		curl_setopt_array($this->ch, $curlOpts);
	}

	protected function getCookiePath()
	{
		if(!$this->cookieName) return NULL;

		$cookieDir = \Yii::getAlias('@app/runtime/curl_client_cookies');
		$cookiePath = \Yii::getAlias($cookieDir.'/'.$this->cookieName.'.txt');
		if(!is_dir($cookieDir))
		{
			mkdir($cookieDir, 0777, true);
		}
		return $cookiePath;
	}

	public function getResultHeader()
	{
		return $this->resultHeader;
	}

	public function getResultData()
	{
		return $this->resultData;
	}

	public function getResultHttpCode()
	{
		return $this->resultHttpCode;
	}
}