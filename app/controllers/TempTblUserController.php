<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;

class TempTblUserController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::check();
        AuthMiddleware::checkAdmin();
        AuthMiddleware::getCurrentUser();
    }

    public function TempUser()
    {
        $result = $this->model('TempTblUserModels')->getAllTempUser();

        header('Content-Type: application/json');

        if ($result) {
            echo json_encode(['status' => 'success', 'data' => $result]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data user.']);
        }
    }
}
