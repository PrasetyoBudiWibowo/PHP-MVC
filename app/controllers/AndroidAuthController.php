<?php

namespace App\Controllers;

use App\Core\Controller;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfToken;

class AndroidAuthController extends Controller
{
    private $csrfTokenManager;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->csrfTokenManager = new CsrfTokenManager();
        // Jangan panggil AuthMiddleware di sini, karena login itu public
    }

    public function login()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $token = $this->csrfTokenManager->getToken('android-login')->getValue();
            echo json_encode(['status' => 'ready', 'csrf_token' => $token]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Metode harus POST']);
            return;
        }

        $inputData = json_decode(file_get_contents('php://input'), true);

        $submittedToken = $inputData['csrf_token'] ?? '';

        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('android-login', $submittedToken))) {
            echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
            return;
        }

        $userModel = $this->model('UserModels');

        if (!$userModel->cekNamaUser($inputData['nama_user'])) {
            echo json_encode(['status' => 'error', 'message' => 'USER TIDAK ADA']);
            return;
        }

        $user = $userModel->login([
            'nama_user' => $inputData['nama_user'],
            'password' => $inputData['password'],
        ]);

        if ($user) {
            $_SESSION['user'] = [
                'kd_asli_user' => $user['kd_asli_user'],
                'nama_user' => $user['nama_user'],
                'id_level_user' => $user['id_level_user'],
                'level_user' => $user['level_user'][0]['level_user'] ?? 'Unknown',
                'img_user' => $user['img_user'],
                'format_img_user' => $user['format_img_user'],
                'password_tampil' => $user['password_tampil'],
                'status_user' => $user['status_user'],
                'blokir' => $user['blokir'],
            ];

            echo json_encode([
                'status' => 'success',
                'data' => $_SESSION['user'],
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Login gagal.']);
        }
    }
}
