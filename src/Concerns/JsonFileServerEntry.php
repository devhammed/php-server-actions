<?php

namespace DevHammed\ServerActions\Concerns;

use Closure;
use Throwable;
use Opis\Closure\SerializableClosure;
use DevHammed\ServerActions\Contracts\ServerEntry;

class JsonFileServerEntry implements ServerEntry
{
	protected array $entries;

	public function __construct(protected string $file)
	{
		try {
			$this->entries = json_decode(file_get_contents($this->file), true);
		} catch (Throwable) {
			$this->entries = [];
		}
	}

	public function register(string $name, Closure $callback): void
	{
		$this->entries[$name] = serialize(
			new SerializableClosure($callback),
		);
	}

	public function get(string $name): ?Closure
	{
		if ( ! array_key_exists($name, $this->entries)) {
			return null;
		}

		$wrapper = unserialize($this->entries[$name]);

		if ( ! $wrapper instanceof SerializableClosure) {
			return null;
		}

		return $wrapper->getClosure();
	}

	public function __destruct()
	{
		file_put_contents($this->file, json_encode($this->entries));
	}
}