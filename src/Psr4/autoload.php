<?php

// Example autoload PSR-4

require_once __DIR__ . '/Autoloader.php';

$autoloader = \Laventure\Autoload\Autoloader::load(__DIR__ . '/../framework/');

$autoloader->namespaces([
    'App\\' => 'app/',
    'Jan\\' => 'framework/src/'
]);


// load / require classes
$autoloader->register();


/*
\Laventure\Autoload\Autoloader::load(__DIR__ . '/../framework/')->register();
*/