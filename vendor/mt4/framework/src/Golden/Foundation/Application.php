<?php

namespace Golden\Foundation;

use Golden\Database\Drivers\MySql;
use Golden\Event\Event;
use Golden\Event\Events\OnBeforeLoad;
use Golden\Event\Events\OnLoad;
use Golden\Exception\FileNotFoundException;
use Golden\Exception\MySqlException;
use Golden\Http\Request;
use Golden\Routing\Router;
use Golden\Session\Session;


/**
 * Class Application
 *
 * @package Golden\Foundation
 */
class Application
{
	/**
	 * @var Application $instance
	 */
	private static $instance = null;

	/**
	 * @var string $view_sufix
	 */
	private static $view_sufix = '.view';

	/**
	 * @var string $dot_env_path
	 */
	private static $dot_env_path = '';

	/**
	 * @var array $dot_data
	 */
	private static $dot_data = [ ];

	/**
	 * @var string $app_url
	 */
	private static $app_url;

	/**
	 * @var Router $router
	 */
	private static $router;

	/**
	 * @var Event[] $events
	 */
	private $events = [
		Event::ON_BEFORE_LOAD => [ ],
		Event::ON_LOAD => [ ],
	];

	/**
	 * The MT4 framework version.
	 *
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * The base path;
	 *
	 * @var string
	 */
	protected $basePath;

	/**
	 * @var string
	 */
	protected $viewPath = DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;

	/**
	 * @var MySql $_db_instance
	 */
	private $_db_instance;


	/**
	 * Create a new Illuminate application instance.
	 *
	 * @param  string|null $basePath
	 *
	 * @throws FileNotFoundException
	 * @throws MySqlException
	 */
	public function __construct($basePath = null)
	{
		if ($basePath)
			$this->setBasePath($basePath);

		// -- setup event
		$this->setupEvents();

		// -- call OnBeforeLoad event
		$this->onBeforeLoad();

		// -- parse env
		$this->parseEnv();

		// -- set app url
		$this->setAppUrl();

		// -- set router
		$this->setRouter();

		// -- set view
		$this->setViewPath($basePath);

		// -- set Pdo
		$this->setDbInstance();

		// -- call OnLoad event
		$this->onLoad();
	}

	/**
	 * @return Application
	 */
	public static function getInstance() {
		return self::$instance;
	}

	/**
	 * @param Application $instance
	 */
	public static function setInstance( $instance ) {
		self::$instance = $instance;
	}

	/**
	 * @return string
	 */
	public static function getViewSufix() {
		return self::$view_sufix;
	}

	/**
	 * @param string $view_sufix
	 */
	public static function setViewSufix( $view_sufix ) {
		self::$view_sufix = $view_sufix;
	}

	/**
	 * @return string
	 */
	public function getDotEnvPath()
	{
		return $this->getBasePath() . DIRECTORY_SEPARATOR . '.env';
	}

	/**
	 * @param string $dot_env_path
	 */
	public static function setDotEnvPath( $dot_env_path )
	{
		self::$dot_env_path = $dot_env_path;
	}

	/**
	 * Set the base path for the application.
	 *
	 * @param  string  $basePath
	 * @return $this
	 */
	public function setBasePath($basePath)
	{
		$this->basePath = rtrim($basePath, '/');
		return $this;
	}

	/**
	 * @param mixed $key
	 *
	 * @return mixed|null
	 */
	public static function getDotData($key = null)
	{
		if(is_null($key))
			return self::$dot_data;

		return array_key_exists($key, self::$dot_data) ? self::$dot_data[$key] : null;
	}

	/**
	 * @param array $dot_data
	 */
	public static function setDotData( $dot_data )
	{
		self::$dot_data = $dot_data;
	}

	/**
	 * @return string
	 */
	public static function getAppUrl()
	{
		return self::$app_url;
	}

	/**
	 * Set app url
	 */
	public function setAppUrl()
	{
		self::$app_url = array_key_exists('APP_URL', self::getDotData()) ?
			rtrim(self::getDotData('APP_URL'), '/') : null;

		if(!is_null(self::$app_url))
		{
			self::$dot_data['APP_URL'] = self::$app_url;
		}
	}

	/**
	 * @return Router
	 */
	public static function getRouter() {
		return self::$router;
	}

	/**
	 * Set router
	 */
	private function setRouter() {
		self::$router = new Router( $this );
	}

	/**
	 * @return MySql
	 */
	public function getDbInstance()
	{
		return $this->_db_instance;
	}

	/**
	 * Set the DB instance
	 *
	 * @throws MySqlException
	 */
	public function setDbInstance( )
	{
		try
		{
			$this->_db_instance = new MySql(
				self::getDotData('DB_HOST'),
				self::getDotData('DB_PORT'),
				self::getDotData('DB_DATABASE'),
				self::getDotData('DB_USERNAME'),
				self::getDotData('DB_PASSWORD')
			);
		}
		catch (\Exception $ex)
		{
			throw new MySqlException('Error: Unable to connect to MySQL using .env file settings', 1);
		}
	}

	/**
	 * @return string
	 */
	public function getViewPath() {
		return $this->viewPath;
	}

	/**
	 * @param string $basePath
	 */
	public function setViewPath( $basePath ) {
		$this->viewPath = $basePath . $this->getViewPath();
	}

	/**
	 * @return string
	 */
	public function getBasePath() {
		return $this->basePath;
	}

	/**
	 * Parse de .
	 *
	 * @throws FileNotFoundException
	 */
	private function parseEnv()
	{
		if(!file_exists( self::getDotEnvPath() ))
			throw new FileNotFoundException('Error: The <strong>.env</strong> file was not found', 1);

		self::setDotData(parse_ini_file(self::getDotEnvPath()));
	}

	/**
	 * Set events
	 */
	private function setupEvents()
	{
		$this->setEvents([
			Event::ON_BEFORE_LOAD => new OnBeforeLoad( $this->setupOnBeforeLoadEvent() ),
			Event::ON_LOAD => new OnLoad( $this->setupOnLoadEvent() ),
		]);
	}

	/**
	 * @return \Closure
	 */
	private function setupOnBeforeLoadEvent()
	{
		return function () {
			// -- request
			Request::capture();
		};
	}

	/**
	 * @return \Closure
	 */
	private function setupOnLoadEvent()
	{
		return function () {
			call_user_func( Router::onLoad() );
			Session::flush()->start();
		};
	}

	/**
	 * @return Event[]
	 */
	public function getEvents()
	{
		return $this->events;
	}

	/**
	 * @param Event[] $events
	 */
	public function setEvents( $events )
	{
		$this->events = $events;
	}

	/**
	 * @param string $event_nam
	 *
	 * @return bool
	 */
	private function hasEvent($event_nam)
	{
		return array_key_exists($event_nam, $this->getEvents());
	}

	/**
	 * Run On Before Load
	 */
	public function onBeforeLoad()
	{
		if($this->hasEvent(Event::ON_BEFORE_LOAD))
		{
			/** @var $event OnBeforeLoad */
			$event = $this->getEvents()[Event::ON_BEFORE_LOAD];
			$event->run();
		}
	}

	/**
	 * Run On Load
	 */
	public function onLoad()
	{
		if($this->hasEvent(Event::ON_LOAD))
		{
			/** @var $event OnLoad */
			$event = $this->getEvents()[Event::ON_LOAD];
			$event->run();
		}
	}

}