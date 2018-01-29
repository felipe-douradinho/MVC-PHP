<?php

namespace Golden\Exception;

use Throwable;

/**
 * Class FileNotFoundException
 *
 * @package Golden\Exception
 */
class FileNotFoundException extends BaseException
{
	/**
	 * ViewWasNotFound constructor.
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