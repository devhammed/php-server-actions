<?php

namespace DevHammed\ServerActions\Concerns;

use Closure;
use Ramsey\Uuid\Uuid;
use ReflectionFunction;
use ReflectionParameter;
use DevHammed\ServerActions\Contracts\ServerEntry;
use DevHammed\ServerActions\Exceptions\InvalidIndexException;
use DevHammed\ServerActions\Exceptions\InvalidServerActionException;
use DevHammed\ServerActions\Exceptions\RequiredServerParameterException;

class Server
{
	/**
	 * The server actions URL.
	 */
	protected string $serverActionsUrl;

	/**
	 * The server actions entries.
	 */
	protected ServerEntry $serverEntry;

	/**
	 * Register a server action.
	 */
	public function register(Closure $callback): string
	{
		$actionName = (string) Uuid::uuid4();

		$this->serverEntry->register($actionName, $callback);

		return $this->serverActionsUrl . '?action=' . $actionName;
	}

	/**
	 * Set the server actions URL.
	 */
	public function setServerActionsUrl(string $serverActionsUrl): self
	{
		$this->serverActionsUrl = $serverActionsUrl;

		return $this;
	}

	/**
	 * Set the server actions entries.
	 */
	public function setServerEntry(ServerEntry $serverEntry): self
	{
		$this->serverEntry = $serverEntry;

		return $this;
	}

	/**
	 * Run the server action.
	 */
	public function run(): mixed
	{
		$actionName = $_GET['action'] ?? null;

		if (is_null($actionName)) {
			throw new InvalidIndexException('No action index provided.', 400);
		}

		$action = $this->serverEntry->get($actionName);

		if (is_null($action)) {
			throw new InvalidServerActionException('No action found.', 404);
		}

		$actionParameters = (new ReflectionFunction($action))->getParameters();

		$parameters = array_map(function (ReflectionParameter $parameter) {
			$name = $parameter->getName();
			$type = $parameter->getType()?->getName() ?? 'mixed';
			$value = $_GET[$name] ?? $_POST[$name] ?? $_FILES[$name] ?? null;

			if (is_null($value)) {
				if ($parameter->isOptional()) {
					return null;
				}

				throw new RequiredServerParameterException( 'No value provided for the action parameter: ' . $name, 422);
			}

			return match ($type) {
				'int' => (int) $value,
				'float' => (float) $value,
				'bool', 'boolean' => $value === 'true',
				default => $value,
			};
		}, $actionParameters);

		return $action(...$parameters);
	}
}