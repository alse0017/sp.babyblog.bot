<?php
/**
 * Created by PhpStorm.
 * User: Alexeenko Sergey Aleksandrovich
 * Phone: +79231421947
 * Email: sergei_alekseenk@list.ru
 * Date: 19.01.2020
 * Time: 12:03
 */

namespace app\components;

class Tools
{
	/**
	 * @param int $len
	 * @param string|array $passChars
	 * @return string
	 */
	public static function randString($len = 10, $passChars = NULL)
	{
		static $allchars = "abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ0123456789";
		$string = "";
		if(is_array($passChars))
		{
			while(strlen($string) < $len)
			{
				if(function_exists('shuffle'))
				{
					shuffle($passChars);
				}
				foreach($passChars as $chars)
				{
					$n = strlen($chars) - 1;
					$string .= $chars[mt_rand(0, $n)];
				}
			}
			if(strlen($string) > count($passChars))
			{
				$string = substr($string, 0, $len);
			}
		}
		else
		{
			if($passChars !== NULL)
			{
				$chars = $passChars;
				$n = strlen($passChars) - 1;
			}
			else
			{
				$chars = $allchars;
				$n = 61; //strlen($allchars)-1;
			}
			for($i = 0; $i < $len; $i++)
			{
				$string .= $chars[mt_rand(0, $n)];
			}
		}
		return $string;
	}
}