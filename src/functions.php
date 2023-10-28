<?php

namespace DevHammed\ServerActions;

use Closure;
use DevHammed\ServerActions\Concerns\Server;

function serverAction(?Closure $callback = null): Server|string
{
	static $server = null;

	if (is_null($server)) {
		$server = new Server;
	}

	if (is_null($callback)) {
		return $server;
	}

	return $server->register($callback);
}