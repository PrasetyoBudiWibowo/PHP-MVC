<?php

namespace App\Core;

class Controller
{
    public function model($model)
    {
        $modelPath = '../app/models/' . $model . '.php';

        if (file_exists($modelPath)) {
            require_once $modelPath;

            $modelClass = 'App\\Models\\' . $model;
            if (class_exists($modelClass)) {
                return new $modelClass();
            } else {
                throw new \Exception("Class model tidak ditemukan: $modelClass");
            }
        } else {
            throw new \Exception("File model tidak ditemukan: $modelPath");
        }
    }

    public function view($view, $data = [])
    {
        $viewPath = '../app/views/' . $view . '.php';

        if (file_exists($viewPath)) {
            extract($data);

            require_once $viewPath;
        } else {
            throw new \Exception("View tidak ditemukan: $viewPath");
        }
    }
}
