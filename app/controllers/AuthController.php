<?php

namespace App\Controllers;

use App\Core\Controller;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfToken;

class AuthController extends Controller
{
    private $csrfTokenManager;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->csrfTokenManager = new CsrfTokenManager();
    }

    public function login()
    {
        $token = $this->csrfTokenManager->getToken('login')->getValue();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_user = $_POST['nama_user'];
            $password = $_POST['password'];
            $submittedToken = $_POST['_csrf_token'];

            if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('login', $submittedToken))) {
                echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                return;
            }

            $cekUser = $this->model('UserModels')->cekNamaUser($nama_user);

            if(!$cekUser) {
                echo json_encode(['status' => 'error', 'message' => 'USER TIDAK ADA']);
                return;
            }

            $userModel = $this->model('UserModels');
            $data = [
                'nama_user' => $nama_user,
                'password' => $password
            ];

            $user = $userModel->login($data);

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
                error_log("User logged in: " . json_encode($_SESSION['user']));
                header('Location: ' . BASEURL . '/home');
                exit;
            } else {
                $data['error'] = 'Login gagal.';
                $this->view('auth/login', ['error' => $data['error'], 'csrf_token' => $token]);
            }
        } else {
            $this->view('auth/login', ['csrf_token' => $token]);
        }
    }


    public function register()
    {
        $levelModel = $this->model('LevelUserModels');
        $levels = $levelModel->getLevels();
        $token = $this->csrfTokenManager->getToken('register')->getValue();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $submittedToken = $_POST['_csrf_token'];

            if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('register', $submittedToken))) {
                echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                return;
            }

            $data = [
                'nama_user' => $_POST['nama_user'],
                'id_usr_level' => $_POST['id_usr_level'],
                'password' => $_POST['password'],
            ];

            $cekExistsUser = $this->model('UserModels')->cekNamaUser($_POST['nama_user']);

            if ($cekExistsUser) {
                $data['error'] = 'Nama User Sudah Dipakai';
                echo json_encode(['status' => 'error', 'message' => $data['error']]);
                return;
            }

            $userModel = $this->model('UserModels');
            try {
                $userModel->register($data);
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            } catch (\Exception $e) {
                $data['error'] = $e->getMessage();
                $this->view('auth/register', ['levels' => $levels, 'error' => $data['error'], 'csrf_token' => $token]);
            }
        } else {
            $this->view('auth/register', ['levels' => $levels, 'csrf_token' => $token]);
        }
    }


    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: ' . BASEURL . '/auth/login');
        exit;
    }
}
