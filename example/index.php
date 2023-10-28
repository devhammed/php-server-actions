<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DevHammed\ServerActions\Concerns\JsonFileServerEntry;

use function DevHammed\ServerActions\useServer;

useServer()
    ->withServerActionsUrl('/server-actions.php')
    ->withServerEntry(
       new JsonFileServerEntry(
           __DIR__ . '/server-actions.json',
       ),
    );

?>

<form
    method="post"
    action="<?= useServer(function (string $name) {
            echo 'Hello, ' . $name;
    }) ?>"
>
    <input type="hidden" name="name" value="Hammed">
    <button type="submit">Greet the creator</button>
</form>
