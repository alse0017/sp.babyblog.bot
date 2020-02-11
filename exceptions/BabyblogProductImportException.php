<?php
/**
 * Created by PhpStorm.
 * User: Alexeenko Sergey Aleksandrovich
 * Phone: +79231421947
 * Email: sergei_alekseenk@list.ru
 * Date: 20.01.2020
 * Time: 21:48
 */

namespace app\exceptions;

use yii\base\Exception;

class BabyblogProductImportException extends Exception
{
	/**
	 * @return string the user-friendly name of this exception
	 */
	public function getName()
	{
		return 'Babyblog Product Import Exception';
	}
}