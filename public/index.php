<?php

declare(strict_types=1);

session_start();

require dirname(__DIR__) . '/config/database.php';

use App\Application;

$application = new Application();
$application->run();