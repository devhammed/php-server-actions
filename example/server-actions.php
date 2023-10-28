<?php

require __DIR__ . '/../vendor/autoload.php';

use DevHammed\ServerActions\Concerns\JsonFileServerEntry;

use function DevHammed\ServerActions\useServer;

useServer()
	->withServerActionsUrl('/server-actions.php')
	->withServerEntry(
		new JsonFileServerEntry(
			__DIR__ . '/server-actions.json',
		),
	)
	->run();
