<?php
/**
 * Created by PhpStorm.
 * User: FelipeD
 * Date: 1/30/18
 * Time: 02:48
 */

namespace App\Helpers\Encryption;


/**
 * Class CaesarCipher
 *
 * @package Modules\Admin\Helpers\ImporterHelper
 */
class CaesarCipher
{

	/**
	 * @param string $text
	 * @param int $key
	 *
	 * @return string
	 */
	public static function cryptography($text, $key = 3)
	{
		if (!ctype_alpha($text))
			return $text;

		$offset = ord(ctype_upper($text) ? 'A' : 'a');
		return chr(fmod(((ord($text) + $key) - $offset), 26) + $offset);
	}

	/**
	 * @param string $text
	 * @param int $key
	 *
	 * @return string
	 */
	public static function encrypt( $text, $key = 3 )
	{
		$output = "";

		$inputArr = str_split($text);
		foreach ($inputArr as $ch)
			$output .= self::cryptography($ch, $key);

		return $output;
	}

	/**
	 * @param string $text
	 * @param int $key
	 *
	 * @return string
	 */
	public static function decrypt($text, $key = 3)
	{
		return self::encrypt($text, 26 - $key);
	}
}