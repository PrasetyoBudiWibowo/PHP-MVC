<?php

namespace App\Core;

use Dotenv\Dotenv;

class App
{
    protected $controller = 'App\Controllers\HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $this->loadEnv();

        $url = $this->parseUrl();

        if (!empty($_GET)) {
            $this->params = array_merge($this->params, $_GET);
        }

        if (isset($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerPath = '../app/controllers/' . $controllerName . '.php';
            $fullControllerName = 'App\Controllers\\' . $controllerName;

            if (file_exists($controllerPath) && class_exists($fullControllerName)) {
                $this->controller = $fullControllerName;
                unset($url[0]);
            } else {
                http_response_code(404);
                die("Controller tidak ditemukan.");
            }
        }

        $this->controller = new $this->controller;

        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        } elseif (isset($url[1])) {
            http_response_code(404);
            die("Method tidak ditemukan.");
        }

        $this->params = !empty($url) ? array_values($url) :  [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function loadEnv()
    {
        if (file_exists(__DIR__ . '/../.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();
        }
    }

    private function sanitizeUrl($url)
    {
        return filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL);
    }

    private function parseUrl()
    {
        if (isset($_GET['url'])) {
            $url = explode('/', $this->sanitizeUrl($_GET['url']));
            return $url;
        }
        return [];
    }
}
