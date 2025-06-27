<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfToken;
use Respect\Validation\Validator as v;

class HrdController extends Controller
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

    // view
    public function index()
    {
        $data['judul'] = 'Halaman Dasboard HRD';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarHrd');
        $this->view('module/HRD/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function divisi()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('inputDivisi')->getValue();

        $data['judul'] = 'Halaman Divisi';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarHrd');
        $this->view('module/HRD/divisi/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function departement()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('inputDepartement')->getValue();

        $data['judul'] = 'Halaman Departement';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarHrd');
        $this->view('module/HRD/departement/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function master_karyawan()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('dataMasterKaryawan')->getValue();

        $data['judul'] = 'Halaman Master karyawan';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarHrd');
        $this->view('module/HRD/karyawan/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function edit_data_personal_karyawan()
    {

        $data['judul'] = 'Ubah Data Karyawan';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarHrd');
        $this->view('module/HRD/karyawan/ubahData/personalKaryawan', $data);
        $this->view('layouts/footer/footer');
    }

    public function edit_kontrak_karyawan()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('kontraKaryawan')->getValue();

        $data['judul'] = 'Halaman Edit Karyawan';

        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarHrd');
        $this->view('module/HRD/karyawan/ubahData/kontrakKaryawan', $data);
        $this->view('layouts/footer/footer');
    }

    public function dummy_karyawan()
    {
        $data['judul'] = 'Dummy Karyawan';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarHrd');
        $this->view('module/HRD/test/fakeKaryawan', $data);
        $this->view('layouts/footer/footer');
    }

    public function posisition_title()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('inputPosisionTitle')->getValue();

        $data['judul'] = 'Halaman Posistion Title';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarHrd');
        $this->view('module/HRD/posisi/posisitionTitle/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function foto_karyawan()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('fotoKaryawan')->getValue();

        $data['judul'] = 'Ubah Foto Karyawan';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarHrd');
        $this->view('module/HRD/karyawan/ubahData/fotoKaryawan/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function data_personal()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('personalKaryawan')->getValue();

        $data['judul'] = 'Ubah Data Personal Karyawan';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarHrd');
        $this->view('module/HRD/karyawan/ubahData/dataPersonalKaryawan/index', $data);
        $this->view('layouts/footer/footer');
    }

    // get Data
    public function allDataDivisi()
    {
        $divisi = $this->model('HrdModels')->allDivisi();

        header('Content-Type: application/json');

        if ($divisi) {
            echo json_encode(['status' => 'success', 'data' => $divisi]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function allDataDepartement()
    {
        $departement = $this->model('HrdModels')->allDepartement();

        header('Content-Type: application/json');

        if ($departement) {
            echo json_encode(['status' => 'success', 'data' => $departement]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function allDataPosisiton()
    {
        $posisition = $this->model('HrdModels')->allPosisition();

        header('Content-Type: application/json');

        if ($posisition) {
            echo json_encode(['status' => 'success', 'data' => $posisition]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function allDataKaryawan()
    {
        $karyawan = $this->model('HrdModels')->allKaryawan();

        header('Content-Type: application/json');

        if ($karyawan) {
            echo json_encode(['status' => 'success', 'data' => $karyawan]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function allDataCountry()
    {
        $Negara = $this->model('HrdModels')->allCountry();

        header('Content-Type: application/json');

        if ($Negara) {
            echo json_encode(['status' => 'success', 'data' => $Negara]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    // Bagian Divisi
    public function validasiSimpanDivisi()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputDivisi', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekNamaDivisi = $this->model('HrdModels')->cekNamaDivisi($_POST['nama_divisi']);

                $namaDivisi = v::stringType()
                    ->notEmpty()
                    ->length(1, 50)
                    ->regex('/^[a-zA-Z\s&]+$/u');

                if (!$namaDivisi->validate($_POST['nama_divisi'])) {
                    throw new \Exception('Nama divisi mengandung karakter lain selain huruf.');
                }

                if ($cekNamaDivisi) {
                    throw new \Exception('Nama Divisi sudah ada.');
                }

                $result = $this->model('HrdModels')->simpanDivisi($_POST);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiSimpanDivisi');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiUbahDataDivisi()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputDivisi', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekDivisi = $this->model('HrdModels')->cekDivisiByKd($_POST['kd_divisi']);

                if (!$cekDivisi) {
                    throw new \Exception('Divisi Tidak ditemukan.');
                }

                $result = $this->model('HrdModels')->ubahDivisi($_POST);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiUbahDataDivisi');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Bagian Departement
    public function validasiSimpanDepartement()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputDepartement', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekDivisi = $this->model('HrdModels')->cekDivisiByKd($_POST['kd_divisi']);

                if (!$cekDivisi) {
                    throw new \Exception('Divisi Tidak ditemukan.');
                }

                $namaDepartement = v::stringType()
                    ->notEmpty()
                    ->length(1, 50)
                    ->regex('/^[a-zA-Z\s&]+$/u');

                if (!$namaDepartement->validate($_POST['nama_departement'])) {
                    throw new \Exception('Nama Departement mengandung karakter lain selain huruf.');
                }

                $result = $this->model('HrdModels')->simpanDepartement($_POST);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiSimpanDepartement');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiUbahDepartement()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputDepartement', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekDivisi = $this->model('HrdModels')->cekDivisiByKd($_POST['kd_divisi']);

                if (!$cekDivisi) {
                    throw new \Exception('Divisi Tidak ditemukan.');
                }

                $cekDepartement = $this->model('HrdModels')->cekDepartementByKd($_POST['kd_departement']);

                if (!$cekDepartement) {
                    throw new \Exception('Departement Tidak ditemukan.');
                }

                $namaDepartement = v::stringType()
                    ->notEmpty()
                    ->length(1, 50)
                    ->regex('/^[a-zA-Z\s&]+$/u');

                if (!$namaDepartement->validate($_POST['nama_departement'])) {
                    throw new \Exception('Nama Departement mengandung karakter lain selain huruf.');
                }

                $result = $this->model('HrdModels')->ubahDepartement($_POST);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiUbahDepartement');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // bagian posisiton title
    public function validasiSimpanPosisitionTitle()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputPosisionTitle', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekDivisi = $this->model('HrdModels')->cekDivisiByKd($_POST['kd_divisi']);

                if (!$cekDivisi) {
                    throw new \Exception('Divisi Tidak ditemukan.');
                }

                $cekDepartement = $this->model('HrdModels')->cekDepartementByKd($_POST['kd_departement']);

                if (!$cekDepartement) {
                    throw new \Exception('Departement Tidak ditemukan.');
                }

                $namaPosisitonTitle = v::stringType()
                    ->notEmpty()
                    ->length(3, 50)
                    ->regex('/^[a-zA-Z\s&]+$/u');

                if (!$namaPosisitonTitle->validate($_POST['nama_position'])) {
                    throw new \Exception('Inputan Job Title Ada Yang Kurang Atau Salah.');
                }

                $result = $this->model('HrdModels')->simpanPosisitionTitle($_POST);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiSimpanPosisitionTitle');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Kontrak Karyawan
    public function validasiDataKontrakKaryawan()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('dataMasterKaryawan', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekKaryawan = $this->model('HrdModels')->cekKaryawanByKd($_POST['kd_karyawan']);

                if ($cekKaryawan) {
                    echo json_encode(['status' => 'success', 'message' => 'Data Di Dapatkan.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Karyawan Tidak Ditemukan.']);
                }
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiSimpanKontrakKaryawan()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('kontraKaryawan', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekKaryawan = $this->model('HrdModels')->cekKaryawanByKd($_POST['kd_karyawan']);

                if (!$cekKaryawan) {
                    throw new \Exception('Karyawan Tidak Ditemukan.');
                }

                $result = $this->model('HrdModels')->simpanDataPerpanjangKontrakKaryawan($_POST);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data.']);
                }
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Data Personal karyawan
    public function validasiDataPersonalKaryawan()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('dataMasterKaryawan', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekKaryawan = $this->model('HrdModels')->cekKaryawanByKd($_POST['kd_karyawan']);

                if ($cekKaryawan) {
                    echo json_encode(['status' => 'success', 'message' => 'Data Di Dapatkan.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Karyawan Tidak Ditemukan.']);
                }
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiUbahDataKaryawan()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($_POST['type'] === 'FOTO') {
                    if (isset($_FILES['img_kry']) && $_FILES['img_kry']['error'] === UPLOAD_ERR_OK) {

                        $submittedToken = $_POST['_csrf_token'];
                        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('fotoKaryawan', $submittedToken))) {
                            echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                            return;
                        }

                        $fileTmpPath = $_FILES['img_kry']['tmp_name'];
                        $fileName = basename($_FILES['img_kry']['name']);
                        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $fileSize = $_FILES['img_kry']['size'];

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

                        $cekKaryawan = $this->model('HrdModels')->cekKaryawanByKd($_POST['kd_karyawan']);

                        if (!$cekKaryawan) {
                            throw new \Exception('Karyawan Tidak Ditemukan.');
                        }

                        $newCode = $this->model('HrdModels')->generateImageKry($_POST);
                        $newFileName = $newCode . '.' . $fileExtension;
                        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/mvc-project/public/img/karyawan/';
                        $filePath = $uploadDir . $newFileName;

                        if (move_uploaded_file($fileTmpPath, $filePath)) {
                            $data = [
                                'kd_karyawan' => $_POST['kd_karyawan'],
                                'type' => $_POST['type'],
                                'foto_karyawan' => $newCode,
                                'format_gambar' => $fileExtension
                            ];

                            $result = $this->model('HrdModels')->ubahDataKaryawan($data);

                            if ($result) {
                                echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                            } else {
                                echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data KARYAWAN.']);
                            }
                        } else {
                            throw new \Exception('Gagal mengunggah gambar.');
                        }
                    }
                } else if ($_POST['type'] === 'PERSONAL KARYAWAN') {
                    $submittedToken = $_POST['csrf_token'];
                    if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('personalKaryawan', $submittedToken))) {
                        echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                        return;
                    }

                    $cekKaryawan = $this->model('HrdModels')->cekKaryawanByKd($_POST['kd_karyawan']);

                    if (!$cekKaryawan) {
                        throw new \Exception('Karyawan Tidak Ditemukan.');
                    }

                    $namaKaryawan = v::stringType()
                        ->notEmpty()
                        ->regex('/^[a-zA-Z\s&]+$/u');

                    if (!$namaKaryawan->validate($_POST['nama_karyawan'])) {
                        throw new \Exception('Nama Karyawan mengandung karakter lain selain huruf.');
                    }

                    $tglLahir = v::date('Y-m-d');

                    if (!$tglLahir->validate($_POST['tgl_lahir'])) {
                        throw new \Exception('Format tanggal lahir tidak valid. Gunakan format YYYY-MM-DD.');
                    }

                    $result = $this->model('HrdModels')->ubahDataKaryawan($_POST);
                    if ($result) {
                        echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data KARYAWAN.']);
                    }
                }
            } else {
                throw new \Exception('Metode request tidak valid Ubah Data Karyawan');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            return;
        }
    }
}
