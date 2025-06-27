<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;

class LevelUserController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::check();
        AuthMiddleware::checkAdmin();
        AuthMiddleware::getCurrentUser();
    }

    public function level()
    {
        $levels = $this->model('LevelUserModels')->getLevels();

        header('Content-Type: application/json');

        if ($levels) {
            echo json_encode(['status' => 'success', 'data' => $levels]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data user.']);
        }
    }
}
