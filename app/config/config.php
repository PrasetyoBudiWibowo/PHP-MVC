<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

$requiredEnvVars = ['DB_HOST', 'DB_USERNAME', 'DB_DATABASE', 'BASEURL', 'APP_ENV', 'APP_DEBUG', 'APP_TIMEZONE'];

foreach ($requiredEnvVars as $var) {
    if (!isset($_ENV[$var])) {
        throw new Exception("Missing required environment variable: {$var}");
    }
}

define('APPROOT', dirname(__DIR__));
define('BASEURL', $_ENV['BASEURL']);
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USERNAME', $_ENV['DB_USERNAME']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? '');
define('DB_NAME', $_ENV['DB_DATABASE']);
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('APP_DEBUG', $_ENV['APP_DEBUG'] ?? false);
define('APP_TIMEZONE', $_ENV['APP_TIMEZONE'] ?? 'UTC');

try {
    $capsule = new Illuminate\Database\Capsule\Manager();
    $capsule->addConnection([
        'driver'    => $_ENV['DB_CONNECTION'],
        'host'      => $_ENV['DB_HOST'],
        'database'  => $_ENV['DB_DATABASE'],
        'username'  => $_ENV['DB_USERNAME'],
        'password'  => $_ENV['DB_PASSWORD'] ?? '',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();
} catch (Exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}
