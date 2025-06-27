<?php

namespace App\Middleware;

class AuthMiddleware
{
    private static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function check()
    {
        self::startSession();

        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }
    }

    public static function getCurrentUser()
    {
        self::startSession();

        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }

    public static function checkAdmin()
    {
        self::startSession();

        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['level_user'] !== null) {
                return;
            }
        }

        header('Location: ' . BASEURL . '/home');
        exit;
    }
}
