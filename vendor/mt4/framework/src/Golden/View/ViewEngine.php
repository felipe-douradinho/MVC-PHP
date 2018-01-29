<?php

namespace Golden\View;

use Golden\Exception\ViewWasNotFound;
use Golden\Foundation\Application;


/**
 * Class ViewEngine
 *
 * @package Golden\View
 */
class ViewEngine
{
	/**
	 * @var string $view_path
	 */
	public $view_path;

	/**
	 * @var array $blocks
	 */
	public $blocks = [ ];

	/**
	 * @var array $append
	 */
	public $append = [ ];

	/**
	 * @var array $variables
	 */
	public $variables = [ ];


	/**
	 * Returns a new template object
	 *
	 * @param string $view_path
	 * @param array $variables
	 *
	 * @return ViewEngine
	 */
	public function __construct($view_path, array $variables = [ ])
	{
		$this->setViewPath( $view_path );
		$this->setVariables( $variables );

		return $this;
	}

	/**
	 * Allows setting template values while still returning the object instance
	 *
	 * $template->title($title)->text($text);
	 *
	 * @param string $key
	 * @param array $args
	 *
	 * @return ViewEngine
	 */
	public function __call($key, $args)
	{
		$this->$key = $args[0];
		return $this;
	}

	/**
	 * Render template HTML
	 *
	 * @return string
	 *
	 * @throws ViewWasNotFound
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * @param $view_path
	 * @param array $variables
	 *
	 * @return string
	 * @throws ViewWasNotFound
	 */
	public static function make($view_path, array $variables = [ ])
	{
		return (new static($view_path, $variables))->render();
	}

	/**
	 * @param string $view_path
	 *
	 * @throws ViewWasNotFound
	 */
	public function attach($view_path)
	{
		$this->setViewPath( $view_path );
		echo $this->render();
	}

	/**
	 * Load the given template
	 *
	 * @return string
	 * @throws ViewWasNotFound
	 */
	public function render()
	{
		$view_path = $this->getTemplatePath($this->getViewPath());

		if ( ! is_file( $view_path ) || ! is_readable( $view_path ) )
		{
			throw new ViewWasNotFound(
				"Error: The view <strong>{$view_path}</strong> was not found on the server.",
				1
			);
		}

		ob_start();
		extract( $this->getVariables() );
		/** @noinspection PhpIncludeInspection */
		require $view_path;
		return ob_get_clean();
	}

	/**
	 * @param string $view_path
	 *
	 * @return string
	 */
	private function getTemplatePath($view_path)
	{
		$view_sufix = Application::getViewSufix();

		$view_base_path = Application::getInstance()->getViewPath();
		$normalized_view_path = str_replace( '.', DIRECTORY_SEPARATOR, $view_path ) . $view_sufix . '.php';

		return $view_base_path . $normalized_view_path;
	}

	/**
	 * Extend a parent template
	 *
	 * @param string $view_path
	 *
	 * @throws ViewWasNotFound
	 */
	public function extend($view_path)
	{
		ob_end_clean(); // Ignore this child class and render the parent!
		ob_start();

		$this->setViewPath( $view_path );
		echo $this->render();
	}

	/**
	 * Start a new block
	 */
	public function start()
	{
		ob_start();
	}

	/**
	 * Empty default block to be extended by child templates
	 *
	 * @param string $name of block
	 *
	 * @return mixed
	 */
	public function block($name)
	{
		return array_key_exists( $name, $this->blocks ) ? $this->blocks[$name] : '';
	}

	/**
	 * End a block
	 *
	 * @param string $name
	 * @param boolean $keep_parent
	 * @param array $filters
	 */
	public function end($name, $keep_parent = FALSE, array $filters = [ ])
	{
		$buffer = ob_get_clean();

		foreach($filters as $filter)
			$buffer = $filter($buffer);

		// -- this block is already set
		if( ! isset($this->blocks[$name]))
		{
			$this->blocks[$name] = $buffer;

			if($keep_parent)
				$this->append[$name] = true;
		}

		elseif(isset($this->append[$name]))
		{
			$this->blocks[$name] .= $buffer;
		}

		echo $this->blocks[$name];
	}

	/**
	 * Convert special characters to HTML safe entities
	 *
	 * @param string $string to encode
	 * @return string
	 */
	public function e($string)
	{
		return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	}

	/**
	 * Convert dangerous HTML entities into special characters
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public function d($string)
	{
		return htmlspecialchars_decode($string, ENT_QUOTES);
	}

	/**
	 * @return array
	 */
	public function getVariables()
	{
		return $this->variables;
	}

	/**
	 * @param array $variables
	 */
	public function setVariables( $variables )
	{
		$this->variables = $variables;
	}

	/**
	 * @return string
	 */
	public function getViewPath() {
		return $this->view_path;
	}

	/**
	 * @param string $view_path
	 */
	public function setViewPath( $view_path ) {
		$this->view_path = $view_path;
	}
}