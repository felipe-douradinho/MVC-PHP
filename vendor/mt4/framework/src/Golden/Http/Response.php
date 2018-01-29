<?php

namespace Golden\Http;

/**
 * Class Response
 *
 * @package Golden\Http
 */
class Response
{
	const HTTP_CONTINUE = 100;
	const HTTP_SWITCHING_PROTOCOLS = 101;
	const HTTP_PROCESSING = 102;            // RFC2518
	const HTTP_OK = 200;
	const HTTP_CREATED = 201;
	const HTTP_ACCEPTED = 202;
	const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
	const HTTP_NO_CONTENT = 204;
	const HTTP_RESET_CONTENT = 205;
	const HTTP_PARTIAL_CONTENT = 206;
	const HTTP_MULTI_STATUS = 207;          // RFC4918
	const HTTP_ALREADY_REPORTED = 208;      // RFC5842
	const HTTP_IM_USED = 226;               // RFC3229
	const HTTP_MULTIPLE_CHOICES = 300;
	const HTTP_MOVED_PERMANENTLY = 301;
	const HTTP_FOUND = 302;
	const HTTP_SEE_OTHER = 303;
	const HTTP_NOT_MODIFIED = 304;
	const HTTP_USE_PROXY = 305;
	const HTTP_RESERVED = 306;
	const HTTP_TEMPORARY_REDIRECT = 307;
	const HTTP_PERMANENTLY_REDIRECT = 308;  // RFC7238
	const HTTP_BAD_REQUEST = 400;
	const HTTP_UNAUTHORIZED = 401;
	const HTTP_PAYMENT_REQUIRED = 402;
	const HTTP_FORBIDDEN = 403;
	const HTTP_NOT_FOUND = 404;
	const HTTP_METHOD_NOT_ALLOWED = 405;
	const HTTP_NOT_ACCEPTABLE = 406;
	const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
	const HTTP_REQUEST_TIMEOUT = 408;
	const HTTP_CONFLICT = 409;
	const HTTP_GONE = 410;
	const HTTP_LENGTH_REQUIRED = 411;
	const HTTP_PRECONDITION_FAILED = 412;
	const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
	const HTTP_REQUEST_URI_TOO_LONG = 414;
	const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
	const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
	const HTTP_EXPECTATION_FAILED = 417;
	const HTTP_I_AM_A_TEAPOT = 418;                                               // RFC2324
	const HTTP_MISDIRECTED_REQUEST = 421;                                         // RFC7540
	const HTTP_UNPROCESSABLE_ENTITY = 422;                                        // RFC4918
	const HTTP_LOCKED = 423;                                                      // RFC4918
	const HTTP_FAILED_DEPENDENCY = 424;                                           // RFC4918
	const HTTP_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL = 425;   // RFC2817
	const HTTP_UPGRADE_REQUIRED = 426;                                            // RFC2817
	const HTTP_PRECONDITION_REQUIRED = 428;                                       // RFC6585
	const HTTP_TOO_MANY_REQUESTS = 429;                                           // RFC6585
	const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;                             // RFC6585
	const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;
	const HTTP_INTERNAL_SERVER_ERROR = 500;
	const HTTP_NOT_IMPLEMENTED = 501;
	const HTTP_BAD_GATEWAY = 502;
	const HTTP_SERVICE_UNAVAILABLE = 503;
	const HTTP_GATEWAY_TIMEOUT = 504;
	const HTTP_VERSION_NOT_SUPPORTED = 505;
	const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;                        // RFC2295
	const HTTP_INSUFFICIENT_STORAGE = 507;                                        // RFC4918
	const HTTP_LOOP_DETECTED = 508;                                               // RFC5842
	const HTTP_NOT_EXTENDED = 510;                                                // RFC2774
	const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;                             // RFC6585

	/**
	 * @var array
	 */
	protected $headers = [ ];

	/**
	 * @var int
	 */
	protected $http_code = self::HTTP_OK;

	/**
	 * @var string
	 */
	protected $content = '';


	/**
	 * Response constructor.
	 *
	 * @param string $content
	 * @param int $status
	 * @param array $headers
	 *
	 * @throws \Exception
	 */
	public function __construct( $content = '', $status = 200, array $headers = [ ] )
	{
		$this->setContent($content);
		$this->setHttpCode($status);
		$this->setHeaders($headers);
	}

	/**
	 * Response __clone
	 */
	private function __clone() { }

	/**
	 * Response __wakeup
	 */
	private function __wakeup() { }


	/**
	 * Factory method for chainability.
	 *
	 * Example:
	 *
	 *     return Response::create($body, 200);
	 *
	 * @param mixed $content The response content, see setContent()
	 * @param int   $status  The response status code
	 * @param array $headers An array of response headers
	 *
	 * @return static
	 */
	public static function create($content = '', $status = 200, $headers = array())
	{
		return new static($content, $status, $headers);
	}

	/**
	 * Returns the Response as an HTTP string.
	 *
	 * @return string The Response as an HTTP string
	 */
	public function __toString()
	{
		return $this->getContent();
	}

	/**
	 * Sends HTTP headers and content.
	 *
	 * @return Response
	 */
	public function send()
	{
		$this->sendHeaders();
		$this->sendContent();

		return $this;
	}

	public function setAjax()
	{
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
		return $this;
	}

	/**
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * @return Response
	 */
	protected function sendHeaders()
	{
		if (headers_sent())
			return $this;

		// -- set header ajax
		if(Request::isAjax())
		{
			$this->setHeader( 'Content-type', 'application/json' ); // -- set header ajax
		}
		else
		{
			$this->setHeader( 'Content-type', 'text/html' );  // -- set header html
		}

		// for each header
		foreach ( $this->getHeaders() as $key => $value )
			@header("{$key}: {$value}");

		// -- set http code
		http_response_code( $this->getHttpCode() );

		return $this;
	}

	/**
	 * Sends content for the current web response.
	 *
	 * @return Response
	 */
	public function sendContent()
	{
		echo $this->getContent();

		return $this;
	}

	/**
	 * @param string $key
	 * @param string $value
	 */
	public function setHeader($key, $value)
	{
		$this->headers[$key] = $value;
	}

	/**
	 * @param array $headers
	 */
	protected function setHeaders( array $headers = [ ] )
	{
		$this->headers = array_merge( $this->headers, $headers );
	}

	/**
	 * A link to setHeaders() method
	 *
	 * @param array $headers
	 *
	 * @return Response
	 */
	public function withHeaders(array $headers = [ ])
	{
		$this->setHeaders($headers);
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHttpCode() {
		return $this->http_code;
	}

	/**
	 * @param int $http_code
	 */
	public function setHttpCode( $http_code ) {
		$this->http_code = $http_code;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param mixed $content
	 *
	 * @return Response
	 * @throws \Exception
	 */
	public function setContent( $content )
	{
		$this->content = (string) ( is_array( $content ) ? json_encode($content) : $content );
		return $this;
	}

}