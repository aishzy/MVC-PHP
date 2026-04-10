<?php

    // Front controller entry point & Bootstrap
    declare(strict_types=1);

    define('BASE_PATH', dirname(__DIR__));

    require_once BASE_PATH . '/vendor/autoload.php';

    // Load environment variables 
    $dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->safeLoad();

    // Load helpers
    require_once BASE_PATH . '/core/Helpers.php';
    // Boot the application
    $app = new Core\App();
    $app->run();
?>

