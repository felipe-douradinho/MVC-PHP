<?php

namespace Golden\Exception;

use Throwable;

/**
 * Class ViewWasNotFound
 *
 * @package Golden\Exception
 */
class ViewWasNotFound extends BaseException
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