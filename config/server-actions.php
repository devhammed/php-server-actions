<?php

return [
	/*
	 * The prefix for the routes.
	 */
	'route' => '/server-actions',

	/**
	 * The middleware for the routes.
	 */
	'middleware' => ['web'],

	/*
	 * The entries storage provider.
	 */
	'server_entry' => [
		'provider' => \DevHammed\ServerActions\Concerns\JsonFileServerEntry::class,
		'parameters' => [
			\Illuminate\Support\Facades\App::storagePath('server-actions.json'),
		],
	],
];