<?php

const PROJECT_PATH = __DIR__.'/';

require_once __DIR__ . '/core/Application.php';

$application = Core\Application::getInstance();
$application->run();
