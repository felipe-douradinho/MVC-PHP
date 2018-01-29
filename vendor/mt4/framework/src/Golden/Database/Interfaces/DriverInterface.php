<?php

namespace Golden\Database\Interfaces;

/**
 * Interface DriverInterface
 *
 * @package Golden\Database\Interfaces
 */
interface DriverInterface
{

	/**
	 * @return string
	 */
	public function getDsn();

	/**
	 * @return void
	 */
	public function setDsn();

	/**
	 * @return string
	 */
	public function getDatabase();

	/**
	 * @param string $database
	 */
	public function setDatabase( $database );

	/**
	 * @return string
	 */
	public function getUsername();

	/**
	 * @param string $username
	 */
	public function setUsername( $username );

	/**
	 * @return string
	 */
	public function getPassword();

	/**
	 * @param string $password
	 */
	public function setPassword( $password );

	/**
	 * @return string
	 */
	public function getHost();

	/**
	 * @param string $host
	 */
	public function setHost( $host );

	/**
	 * @return int
	 */
	public function getPort();

	/**
	 * @param int $port
	 */
	public function setPort( $port );

}