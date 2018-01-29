<?php

namespace Golden\Session;

/**
 * Class Session
 * @package Golden\Session
 */
class Session
{

	/**
	 * Lets begin
	 */
	public static function start()
	{
		@session_start();
		$_SESSION['data'] = [ ];
	}

	/**
	 * @param array $data
	 */
	public static function set(array $data = [ ])
	{
		$_SESSION['data'] = $data;
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 */
	public static function pull($key)
	{
		if(self::has($key))
		{
			$value = $_SESSION['data'][$key];
			unset($_SESSION['data'][$key]);

			return $value;
		}

		return '';
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public static function has($key)
	{
		return array_key_exists('data', $_SESSION) && array_key_exists($key, $_SESSION['data']);
	}

	/**
	 * Flush
	 *
	 * @return Session
	 */
	public static function flush()
	{
		if(isset($_SESSION['data']))
			unset($_SESSION['data']);

		return (new static());
	}

}