<?php
/**
 * Created by PhpStorm.
 * User: FelipeD
 * Date: 1/30/18
 * Time: 02:48
 */

namespace App\Helpers\Encryption;


/**
 * Class Custom
 *
 * @package App\Helpers\Encryption
 */
class Custom
{
	/**
	 * Encrypt
	 *
	 * @param $string
	 * @param int $key
	 *
	 * @return string
	 */
	public static function encrypt($string, $key = 5)
	{
		$result = '';
		for($i=0, $k= strlen($string); $i<$k; $i++)
		{
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result .= $char;
		}
		return base64_encode($result);
	}

	/**
	 * Decrypt
	 *
	 * @param $string
	 * @param int $key
	 *
	 * @return string
	 */
	public static function decrypt($string, $key = 5)
	{
		$result = '';
		$string = base64_decode($string);
		for($i=0,$k=strlen($string); $i< $k ; $i++)
		{
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
	}

}