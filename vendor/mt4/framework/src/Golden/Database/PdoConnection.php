<?php

namespace Golden\Database;

use PDO;
use PDOException;
use PDOStatement;

/**
 * Class PdoConnection
 *
 * @package Golden\Database
 */
class PdoConnection
{

	/**
	 * @var PDO $instance
	 */
	static private $instance;

	/**
	 * Creates a PDO instance representing a connection to a database and makes the instance available as a singleton
	 *
	 * @param string $dsn The full DSN, eg: mysql:host=localhost;dbname=testdb
	 * @param string $username The user name for the DSN string. This parameter is optional for some PDO drivers.
	 * @param string $password The password for the DSN string. This parameter is optional for some PDO drivers.
	 * @param array $driver_options A key=>value array of driver-specific connection options
	 *
	 * @return PDO
	 */
	public function __construct($dsn, $username, $password, $driver_options)
	{
		if(!self::getInstance())
			self::setInstance(new PDO($dsn, $username, $password, $driver_options));

		return self::getInstance();
	}

	/**
	 * @return PDO|false
	 */
	public static function getInstance()
	{
		return self::$instance;
	}

	/**
	 * @param PDO $instance
	 */
	public static function setInstance( PDO $instance ) {
		self::$instance = $instance;
	}

	/**
	 * Initiates a transaction
	 *
	 * @return bool
	 */
	public static function beginTransaction()
	{
		return self::getInstance()->beginTransaction();
	}

	/**
	 * Commits a transaction
	 *
	 * @return bool
	 */
	public static function commit()
	{
		return self::getInstance()->commit();
	}

	/**
	 * Fetch the SQLSTATE associated with the last operation on the database handle
	 *
	 * @return string
	 */
	public static function errorCode()
	{
		return self::getInstance()->errorCode();
	}

	/**
	 * Fetch extended error information associated with the last operation on the database handle
	 *
	 * @return array
	 */
	public static function errorInfo()
	{
		return self::getInstance()->errorInfo();
	}

	/**
	 * Execute an SQL statement and return the number of affected rows
	 *
	 * @param string $statement
	 *
	 * @return int
	 */
	public static function exec($statement)
	{
		return self::getInstance()->exec($statement);
	}

	/**
	 * Retrieve a database connection attribute
	 *
	 * @param int $attribute
	 * @return mixed
	 */
	public static function getAttribute($attribute)
	{
		return self::getInstance()->getAttribute($attribute);
	}

	/**
	 * Return an array of available PDO drivers
	 *
	 * @return array
	 */
	public static function getAvailableDrivers()
	{
		return self::getInstance()->getAvailableDrivers();
	}

	/**
	 * Returns the ID of the last inserted row or sequence value
	 *
	 * @param string $name Name of the sequence object from which the ID should be returned.
	 * @return string
	 */
	public static function lastInsertId($name)
	{
		return self::getInstance()->lastInsertId($name);
	}

	/**
	 * Prepares a statement for execution and returns a statement object
	 *
	 * @param string $statement A valid SQL statement for the target database server
	 * @param array $params Array of one or more key=>value pairs to set attribute values for the PDOStatement obj
	returned  
	 * @return PDOStatement
	 */
	public static function prepare($statement, array $params = [ ])
	{
		$query = self::getInstance()->prepare($statement, $params);

		foreach ( $params as $key => $value)
		{
			$key++;
			$query->bindValue(":v{$key}", $value);
		}

		$query->execute();
		return $query;
	}

	/**
	 * Executes an SQL statement, returning a result set as a PDOStatement object
	 *
	 * @param string $statement
	 * @return PDOStatement
	 */
	public static function query($statement)
	{
		return self::getInstance()->query($statement);
	}

	/**
	 * Execute query and return all rows in assoc array
	 *
	 * @param string $statement
	 * @param array $params
	 *
	 * @return array
	 */
	public static function get($statement, array $params = [])
	{
		$query = self::prepare($statement, $params);
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Get the first
	 *
	 * @param string $statement
	 * @param array $params
	 *
	 * @return array
	 */
	public static function first($statement, array $params = [])
	{
		$query = self::prepare($statement, $params);
		return $query->fetch(\PDO::FETCH_ASSOC);
	}

	/**
	 * Insert
	 *
	 * @param string $statement
	 * @param array $params
	 *
	 * @return int|null
	 */
	public static function insert($statement, array $params = [])
	{
		$pdo = self::prepare($statement, $params);

		if($pdo)
			return self::getInstance()->lastInsertId();

		return null;
	}

	/**
	 * Update
	 *
	 * @param string $statement
	 * @param array $params
	 *
	 * @return int|null
	 */
	public static function update($statement, array $params = [])
	{
		$pdo = self::prepare($statement, $params);
		return $pdo ? true : false;
	}

	/**
	 * Destroy everything
	 *
	 * @param string $statement
	 * @param array $params
	 *
	 * @return int|null
	 */
	public static function delete($statement, array $params = [])
	{
		$pdo = self::prepare($statement, $params);
		return $pdo ? true : false;
	}

	/**
	 * Execute query and select one column only
	 *
	 * @param string $statement
	 * @return mixed
	 */
	public static function queryFetchColAssoc($statement)
	{
		return self::getInstance()->query($statement)->fetchColumn();
	}

	/**
	 * Quotes a string for use in a query
	 *
	 * @param string $input
	 * @param int $parameter_type
	 * @return string
	 */
	public static function quote ($input, $parameter_type=0)
	{
		return self::getInstance()->quote($input, $parameter_type);
	}

	/**
	 * Rolls back a transaction
	 *
	 * @return bool
	 */
	public static function rollBack()
	{
		return self::getInstance()->rollBack();
	}

	/**
	 * Set an attribute
	 *
	 * @param int $attribute
	 * @param mixed $value
	 * @return bool
	 */
	public static function setAttribute($attribute, $value  )
	{
		return self::getInstance()->setAttribute($attribute, $value);
	}
}