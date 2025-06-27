<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfToken;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;
use Mpdf\Mpdf;

class UserController extends Controller
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

    public function index()
    {
        $data['judul'] = 'List User';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebar');
        $this->view('user/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function tambah_user()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('tambahUser')->getValue();

        $data['judul'] = 'Tambah user';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebar');
        $this->view('user/tambahUser', $data);
        $this->view('layouts/footer/footer');
    }

    public function ubah()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('tempUser')->getValue();

        $data['judul'] = 'Ubah Data User';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebar');
        $this->view('user/ubahUser', $data);
        $this->view('layouts/footer/footer');
    }

    public function allDataUser()
    {
        $users = $this->model('UserModels')->AllUser();

        header('Content-Type: application/json');

        if ($users) {
            echo json_encode(['status' => 'success', 'data' => $users]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data user.']);
        }
    }

    public function getUserByLogin()
    {
        if (isset($_SESSION['user'])) {
            $userData = [
                'kd_asli_user' => htmlspecialchars($_SESSION['user']['kd_asli_user']),
                'username' => htmlspecialchars($_SESSION['user']['nama_user']),
                'id_level_user' => htmlspecialchars($_SESSION['user']['id_level_user']),
                'password_tampil' => htmlspecialchars($_SESSION['user']['password_tampil']),
                'status_user' => htmlspecialchars($_SESSION['user']['status_user']),
                'blokir' => htmlspecialchars($_SESSION['user']['blokir']),
                'img_user' => htmlspecialchars($_SESSION['user']['img_user']),
                'format_img_user' => htmlspecialchars($_SESSION['user']['format_img_user'])
            ];

            header('Content-Type: application/json');
            echo json_encode($userData);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'User not logged in']);
        }
    }

    public function validasiTambahUser()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES['img_user']) && $_FILES['img_user']['error'] === UPLOAD_ERR_OK) {

                    $submittedToken = $_POST['_csrf_token'];
                    if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('tambahUser', $submittedToken))) {
                        echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                        return;
                    }

                    $fileTmpPath = $_FILES['img_user']['tmp_name'];
                    $fileName = basename($_FILES['img_user']['name']);
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $fileSize = $_FILES['img_user']['size'];

                    $maxFileSize = 50 * 1024 * 1024;
                    $allowedExtensions = ['jpg', 'jpeg', 'png'];

                    if (!in_array($fileExtension, $allowedExtensions)) {
                        echo json_encode(['status' => 'error', 'message' => 'Format tidak valid. Hanya diperbolehkan jpg, jpeg, dan png.']);
                        return;
                    }

                    if ($fileSize > $maxFileSize) {
                        echo json_encode(['status' => 'error', 'message' => 'Ukuran gambar melebihi batas maksimal 50MB.']);
                        return;
                    }

                    $namaUserValidator = v::stringType()
                        ->notEmpty()
                        ->length(3, 50)
                        ->regex('/^[a-zA-Z\s]+$/')
                        ->regex('/^[^<>]+$/');

                    if (!$namaUserValidator->validate($_POST['nama_user'])) {
                        throw new \Exception('Nama user tidak valid.');
                    }

                    $newCode = $this->model('UserModels')->generateImage($_POST);
                    $newFileName = $newCode . '.' . $fileExtension;
                    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/mvc-project/public/img/user/';
                    $filePath = $uploadDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $filePath)) {
                        $data = [
                            'nama_user' => $_POST['nama_user'],
                            'id_usr_level' => $_POST['id_usr_level'],
                            'password' => $_POST['password'],
                            'img_user' => $newCode,
                            'user_input' => $_POST['user_input'],
                            'format_img_user' => $fileExtension
                        ];

                        $result = $this->model('UserModels')->register($data);

                        if ($result) {
                            echo json_encode(['status' => 'success', 'message' => 'User Berhasil Ditambahkan.']);
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                        }
                    } else {
                        throw new \Exception('Gagal mengunggah gambar.');
                    }
                } else {
                    throw new \Exception('Gambar tidak dikirim atau terjadi kesalahan saat proses upload.');
                }
            } else {
                throw new \Exception('Metode request tidak valid');
            }
        } catch (ValidationException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            return;
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            return;
        }
    }

    public function dataTempEdit()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $kdUserValidator = v::stringType()->notEmpty();
                $namaUserValidator = v::stringType()
                    ->notEmpty()
                    ->length(3, 50)
                    ->regex('/^[a-zA-Z\s]+$/')
                    ->regex('/^[^<>]+$/');

                if (!$kdUserValidator->validate($_POST['kd_user'])) {
                    throw new \Exception('Kode user tidak valid.');
                }

                if (!$namaUserValidator->validate($_POST['nama_user'])) {
                    throw new \Exception('Nama user tidak valid.');
                }

                $result = $this->model('UserModels')->dataTempEditUser($_POST);

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid. ');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function dataEdit()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES['img_user']) && $_FILES['img_user']['error'] === UPLOAD_ERR_OK) {

                    $submittedToken = $_POST['_csrf_token'];
                    if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('tempUser', $submittedToken))) {
                        echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                        return;
                    }

                    $fileTmpPath = $_FILES['img_user']['tmp_name'];
                    $fileName = basename($_FILES['img_user']['name']);
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $fileSize = $_FILES['img_user']['size'];

                    $maxFileSize = 50 * 1024 * 1024;
                    $allowedExtensions = ['jpg', 'jpeg', 'png'];

                    if (!in_array($fileExtension, $allowedExtensions)) {
                        echo json_encode(['status' => 'error', 'message' => 'Format tidak valid. Hanya diperbolehkan jpg, jpeg, dan png.']);
                        return;
                    }

                    if ($fileSize > $maxFileSize) {
                        echo json_encode(['status' => 'error', 'message' => 'Ukuran gambar melebihi batas maksimal 50MB.']);
                        return;
                    }

                    $kdUserValidator = v::stringType()->notEmpty();
                    $namaUserValidator = v::stringType()
                        ->notEmpty()
                        ->length(3, 50)
                        ->regex('/^[a-zA-Z\s]+$/')
                        ->regex('/^[^<>]+$/');

                    if (!$kdUserValidator->validate($_POST['kd_asli_user'])) {
                        throw new \Exception('Kode user tidak valid.');
                    }

                    if (!$namaUserValidator->validate($_POST['nama_user'])) {
                        throw new \Exception('Nama user tidak valid.');
                    }

                    $newCode = $this->model('UserModels')->generateImage($_POST);
                    $newFileName = $newCode . '.' . $fileExtension;
                    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/mvc-project/public/img/user/';
                    $filePath = $uploadDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $filePath)) {
                        $data = [
                            'kd_asli_user' => $_POST['kd_asli_user'],
                            'nama_user' => $_POST['nama_user'],
                            'id_usr_level' => $_POST['id_usr_level'],
                            'password' => $_POST['password'],
                            'img_user' => $newCode,
                            'user_input' => $_POST['user_input'],
                            'format_img_user' => $fileExtension
                        ];

                        $result = $this->model('UserModels')->ubahDataUser($data);

                        if ($result) {
                            echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                        }
                    } else {
                        throw new \Exception('Gagal mengunggah gambar.');
                    }
                } else {
                    throw new \Exception('Gambar tidak dikirim atau terjadi kesalahan saat proses upload.');
                }
            } else {
                throw new \Exception('Metode request tidak valid');
            }
        } catch (ValidationException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            return;
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            return;
        }
    }

    public function exportUserToPdf()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");


        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $data = $_POST['data'] ?? [];

                if (empty($data)) {
                    throw new \Exception('Data yang diterima kosong.');
                }

                $mpdf = new Mpdf();

                $html = '<h1>Data User</h1>';
                $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%;">';
                $html .= '<thead>
                            <tr>
                                <th>No</th>
                                <th>Nama User</th>
                                <th>Password</th>
                                <th>Tanggal Input</th>
                                <th>Gambar</th>
                            </tr>
                        </thead>
                        <tbody>';

                foreach ($data as $index => $row) {
                    $html .= '<tr>';
                    $html .= '<td>' . ($index + 1) . '</td>';
                    $html .= '<td>' . htmlspecialchars($row['nama_user']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($row['password_tampil']) . '</td>';
                    $html .= '<td>' . date('d F Y', strtotime($row['tgl_input'])) . '</td>';

                    if (!empty($row['img_user'])) {
                        $html .= '<td><img src="' . BASEURL . '/img/user/' . $row['img_user'] . '.' . $row['format_img_user'] . '" alt="User Image" style="width: 50px; height: 50px; border-radius: 50%;"></td>';
                    } else {
                        $html .= '<td><img src="' . BASEURL . '/img/default/Default-Profile.png" alt="Default Image" style="width: 50px; height: 50px; border-radius: 50%;"></td>';
                    }

                    $html .= '</tr>';
                }

                $html .= '</tbody></table>';

                $mpdf->WriteHTML($html);

                $mpdf->Output('data_user.pdf', 'I');

                echo json_encode(['status' => 'success', 'message' => 'PDF berhasil dibuat']);
            } else {
                throw new \Exception('Metode request tidak valid pada export user to PDF.');
            }
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal export user to PDF: ' . $e->getMessage()
            ]);
        }
    }
}
