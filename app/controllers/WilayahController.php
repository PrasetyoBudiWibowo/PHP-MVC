<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfToken;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class WilayahController extends Controller
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
    public function list_provinsi()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('inputProvinsi')->getValue();

        $data['judul'] = 'List Provinsi';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebar');
        $this->view('wilayah/provinsi/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function list_kota_kabupaten()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('inputKotaKabupaten')->getValue();

        $data['judul'] = 'List Kota Kabupaten';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebar');
        $this->view('wilayah/kota/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function list_kecamatan()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('inputKecamatan')->getValue();

        $data['judul'] = 'List Kecamatan';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebar');
        $this->view('wilayah/kecamatan/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function import_excel_provinsi()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('excelProvinsi')->getValue();

        $data['judul'] = 'Import Excel Provinsi';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebar');
        $this->view('wilayah/provinsi/importExcelProvinsi', $data);
        $this->view('layouts/footer/footer');
    }

    public function import_excel_kota_kabupaten()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('excelKotaKabupaten')->getValue();

        $data['judul'] = 'Import Excel Provinsi';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebar');
        $this->view('wilayah/kota/importExcelKota', $data);
        $this->view('layouts/footer/footer');
    }

    public function import_excel_kecamatan()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('excelKecamatan')->getValue();

        $data['judul'] = 'Import Excel Kecamatan';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebar');
        $this->view('wilayah/kecamatan/importExcelKecamatan', $data);
        $this->view('layouts/footer/footer');
    }

    // get data
    public function allDataProvinsi()
    {
        $provinsi = $this->model('WilayahModels')->allProvinsi();

        header('Content-Type: application/json');

        if ($provinsi) {
            echo json_encode(['status' => 'success', 'data' => $provinsi]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function allDataKotaKabupaten()
    {
        $kotaKabupaten = $this->model('WilayahModels')->allKotaKabupaten();

        header('Content-Type: application/json');

        if ($kotaKabupaten) {
            echo json_encode(['status' => 'success', 'data' => $kotaKabupaten]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function allDataKecamatan()
    {
        $kecamatan = $this->model('WilayahModels')->allKecamatan();

        header('Content-Type: application/json');

        if ($kecamatan) {
            echo json_encode(['status' => 'success', 'data' => $kecamatan]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function allDataKotaKabupatenWithProvinsi()
    {
        $kotaKabupaten = $this->model('WilayahModels')->allKotaKabupatenWithProvinsi();

        header('Content-Type: application/json');

        if ($kotaKabupaten) {
            echo json_encode(['status' => 'success', 'data' => $kotaKabupaten]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function allDataKecamatanWithKabKotaWithProvinsi()
    {
        $kecamatan = $this->model('WilayahModels')->allKecamatanWithKabKotaWithProvinsi();

        header('Content-Type: application/json');

        if ($kecamatan) {
            echo json_encode(['status' => 'success', 'data' => $kecamatan]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    // proses validasi dan simpan
    public function validasiSimpanDataProvinsi()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputProvinsi', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $namaProvinsi = v::stringType()
                    ->notEmpty()
                    ->length(3, 50)
                    ->regex('/^[a-zA-Z\s]+$/')
                    ->regex('/^[^<>]+$/');

                if (!$namaProvinsi->validate($_POST['nama_provinsi'])) {
                    throw new \Exception('Nama Provinsi mengandung karakter lain selain huruf.');
                }

                $cekNamaProvinsi = $this->model('WilayahModels')->cekNamaProvinsi($_POST['nama_provinsi']);

                if ($cekNamaProvinsi) {
                    throw new \Exception('Nama Provinsi sudah ada.');
                }

                $result = $this->model('WilayahModels')->simpanProvnisi($_POST);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiSimpanDataProvinsi');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function valiidasiUbahDataProvinsi()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputProvinsi', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekKodeProvinsi = $this->model('WilayahModels')->cekNamaProvinsi($_POST['nama_provinsi']);

                if ($cekKodeProvinsi) {
                    throw new \Exception('Data Provinsi Tidak Di temukan');
                }

                $namaProvinsi = v::stringType()
                    ->notEmpty()
                    ->length(3, 50)
                    ->regex('/^[a-zA-Z\s]+$/')
                    ->regex('/^[^<>]+$/');

                if (!$namaProvinsi->validate($_POST['nama_provinsi'])) {
                    throw new \Exception('Nama Provinsi mengandung karakter lain selain huruf.');
                }

                $result = $this->model('WilayahModels')->ubahProvinsi($_POST);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil di ubah.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di valiidasiUbahDataProvinsi');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiTempHapusProvinsi()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $cekKodeProvinsi = $this->model('WilayahModels')->cekNamaProvinsi($_POST['kd_provinsi']);

                if ($cekKodeProvinsi) {
                    throw new \Exception('Data Provinsi Tidak Di temukan');
                }

                $result = $this->model('WilayahModels')->ubahProvinsi($_POST);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil di Hapus.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di valiidasiUbahDataProvinsi');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiSimpanKotaKabupaten()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputKotaKabupaten', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekProvinsi = $this->model('WilayahModels')->cekProvinsiByCode($_POST['kd_provinsi']);

                if (!$cekProvinsi) {
                    throw new \Exception('Data Provisi tidak di temukan.');
                }

                $namaKotaKabupaten = v::stringType()
                    ->notEmpty()
                    ->length(3, 50)
                    ->regex('/^[a-zA-Z\s]+$/')
                    ->regex('/^[^<>]+$/');

                if (!$namaKotaKabupaten->validate($_POST['nama_kota_kabupaten'])) {
                    throw new \Exception('Nama kota mengandung karakter lain selain huruf.');
                }

                $result = $this->model('WilayahModels')->simpanKotaKabupaten($_POST);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiSimpanKotaKabupaten');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiUbahKotaKabupaten()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputKotaKabupaten', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekProvinsi = $this->model('WilayahModels')->cekProvinsiByCode($_POST['kd_provinsi']);

                if (!$cekProvinsi) {
                    throw new \Exception('Data Provinsi tidak ditemukan.');
                }

                $cekKotaKabupaten = $this->model('WilayahModels')->cekKotaByKode($_POST['kd_kota_kabupaten']);

                if (!$cekKotaKabupaten) {
                    throw new \Exception('Data Kota tidak ditemukan.');
                }

                $namaKotaKabupaten = v::stringType()
                    ->notEmpty()
                    ->length(3, 50)
                    ->regex('/^[a-zA-Z\s]+$/')
                    ->regex('/^[^<>]+$/');

                if (!$namaKotaKabupaten->validate($_POST['nama_kota_kabupaten'])) {
                    throw new \Exception('Nama kota mengandung karakter lain selain huruf.');
                }

                $result = $this->model('WilayahModels')->ubahDataKotaKabupaten($_POST);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiUbahKotaKabupaten');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiTempHapusKotaKabupaten()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $cekKodeProvinsi = $this->model('WilayahModels')->cekNamaProvinsi($_POST['kd_provinsi']);

                if ($cekKodeProvinsi) {
                    throw new \Exception('Data Provinsi Tidak Di temukan');
                }

                $cekKotaKabupaten = $this->model('WilayahModels')->cekKotaByKode($_POST['kd_kota_kabupaten']);

                if (!$cekKotaKabupaten) {
                    throw new \Exception('Data Kota tidak ditemukan.');
                }

                $result = $this->model('WilayahModels')->ubahDataKotaKabupaten($_POST);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil di hapus.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiTempHapusKotaKabupaten');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiSimpanKecamatan()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputKecamatan', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekKotaDanProvinsi = $this->model('WilayahModels')->cekKotaDanProvinsi($_POST['kd_kota_kabupaten'], $_POST['kd_provinsi']);

                if (!$cekKotaDanProvinsi) {
                    throw new \Exception('Data Kota atau Provinsi tidak ditemukan.');
                }

                $namaKecamatan = v::stringType()
                    ->notEmpty()
                    ->length(3, 50)
                    ->regex('/^[a-zA-Z\s]+$/')
                    ->regex('/^[^<>]+$/');

                if (!$namaKecamatan->validate($_POST['nama_kecamatan'])) {
                    throw new \Exception('Nama kota mengandung karakter lain selain huruf.');
                }

                $data = [
                    'kd_kota_kabupaten' => $_POST['kd_kota_kabupaten'],
                    'nama_kecamatan' => $_POST['nama_kecamatan'],
                    'user_input' => $_POST['kd_user'],
                ];

                $result = $this->model('WilayahModels')->simpanKecamatan($data);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiSimpanKecamatan');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiUbhaKecamatan()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputKecamatan', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekKecamatan = $this->model('WilayahModels')->cekKecamatanByCode($_POST['kd_kecamatan']);

                if (!$cekKecamatan) {
                    throw new \Exception('data kecamatan tidak ditemukan');
                }

                $namaKecamatan = v::stringType()
                    ->notEmpty()
                    ->length(3, 50)
                    ->regex('/^[a-zA-Z\s]+$/')
                    ->regex('/^[^<>]+$/');

                if (!$namaKecamatan->validate($_POST['nama_kecamatan'])) {
                    throw new \Exception('Nama kota mengandung karakter lain selain huruf.');
                }

                $dataSave = [
                    'type' => 'UBAH',
                    'kd_kecamatan' => $_POST['kd_kecamatan'],
                    'kd_kota_kabupaten' => $_POST['kd_kota_kabupaten'],
                    'nama_kecamatan' => $_POST['nama_kecamatan'],
                ];

                $result = $this->model('WilayahModels')->ubahKecamatan($dataSave);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data validasiUbhaKecamatan.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiUbhaKecamatan');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // simpan excel
    public function validasiExcelProvinsi()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $data['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('excelProvinsi', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $invalidProvinces = [];

                foreach ($data['data'] as $d) {
                    $namaProvinsi = $d['nama_provinsi'];

                    $validator = v::stringType()
                        ->notEmpty()
                        ->length(3, 50)
                        ->regex('/^[a-zA-Z\s]+$/')
                        ->regex('/^[^<>]+$/');

                    if (!$validator->validate($namaProvinsi)) {
                        $invalidProvinces[] = $namaProvinsi;
                    }
                }

                if (!empty($invalidProvinces)) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Nama provinsi berikut mengandung karakter tidak valid: ' . implode(', ', $invalidProvinces)
                    ]);
                }

                $result = $this->model('WilayahModels')->simpanExcelProvinsi($data['data']);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiExcelProvinsi');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiExcelKotaKabupaten()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $data['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('excelKotaKabupaten', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $invalidKotaKabupaten = [];
                $dataSave = [];

                foreach ($data['data'] as $d) {
                    $namaKotaKabupaten = $d['nama_kota_kabupaten'];

                    $validator = v::stringType()
                        ->notEmpty()
                        ->length(3, 100)
                        ->regex('/^[a-zA-Z\s]+$/')
                        ->regex('/^[^<>]+$/');

                    if (!$validator->validate($namaKotaKabupaten)) {
                        $invalidKotaKabupaten[] = $namaKotaKabupaten;
                    }

                    $kd_provinsi = $this->model('WilayahModels')->cekKotaByIdProvinsi($d['id_provinsi']);

                    if (!$kd_provinsi) {
                        echo json_encode(['status' => 'error', 'message' => "Provinsi dengan nama kota '{$d['nama_kota_kabupaten']}' tidak ditemukan."]);
                        return;
                    }

                    $dataSave[] = [
                        'kd_provinsi' => $kd_provinsi,
                        'id_kota_kabupaten' => $d['id_kota_kabupaten'],
                        'nama_kota_kabupaten' => $d['nama_kota_kabupaten'],
                        'user_input' => $d['kd_user'],
                    ];
                }

                if (!empty($invalidKotaKabupaten)) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Nama Kota/Kabupaten berikut mengandung karakter tidak valid: ' . implode(', ', $invalidKotaKabupaten)
                    ]);
                    return;
                }

                $result = $this->model('WilayahModels')->simpanExcelKotaKabupaten($dataSave);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiExcelKotaKabupaten');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiExcelKecamatan()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $data['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('excelKecamatan', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $dataSave = [];

                foreach ($data['data'] as $d) {

                    $kdKotaKabupaten = $this->model('WilayahModels')->cekKecamatanaByIdKota($d['id_kota_kabupaten']);

                    if (!$kdKotaKabupaten) {
                        echo json_encode(['status' => 'error', 'message' => "Kecamatan tidak ditemukan."]);
                        return;
                    }

                    $dataSave[] = [
                        'kd_kota_kabupaten' => $kdKotaKabupaten,
                        'id_kecamatan' => $d['id_kecamatan'],
                        'nama_kecamatan' => $d['nama_kecamatan'],
                        'user_input' => $d['kd_user'],
                    ];
                }


                $result = $this->model('WilayahModels')->simpanExcelKecamatan($dataSave);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data Kecamatan berhasi di input.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiExcelKecamatan');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
