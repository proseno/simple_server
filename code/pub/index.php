<?php
define('ROOT_PATH', dirname(__DIR__) . '/');
include "../app/code/autoload.php";

$application = \Core\Kernel::createApplication();
$application->run();