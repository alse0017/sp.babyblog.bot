<?php
/**
 * Created by PhpStorm.
 * User: Alexeenko Sergey Aleksandrovich
 * Phone: +79231421947
 * Email: sergei_alekseenk@list.ru
 * Date: 19.01.2020
 * Time: 11:57
 */

namespace app\components\babyblog;

use app\components\Tools;
use app\models\tables\Option;
use Yii;
use yii\base\Component;

class Babyblog extends Component
{
	const COOKIE_NAME = 'babyblog';

	/** @var static */
	protected static $instance;

	/** @var string */
	public $login;

	/** @var string */
	public $password;

	/**
	 * @return static
	 */
	public static function getInstance()
	{
		if(!static::$instance)
		{
			static::$instance = new static(Option::getBabyblog());
		}
		return static::$instance;
	}

	public function getCookieName()
	{
		return static::COOKIE_NAME.'_'.$this->login;
	}

	public function auth()
	{
		$authData = $this->requestCheckPassAjax();
		if(!empty($authData['auth_code']) && !empty($authData['user_id']))
		{
			if($this->requestAjaxLogin($authData))
			{
				return true;
			}
		}
		return false;
	}

	public function checkAuthByHtml($html)
	{
		return preg_match('~href="/user/logout/?"~i', $html);
	}

	public function checkAuth($url = 'https://sp.babyblog.ru/')
	{
		$client = $this->getCurlClient($url);
		return $this->checkAuthByHtml($client->exec());
	}

	public function getCurlClient($url)
	{
		$client = new \app\components\CurlClient($url);
		$client->referer = 'https://sp.babyblog.ru/';
		$client->cookieName = $this->getCookieName();

		return $client;
	}

	/**
	 * @param array $checkPassData
	 * @return bool
	 */
	public function requestAjaxLogin($checkPassData)
	{
		$curlClient = $this->getCurlClient('https://sp.babyblog.ru/user/ajax_login');
		$curlClient->data = $checkPassData;
		$curlClient->exec();
		return $curlClient->getResultHttpCode() == 200;
	}

	/**
	 * @return array
	 */
	public function requestCheckPassAjax()
	{
		$get = [
			'redirect_uri' => 'https://sp.babyblog.ru/user/callback',
			'login'        => $this->login,
			'password'     => $this->password,
			'callback'     => 'BBJSONP_'.Tools::randString(16),
		];

		$curlClient = $this->getCurlClient('https://sso.babyblog.ru/ssoapi/user/check-pass-ajax?'.http_build_query($get));

		$checkPassData = $curlClient->exec();

		if($curlClient->getResultHttpCode() == 200)
		{
			preg_match('~'.$get['callback'].'\((.+)\)~is', $checkPassData, $checkPassData);
			$checkPassData = (array)json_decode($checkPassData[1], true);
			return $checkPassData;
		}
		return NULL;
	}
}