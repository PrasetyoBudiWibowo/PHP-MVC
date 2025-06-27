<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Database.php';
require_once 'middleware/AuthMiddleware.php';
require_once 'helper/helper.php';
require_once 'helper/deviceDetector.php';
require_once 'config/config.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

try {
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
    die('Koneksi database gagal: ' . $e->getMessage());
}

$requiredEnvVars = ['DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME'];
foreach ($requiredEnvVars as $var) {
    if (empty($_ENV[$var])) {
        die("Variabel lingkungan $var tidak diset.");
    }
}
