<?php
use App\Application;
require_once dirname(__DIR__) ."/vendor/autoload.php";

$app = new Application();
echo $app->app();