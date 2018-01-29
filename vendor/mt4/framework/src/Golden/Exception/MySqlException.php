<?php

namespace Golden\Exception;

use Throwable;

/**
 * Class MySqlException
 *
 * @package Golden\Exception
 */
class MySqlException extends BaseException
{
	/**
	 * MySqlException constructor.
	 *
	 * @param string $message
	 * @param int $code
	 * @param Throwable|null $previous
	 */
	public function __construct( $message = "", $code = 0, Throwable $previous = null )
	{
		die($message);
	}
}