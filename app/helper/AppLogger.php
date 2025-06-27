<?php

namespace App\Helper;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class AppLogger
{
    public static function getLogger($name = 'app')
    {
        $log = new Logger($name);

        // Format log: "[datetime] level: message"
        $formatter = new LineFormatter(null, null, true, true);

        // File log
        $fileHandler = new StreamHandler(__DIR__ . '/../../logs/app.log', Logger::DEBUG);
        $fileHandler->setFormatter($formatter);
        $log->pushHandler($fileHandler);

        // Log ke terminal (CLI)
        $stdoutHandler = new StreamHandler('php://stdout', Logger::DEBUG, true);
        $stdoutHandler->setFormatter($formatter);
        $log->pushHandler($stdoutHandler);

        return $log;
    }
}