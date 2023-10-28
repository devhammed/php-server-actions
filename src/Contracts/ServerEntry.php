<?php

namespace DevHammed\ServerActions\Contracts;

use Closure;

interface ServerEntry
{
	public function register(string $name, Closure $callback): void;

	public function get(string $name): ?Closure;
}