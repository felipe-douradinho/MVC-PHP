<?php

namespace Golden\Http;

/**
 * Class Request
 *
 * @package Golden\Request
 */
class Request
{
	const METHOD_GET    = 'GET';
	const METHOD_POST   = 'POST';
	const METHOD_DELETE = 'DELETE';
	const METHOD_PUT    = 'PUT';
	const METHOD_PATCH  = 'PATCH';


	/**
	 * @var string $base_uri
	 */
	private static $base_uri = 'uri';

	/**
	 * @var array $data
	 */
	protected static $data = [ ];


	/**
	 * Capture all requests
	 */
	public static function capture()
	{
		self::setData($_REQUEST);
	}

	/**
	 * @return array
	 */
	public static function segments()
	{
		$segments = explode("/", parse_url(self::getBaseUri(), PHP_URL_PATH));
		return array_filter($segments);
	}

	/**
	 * @param int $position
	 *
	 * @return string
	 */
	public static function segment($position = 1)
	{
		$segments = self::segments();

		return count($segments) > 0 && count($segments) >= ($position-1) ?
			$segments[$position] : '';
	}

	/**
	 * Check if is ajax
	 *
	 * @return bool
	 */
	public static function isAjax()
	{
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
		       && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	/**
	 * Merge data to the request sent
	 *
	 * @param array $data
	 */
	public static function merge(array $data = [ ])
	{
		self::$data = array_merge(self::$data, $data);
	}

	/**
	 * Get requested method
	 *
	 * @return string
	 */
	public static function getMethod()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * @return array
	 */
	public static function all()
	{
		return self::getData();
	}

	/**
	 * @param mixed $key
	 *
	 * @return mixed
	 */
	public static function get($key)
	{
		return self::has($key) ? self::$data[$key] : null;
	}

	/**
	 * @param mixed $key
	 *
	 * @return bool
	 */
	public static function has($key)
	{
		return array_key_exists($key, self::$data);
	}

	/**
	 * @retur array
	 */
	private static function getData()
	{
		return self::$data;
	}

	/**
	 * @param array $data
	 */
	protected static function setData( $data ) {
		self::$data = $data;
	}

	/**
	 * @return string
	 */
	public static function getBaseUri() {
		return isset( $_GET[ self::$base_uri ] ) ? $_GET[ self::$base_uri ] : '';
	}

	/**
	 * @param string $base_uri
	 */
	protected static function setBaseUri( $base_uri ) {
		self::$base_uri = $base_uri;
	}

	public static function getOld( $field )
	{

	}


}