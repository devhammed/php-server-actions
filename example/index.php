<?php

require_once __DIR__ . '/../vendor/autoload.php';

use DevHammed\ServerActions\Concerns\JsonFileServerEntry;

use function DevHammed\ServerActions\serverAction;

serverAction()
    ->setServerActionsUrl('/server-actions.php')
    ->setServerEntry(
       new JsonFileServerEntry(
           __DIR__ . '/server-actions.json',
       ),
    );

?>

<form
    method="post"
    action="<?= serverAction(function (string $name) {
            echo 'Hello, ' . $name;
    }) ?>"
>
    <input type="hidden" name="name" value="Hammed">
    <button type="submit">Greet the creator</button>
</form>
