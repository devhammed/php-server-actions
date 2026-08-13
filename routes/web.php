<?php

use Illuminate\Support\Facades\Route;
use DevHammed\ServerActions\Exceptions\InvalidIndexException;
use DevHammed\ServerActions\Exceptions\InvalidServerActionException;
use DevHammed\ServerActions\Exceptions\RequiredServerParameterException;

use function DevHammed\ServerActions\useServer;

Route::group([
	'as' => 'server-actions.',
	'prefix' => config('server-actions.route'),
	'middleware' => config('server-actions.middleware'),
], function() {
	Route::post('/', function() {
		try {
			return useServer()->run();
		} catch (InvalidIndexException|InvalidServerActionException $e) {
			abort(404, $e->getMessage());
		} catch (RequiredServerParameterException $e) {
			abort(400, $e->getMessage());
		}
	})->name('handle');
});