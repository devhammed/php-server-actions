<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use DevHammed\ServerActions\Exceptions\InvalidIndexException;
use DevHammed\ServerActions\Exceptions\InvalidServerActionException;
use DevHammed\ServerActions\Exceptions\RequiredServerParameterException;

use function DevHammed\ServerActions\useServer;

Route::group([
	'as' => 'server-actions.',
	'prefix' => Config::get('server-actions.route'),
	'middleware' => Config::get('server-actions.middleware'),
], function() {
	Route::get('/', function() {
		try {
			return useServer()->run();
		} catch (InvalidIndexException|InvalidServerActionException $e) {
			App::abort(404, $e->getMessage());
		} catch (RequiredServerParameterException $e) {
			App::abort(400, $e->getMessage());
		}

		return null;
	})->name('handle');
});