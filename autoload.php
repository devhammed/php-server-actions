<?php

require_once __DIR__ . '/functions.php';

spl_autoload_register(function (string $class) {
	$class = ltrim($class, '\\');
	$namespaces = [
		'DevHammed\ServerActions' => __DIR__ . '/src',
	];

	foreach ($namespaces as $namespace => $dir) {
		if ( ! str_starts_with($class, $namespace)) {
			continue;
		}

		$path = '';
		$class = substr($class, strlen($namespace));

		if (($pos = strripos($class, '\\')) !== false) {
			$path = str_replace('\\', '/', substr($class, 0, $pos)) . '/';
			$class = substr($class, $pos + 1);
		}

		$path .= str_replace('_', '/', $class) . '.php';
		$dir .= '/' . $path;

		if (file_exists($dir)) {
			include $dir;
			return true;
		}

		return false;
	}

	return false;
});
