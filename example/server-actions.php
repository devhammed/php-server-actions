<?php

require __DIR__ . '/../vendor/autoload.php';

use DevHammed\ServerActions\Concerns\JsonFileServerEntry;

use function DevHammed\ServerActions\serverAction;

serverAction()
	->setServerActionsUrl('/server-actions.php')
	->setServerEntry(
		new JsonFileServerEntry(
			__DIR__ . '/server-actions.json',
		),
	)
	->run();
