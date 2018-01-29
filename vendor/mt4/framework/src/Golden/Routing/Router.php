<?php

namespace Golden\Routing;

use Golden\Foundation\Application;
use Golden\Http\Request;
use Golden\Http\Response;
use ReflectionMethod;

/**
 * Class Router
 *
 * @package Golden\Routing
 */
class Router
{
	/**
	 * All avaliable controllers mapped methods
	 */
	const CONTROLLER_METHOD_INDEX = 'index';
	const CONTROLLER_METHOD_STORE = 'store';
	const CONTROLLER_METHOD_CREATE = 'create';
	const CONTROLLER_METHOD_SHOW = 'show';
	const CONTROLLER_METHOD_EDIT = 'edit';
	const CONTROLLER_METHOD_UPDATE = 'update';
	const CONTROLLER_METHOD_DESTROY = 'destroy';
	const CONTROLLER_METHOD_DATATABLES = 'datatables';

	/*
	 * Array with avaliable controllers mapped methods
	 */
	public static $controllers_methods = [
		self::CONTROLLER_METHOD_INDEX   => '/[uri]',
		self::CONTROLLER_METHOD_STORE   => '/[uri]',
		self::CONTROLLER_METHOD_CREATE  => '/[uri]/create',
		self::CONTROLLER_METHOD_SHOW    => '/[uri]/{id}',
		self::CONTROLLER_METHOD_EDIT    => '/[uri]/{id}/edit',
		self::CONTROLLER_METHOD_UPDATE  => '/[uri]/{id}',
		self::CONTROLLER_METHOD_DESTROY => '/[uri]/{id}',
		self::CONTROLLER_METHOD_DATATABLES => '/[uri]',
	];

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected static $namespace = 'App\Http\Controllers';

	/**
	 * @var string $prefix
	 */
    public static $prefix = 'uri';

	/**
	 * @var array $routes_path
	 */
	protected static $routes_path = 'routes';

	/**
	 * @var Application $application
	 */
	protected static $application;

	/**
	 * @var array $resources
	 */
	protected $resources = [ ];

	/**
	 * @var array $route_files
	 */
    protected $route_files = [ ];

	/**
	 * The routes!
	 *
	 * @var array $routes
	 */
    protected $routes = [ ];

	/**
	 * @var string
	 */
	protected $parameterPattern = '/{([\w\d]+)}/';

	/**
	 * @var string
	 */
	protected $valuePattern = '(?P<$1>[^\/]+)';

	/**
	 * @var string
	 */
	protected $valuePatternReplace = '([^\/]+)';

	/**
	 * Router constructor.
	 *
	 * @param Application $application
	 */
    public function __construct(Application $application)
    {
    	$this->setApplication( $application );
    	$this->boot();
    }

	/**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
    	$this->scanRoutes();
    }

	/**
	 * Scan for route files
	 */
    private function scanRoutes()
    {
        $route_files = glob(
	        self::getApplication()->getBasePath() .
	        DIRECTORY_SEPARATOR . self::getRoutesPath() .
	        DIRECTORY_SEPARATOR . '*.php'
        );

	    foreach ( $route_files as $key => $route_file )
	    {
		    if(!file_exists($route_file))
		    	unset($route_files[$key]);
        }

        // -- add just existing files
		$this->setRouteFiles( $route_files );
	}

	/**
	 * @return array
	 */
	public static function getRoutesPath() {
		return self::$routes_path;
	}

	/**
	 * @param array $route_path
	 */
	public static function setRoutesPath( $route_path ) {
		self::$routes_path = $route_path;
	}

	/**
	 * @return string
	 */
	public static function getNamespace() {
		return self::$namespace;
	}

	/**
	 * @param string $namespace
	 */
	public static function setNamespace( $namespace ) {
		self::$namespace = $namespace;
	}

	/**
	 * @return Application
	 */
	public static function getApplication() {
		return self::$application;
	}

	/**
	 * @param Application $application
	 */
	private function setApplication( $application ) {
		self::$application = $application;
	}

	/**
	 * @return \Closure
	 */
	public static function onLoad()
	{
		return function () {
			foreach (Application::getRouter()->getRouteFiles() as $file)
			{
				/** @noinspection PhpIncludeInspection */
				include_once $file;
			}
		};
	}

	/**
	 * @return array
	 */
	public function getRouteFiles() {
		return $this->route_files;
	}

	/**
	 * @param array $route_files
	 */
	public function setRouteFiles( $route_files ) {
		$this->route_files = $route_files;
	}

	/**
	 * @param string $uri
	 * @param string $controller
	 * @param string $name
	 *
	 * @return Router
	 */
	private function addResource($uri, $controller, $name)
	{
		$this->resources[$uri] = [
			'uri' => $uri,
			'controller' => $controller,
			'name' => $name,
		];

		return Application::getRouter();
	}

	/**
	 * @return array
	 */
	public function getResources() {
		return $this->resources;
	}

	/**
	 * @return Router
	 */
	private function mapResource()
	{
		foreach ($this->getResources() as $key => $resource)
		{
			$mapped[ $resource['uri'] ] = [
				[
					'method'        => Request::METHOD_GET,
					'pattern'       => "{$resource['uri']}",
					'name'          => "{$resource['name']}.index",
					'controller'    => "{$resource['controller']}@index",
				],
				[
					'method'        => Request::METHOD_POST,
					'pattern'       => "{$resource['uri']}",
					'name'          => "{$resource['name']}.store",
					'controller'    => "{$resource['controller']}@store",
				],
				[
					'method'        => Request::METHOD_GET,
					'pattern'       => "{$resource['uri']}/create",
					'name'          => "{$resource['name']}.create",
					'controller'    => "{$resource['controller']}@create",
				],
				[
					'method'        => Request::METHOD_DELETE,
					'pattern'       => "{$resource['uri']}/{resource}",
					'name'          => "{$resource['name']}.destroy",
					'controller'    => "{$resource['controller']}@destroy",
				],
				[
					'method'        => Request::METHOD_GET,
					'pattern'       => "{$resource['uri']}/{resource}/destroy_get",
					'name'          => "{$resource['name']}.destroy_get",
					'controller'    => "{$resource['controller']}@destroy",
				],
				[
					'method'        => Request::METHOD_PUT,
					'pattern'       => "{$resource['uri']}/{resource}",
					'name'          => "{$resource['name']}.update",
					'controller'    => "{$resource['controller']}@update",
				],
				[
					'method'        => Request::METHOD_POST,
					'pattern'       => "{$resource['uri']}/{resource}",
					'name'          => "{$resource['name']}.update",
					'controller'    => "{$resource['controller']}@update",
				],
				[
					'method'        => Request::METHOD_GET,
					'pattern'       => "{$resource['uri']}/{resource}",
					'name'          => "{$resource['name']}.show",
					'controller'    => "{$resource['controller']}@show",
				],
				[
					'method'        => Request::METHOD_GET,
					'pattern'       => "{$resource['uri']}/{resource}/edit",
					'name'          => "{$resource['name']}.edit",
					'controller'    => "{$resource['controller']}@edit",
				],
			];

			$this->addRoute($mapped);
		}

		return Application::getRouter();
	}

	/**
	 * @return array
	 */
	public function getRoutes() {
		return $this->routes;
	}

	/**
	 * @param array $route
	 */
	public function addRoute( array $route = [ ]) {
		$this->routes = array_merge( $this->routes, $route );
	}

	/**
	 * Return a response according with the route
	 *
	 * @return Response
	 * @throws \Golden\Exception\ViewWasNotFound
	 * @throws \Exception
	 */
	public function route()
	{
		$return = null;
		$uri = Request::has( self::$prefix ) ? Request::get( self::$prefix ) : '/';

		foreach ( $this->getRoutes() as $base_uri => $routes )
		{
			foreach ($routes as $route)
			{
				// -- get pattern
				$pattern_as_regex = $this->getRegex( $route['pattern'] );

				if(preg_match($pattern_as_regex, $uri, $matches) && Request::getMethod() == $route['method'])
				{
					$parts = explode('@', $route['controller']);
					$controller = self::getNamespace() . '\\' . $parts[0];
					$controller_method = $parts[1];

					$params = array_intersect_key(
						$matches,
						array_flip(array_filter(array_keys($matches), 'is_string'))
					);

					if(!method_exists($controller, $controller_method))
						throw new \Exception("Error: Method {$controller_method} not found at controller {$controller}");

					// -- reflection method
					$reflection_method = new ReflectionMethod($controller, $controller_method);

					// -- if has params
					if(!empty( $params ))
						$return = $reflection_method->invoke( new $controller(), is_array($params) ? array_first($params) : null );
					else
						$return = $reflection_method->invoke( new $controller() );

					if($return)
						break;
				}

				if($return)
					break;
			}
		}

		if(!$return)
		{
			$return = view('errors.404');
			return Response::create( $return, 404 )->send();
		}

		return Response::create( $return )->send();
	}

	/**
	 * Get regex based on uri pattern
	 *
	 * @param $pattern
	 *
	 * @return bool|string
	 */
	public function getRegex($pattern)
	{
		if (preg_match('/[^-:\/_{}()a-zA-Z\d]/', $pattern))
			return false; // Invalid pattern

		// Turn "(/)" into "/?"
		$pattern = preg_replace('#\(/\)#', '/?', $pattern);

		// Create capture group for ":parameter"
		$allowedParamChars = '[a-zA-Z0-9\_\-]+';
		$pattern = preg_replace(
			'/:(' . $allowedParamChars . ')/',   # Replace ":parameter"
			'(?<$1>' . $allowedParamChars . ')', # with "(?<parameter>[a-zA-Z0-9\_\-]+)"
			$pattern
		);

		// Create capture group for '{parameter}'
		$pattern = preg_replace(
			'/{('. $allowedParamChars .')}/',    # Replace "{parameter}"
			'(?<$1>' . $allowedParamChars . ')', # with "(?<parameter>[a-zA-Z0-9\_\-]+)"
			$pattern
		);

		// Add start and end matching
		$patternAsRegex = "@^" . $pattern . "$@D";

		return $patternAsRegex;
	}

	/**
	 * @param string $uri
	 * @param string $controller
	 * @param string $name
	 *
	 * @return Router
	 */
	public static function resource( $uri, $controller, $name )
	{
		return Application::getRouter()
		                  ->addResource($uri, $controller, $name)
		                  ->mapResource();
	}

	/**
	 * @param string $method
	 * @param string $uri
	 * @param string $controller
	 * @param string $name
	 *
	 * @return Router
	 */
	private static function getRouteParams($method, $uri, $controller, $name )
	{
		$route = Application::getRouter()
		                    ->addResource($uri, $controller, $name)
		                    ->getResources();

		switch ($method)
		{
			case Request::METHOD_GET:
				$route[$uri]['method'] = Request::METHOD_GET;
				break;

			case Request::METHOD_POST:
				$route[$uri]['method'] = Request::METHOD_POST;
				break;

			case Request::METHOD_PUT:
				$route[$uri]['method'] = Request::METHOD_PUT;
				break;

			case Request::METHOD_PATCH:
				$route[$uri]['method'] = Request::METHOD_PATCH;
				break;

			case Request::METHOD_DELETE:
				$route[$uri]['method'] = Request::METHOD_DELETE;
				break;
		}

		$route[$uri]['pattern'] = $route[$uri]['uri'];

		Application::getRouter()->addRoute([ $route ]);

		return Application::getRouter();
	}

	/**
	 * @param string $name
	 * @param null|array|string $parameter
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function getRouteByName($name, $parameter = null)
	{
		if($result = collect(Application::getRouter()->getRoutes())->collapse()->where('name', $name)->first())
		{
			if(!is_null( $parameter ) && is_string($parameter) && !$parameter)
				return $result['pattern'];

			if(!is_null( $parameter ) && is_array($parameter))
			{
				$output = '/?'.self::$prefix.'=' . $result['pattern'];

				foreach ( $parameter as $item => $value )
				{
					$output = preg_replace('/\{[^)]+\}/', $value, $output);
				}

				return $output;
			}

			return '/?'.self::$prefix.'=' . preg_replace('/\{[^)]+\}/', $parameter, $result['pattern']);
		}

		throw new \Exception("Error: Route {$name} not found");
	}

	/**
	 * @param string $uri
	 * @param string $controller
	 * @param string $name
	 *
	 * @return Router
	 */
	public static function get( $uri, $controller, $name )
	{
		return self::getRouteParams(Request::METHOD_GET, $uri, $controller, $name);
	}

	/**
	 * @param string $uri
	 * @param string $controller
	 * @param string $name
	 *
	 * @return Router
	 */
	public static function post( $uri, $controller, $name )
	{
		return self::getRouteParams(Request::METHOD_POST, $uri, $controller, $name);
	}

	/**
	 * @param string $uri
	 * @param string $controller
	 * @param string $name
	 *
	 * @return Router
	 */
	public static function put( $uri, $controller, $name )
	{
		return self::getRouteParams(Request::METHOD_PUT, $uri, $controller, $name);
	}

	/**
	 * @param string $uri
	 * @param string $controller
	 * @param string $name
	 *
	 * @return Router
	 */
	public static function patch( $uri, $controller, $name )
	{
		return self::getRouteParams(Request::METHOD_PATCH, $uri, $controller, $name);
	}

	/**
	 * @param string $uri
	 * @param string $controller
	 * @param string $name
	 *
	 * @return Router
	 */
	public static function delete( $uri, $controller, $name )
	{
		return self::getRouteParams(Request::METHOD_DELETE, $uri, $controller, $name);
	}

}
