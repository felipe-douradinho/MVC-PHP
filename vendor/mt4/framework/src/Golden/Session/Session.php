<?php

namespace Golden\Session;

use Golden\Foundation\Application;

/**
 * Class Session
 * @package Golden\Session
 */
class Session
{

	/**
	 * Lets begin
	 *
	 * @param $app_base_path
	 */
	public static function start($app_base_path = null)
	{
		@session_start();
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
	 * @param bool $preserve_key
	 *
	 * @return string|array
	 */
	public static function get($key, $preserve_key = false)
	{
		if(self::has($key))
		{
			if($preserve_key)
				$value[$key] = $_SESSION['data'][$key];
			else
				$value = $_SESSION['data'][$key];

			return $value;
		}

		return '';
	}

	/**
	 * @param string $key
	 */
	public static function destroy($key)
	{
		if(!isset($_SESSION))
			self::start();

		if(self::has($key))
			unset($_SESSION['data'][$key]);
	}

	/**
	 * @param string $key
	 *
	 * @param bool $preserve_key
	 *
	 * @return string|array
	 */
	public static function pull($key, $preserve_key = false)
	{
		if(!isset($_SESSION))
			self::start();

		if(self::has($key))
		{
			if($preserve_key)
				$value[$key] = $_SESSION['data'][$key];
			else
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
		return isset($_SESSION) && array_key_exists('data', $_SESSION) && array_key_exists($key, $_SESSION['data']);
	}

	/**
	 * Flush
	 *
	 * @return Session
	 */
	public static function flushErrors()
	{
		self::pull('errors');
		return (new static());
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