<?php

// base directory for the namespace prefix
$base_dir = __DIR__ . '/mt4/framework/src/';

require_once $base_dir . 'Golden/autoload.php';

spl_autoload_register(function ($class) use($base_dir)
{
	if(strpos($class, 'App\\') !== false)
	{
		$base_dir = __DIR__ . '/..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR;
		$class = str_replace('App\\', '', $class);
	}

	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = $base_dir . str_replace('\\', '/', $class) . '.php';

	// if the file exists, require it
	if (file_exists($file)) {
		require $file;
	}
});