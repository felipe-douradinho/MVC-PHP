<?php

namespace Golden\Database\Drivers;

use Golden\Database\Interfaces\DriverInterface;
use Golden\Database\PdoConnection;


/**
 * Class MySql
 *
 * @package Golden\Database\Drivers
 */
class MySql extends PdoConnection implements DriverInterface
{

	/**
	 * @var string $host
	 */
	private $host;

	/**
	 * @var string $username
	 */
	private $username;

	/**
	 * @var string $password
	 */
	private $password;

	/**
	 * @var string $database
	 */
	private $database;

	/**
	 * @var int $port
	 */
	private $port;

	/**
	 * @var string $dsn
	 */
	private $dsn = 'mysql:host={host};port=3306;dbname={database};';


	/**
	 * MySql constructor.
	 *
	 * @param string $host
	 * @param int $port
	 * @param string $database
	 * @param string $username
	 * @param string $password
	 */
	public function __construct( $host, $port = 3306, $database, $username, $password )
	{
		// -- set username
		$this->setUsername($username);

		// -- set password
		$this->setPassword($password);

		// -- set database
		$this->setDatabase($database);

		// -- set Dsn
		$this->setDsn();

		// -- set father
		parent::__construct( $this->getDSN(), $username, $password, [ ] );
	}

	/**
	 * @return string
	 */
	public function getDsn()
	{
		return $this->dsn;
	}

	/**
	 * @return void
	 */
	public function setDsn()
	{
		$this->dsn = str_replace( '{host}', $this->getHost(), $this->dsn);
		$this->dsn = str_replace( '{database}', $this->getDatabase(), $this->dsn);
		$this->dsn = str_replace( '{port}', $this->getPort(), $this->dsn);
	}

	/**
	 * @return string
	 */
	public function getDatabase()
	{
		return $this->database;
	}

	/**
	 * @param string $database
	 */
	public function setDatabase( $database )
	{
		$this->database = $database;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername( $username )
	{
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword( $password )
	{
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @param string $host
	 */
	public function setHost( $host )
	{
		$this->host = $host;
	}

	/**
	 * @return int
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * @param int $port
	 */
	public function setPort( $port )
	{
		$this->port = $port;
	}
}