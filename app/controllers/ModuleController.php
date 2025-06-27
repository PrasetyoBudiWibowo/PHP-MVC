<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfToken;
use Respect\Validation\Validator as v;

class ModuleController extends Controller
{
    private $csrfTokenManager;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        AuthMiddleware::check();
        AuthMiddleware::checkAdmin();
        AuthMiddleware::getCurrentUser();

        $this->csrfTokenManager = new CsrfTokenManager();
    }

    public function list_module()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('inputModule')->getValue();

        $data['judul'] = 'List Module';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebar');
        $this->view('menu/module', $data);
        $this->view('layouts/footer/footer');
    }

    public function list_akses_user()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('aksesModule')->getValue();

        $data['judul'] = 'Akses Menu User';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebar');
        $this->view('menu/aksesMenu', $data);
        $this->view('layouts/footer/footer');
    }

    public function allDataModule()
    {
        $module = $this->model('ModuleModels')->allModule();

        header('Content-Type: application/json');

        if ($module) {
            echo json_encode(['status' => 'success', 'data' => $module]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function validasiAksesModule()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $kodeUser = $_POST['kd_user'] ?? null;
                $kdModule = $_POST['kd_module'] ?? null;

                if (!$kodeUser || !$kdModule) {
                    throw new \Exception('Kode user atau kode module tidak boleh kosong.');
                }

                $cekUser = $this->model('UserModels')->userWithLevel($kodeUser);
                if (!$cekUser) {
                    throw new \Exception('User tidak ditemukan.');
                }

                $module = $this->model('ModuleModels')->cekModule($kdModule);
                if (!$module) {
                    throw new \Exception('Menu tidak ditemukan.');
                }

                if ($cekUser['id_usr_level'] == 1) {
                    if (isset($module['url_module'])) {
                        echo json_encode(['status' => 'success', 'url_module' => $module['url_module']]);
                        return;
                    } else {
                        throw new \Exception('URL modul tidak ditemukan.');
                    }
                }

                $cekAkses = $this->model('ModuleModels')->cekAksesUser($kdModule, $kodeUser);
                if ($cekAkses) {
                    if ($cekAkses->status_akses === 'YA') {
                        if (isset($module['url_module'])) {
                            echo json_encode(['status' => 'success', 'url_module' => $module['url_module']]);
                            return;
                        } else {
                            throw new \Exception('URL modul tidak ditemukan.');
                        }
                    } else {
                        throw new \Exception('User tidak memiliki akses ke modul ini.');
                    }
                } else {
                    throw new \Exception('Akses modul tidak ditemukan.');
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiAksesModule');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiSimpanModule()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputModule', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $namaModule = v::stringType()
                    ->notEmpty()
                    ->length(3, 50)
                    ->regex('/^[a-zA-Z\s]+$/')
                    ->regex('/^[^<>]+$/');

                if (!$namaModule->validate($_POST['nama_module'])) {
                    throw new \Exception('Nama kota mengandung karakter lain selain huruf.');
                }

                $data = [
                    'nama_module' => $_POST['nama_module'],
                    'url_module' => $_POST['url_module'],
                    'user_input' => $_POST['kd_user'],
                ];

                $result = $this->model('ModuleModels')->simpanModule($data);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiSimpanModule');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiSimpanAksesModuleUser()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $data['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('aksesModule', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $dataSave = [];

                foreach ($data['data'] as $d) {
                    $checkExistingModules = $this->model('ModuleModels')->cekModule($d['kd_module']);
                    if (!$checkExistingModules) {
                        echo json_encode(['status' => 'error', 'message' => "Modul tidak ditemukan."]);
                        return;
                    }

                    $checkAksesUser = $this->model('ModuleModels')->cekAksesUser($d['kd_module'], $d['kd_user']);
                    if ($checkAksesUser && in_array($checkAksesUser['status_akses'], ['YA', 'TIDAK'], true)) {
                        $user = $this->model('UserModels')->cekKodeAsliUser($d['kd_user']);
                        echo json_encode([
                            'status' => 'error',
                            'message' => "Pengguna **{$user['nama_user']}** sudah memiliki akses ke modul **{$checkExistingModules['nama_module']}**."
                        ]);
                        return;
                    }

                    $dataSave[] = [
                        'kd_module' => $d['kd_module'],
                        'kd_user' => $d['kd_user'],
                        'user_input' => $d['user_input'],
                    ];
                }

                if (!empty($dataSave)) {
                    $result = $this->model('ModuleModels')->simpanAksesModule($dataSave);
                    if ($result) {
                        echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada data yang disimpan.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiSimpanAksesModuleUser');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
