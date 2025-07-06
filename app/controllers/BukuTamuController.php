<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfToken;
use Respect\Validation\Validator as v;

use App\Helper\AppLogger;

class BukuTamuController extends Controller
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
        $data['judul'] = 'Halaman Dasboard';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarBukuTamu');
        $this->view('module/BUKUTAMU/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function input_new_pengunjung()
    {
        $data['judul'] = 'Halaman Input Buku Tamu';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarBukuTamu');
        $this->view('module/BUKUTAMU/newCustomer', $data);
        $this->view('layouts/footer/footer');
    }

    public function input_pengunjung()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('inputBukuTamu')->getValue();

        $data['judul'] = 'Halaman Input Buku Tamu';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarBukuTamu');
        $this->view('module/BUKUTAMU/inputPengunjung', $data);
        $this->view('layouts/footer/footer');
    }

    public function sumber_informasi_buku_tamu()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('inputSumberInfomasiBukutamu')->getValue();

        $data['judul'] = 'Halaman Sumber Informasi Buku tamu';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarBukuTamu');
        $this->view('module/BUKUTAMU/sumberinformasi/sumberinformasi', $data);
        $this->view('layouts/footer/footer');
    }

    public function sumber_informasi_detail_buku_tamu()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('inputSumberInfomasiDetailBukutamu')->getValue();

        $data['judul'] = 'Halaman Sumber Informasi Detail Buku tamu';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarBukuTamu');
        $this->view('module/BUKUTAMU/sumberinformasi/sumberInformasiDetail', $data);
        $this->view('layouts/footer/footer');
    }

    public function alasan_kunjungan_buku_tamu()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('inputAlasanKunjunganBukuTamu')->getValue();

        $data['judul'] = 'Halaman Alasan Kunjungan Buku tamu';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarBukuTamu');
        $this->view('module/BUKUTAMU/alasanKunjungan/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function fake_pengunjung()
    {
        $data['judul'] = 'Halaman Input Buku Tamu';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarBukuTamu');
        $this->view('module/BUKUTAMU/dummy/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function allDataSumberInformasi()
    {
        $sumberInformasi = $this->model('BukuTamuModels')->getAllSumberInfromasi();

        header('Content-Type: application/json');

        if ($sumberInformasi) {
            echo json_encode(['status' => 'success', 'data' => $sumberInformasi]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function allDataSumberInformasiDetail()
    {
        $sumberInformasiDetail = $this->model('BukuTamuModels')->getAllSumberInformasiDetail();

        header('Content-Type: application/json');

        if ($sumberInformasiDetail) {
            echo json_encode(['status' => 'success', 'data' => $sumberInformasiDetail]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function allDataAlasanKunjungan()
    {
        $alasanKunjungan = $this->model('BukuTamuModels')->getAllAlasanKunjungan();

        header('Content-Type: application/json');

        if ($alasanKunjungan) {
            echo json_encode(['status' => 'success', 'data' => $alasanKunjungan]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function allDataKunjunganBukuTamu()
    {
        $kunjunganBukutamu = $this->model('BukuTamuModels')->getAllKunjunganBukuTamu();

        header('Content-Type: application/json');

        if ($kunjunganBukutamu) {
            echo json_encode(['status' => 'success', 'data' => $kunjunganBukutamu]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function validaSimpanSumberInformasi()
    {
        header('Content-Type: application/json');
        $log = AppLogger::getLogger('sumberinfomrasi');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputSumberInfomasiBukutamu', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $namaSumberInformasi = v::stringType()
                    ->notEmpty()
                    ->length(1, 50)
                    ->regex('/^[a-zA-Z\s&]+$/u');

                $namaInput = $_POST['nm_sumber_informasi'] ?? '';
                $log->info("Nama Sumber Informasi: $namaInput");

                if (!$namaSumberInformasi->validate($_POST['nm_sumber_informasi'])) {
                    throw new \Exception('Nama Sumber Informasi mengandung karakter lain selain huruf.');
                }

                $data = [
                    'nm_sumber_informasi' => $namaInput,
                    'kd_user' => $_POST['kd_user'],
                ];

                $log->info("Data dikirim ke model:", $data);

                $result = $this->model('BukuTamuModels')->simpanSumberInformasi($data);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validaSimpanSumberInformasi');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiUbahSumberInformasi()
    {
        header('Content-Type: application/json');
        $log = AppLogger::getLogger('UbahDataSumberInformasi');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $log->info("<========================== MULAI PROSES ==========================>");
                $submittedToken = $_POST['csrf_token'];
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputSumberInfomasiBukutamu', $submittedToken))) {
                    echo json_encode(['status' => 'error', 'message' => 'Token tidak valid!']);
                    return;
                }

                $cekSumberInformasi = $this->model('BukuTamuModels')->cekSumberInformasiByKode($_POST['kd_sumber_informasi_buku_tamu']);

                $namaInput = $_POST['nm_sumber_informasi'] ?? '';

                if (!$cekSumberInformasi) {
                    $log->info("Sumber Informasi: $namaInput (TIDAK ADA 🎉🎉🎉🎉)");
                    throw new \Exception('Sumber Inforasi Tidak ditemukan.');
                }

                $namaSumberInformasi = v::stringType()
                    ->notEmpty()
                    ->length(1, 50)
                    ->regex('/^[a-zA-Z\s&]+$/u');

                if (!$namaSumberInformasi->validate($_POST['nm_sumber_informasi'])) {
                    throw new \Exception('Nama Sumber Informasi mengandung karakter lain selain huruf.');
                }

                $data = [
                    'nm_sumber_informasi' => $namaInput,
                    'kd_sumber_informasi_buku_tamu' => $_POST['kd_sumber_informasi_buku_tamu'],
                    'tampil_buku_tamu' => $_POST['tampil_buku_tamu'],
                ];

                $log->info("Data dikirim ke model:", $data);

                $result = $this->model('BukuTamuModels')->ubahSumberInformasi($data);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiUbahSumberInformasi');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validaSimpanSumberInformasiDetail()
    {
        header('Content-Type: application/json');
        $log = AppLogger::getLogger('SUMBER-INFORMASI-DETAIL');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $log->info("<================= MULAI PROSES UNTUK SIMPAN SUMBER-INFORMASI-DETAIL =================>");
                $log->info("<================= MULAI PROSES DI CONTROLLER validaSimpanSumberInformasiDetail =================>");

                $inputData = json_decode(file_get_contents('php://input'), true);

                if (!$inputData) {
                    throw new \Exception('Data input tidak valid (bukan JSON).');
                }

                $submittedToken = $inputData['csrf_token'] ?? '';
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputSumberInfomasiDetailBukutamu', $submittedToken))) {
                    $log->info("<================= TOKEN TIDAK VALID =================>");
                    throw new \Exception('TOKEN TIDAK VALID');
                    return;
                }

                $kdSumberInformasi = $inputData['kd_sumber_informasi_buku_tamu'] ?? '';
                $cekSumberInformasi = $this->model('BukuTamuModels')->cekSumberInformasiByKode($kdSumberInformasi);

                if (!$cekSumberInformasi) {
                    $log->info("<================= SUMBER INFORMASI TIDAK ADA =================>");
                    throw new \Exception('Sumber Informasi Tidak ditemukan.');
                }

                $namaSumberInformasiDetail = $inputData['nm_sumber_informasi_detail'] ?? '';

                $validator = v::stringType()
                    ->notEmpty()
                    ->length(1, 50)
                    ->regex('/^[a-zA-Z\s&]+$/u');

                if (!$validator->validate($namaSumberInformasiDetail)) {
                    $log->error("Validasi gagal untuk input nama_sumber_informasi_detail", [
                        'invalid_input' => $namaSumberInformasiDetail,
                        'expected_format' => 'Hanya huruf, spasi, & dan maksimal 50 karakter'
                    ]);

                    throw new \Exception("Input tidak valid: \"{$namaSumberInformasiDetail}\". Nama hanya boleh mengandung huruf");
                }

                $result = $this->model('BukuTamuModels')->simpanSumberInformasiDetail($inputData);

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validaSimpanSumberInformasiDetail');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiubahSumberInformasiDetail()
    {
        header('Content-Type: application/json');
        $log = AppLogger::getLogger('UBAH-SUMBER-INFORMASI-DETAIL');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $log->info("<================= MULAI PROSES UNTUK UBAH-SUMBER-INFORMASI-DETAIL =================>");
                $log->info("<================= MULAI PROSES DI CONTROLLER validasiubahSumberInformasiDetail =================>");

                $inputData = json_decode(file_get_contents('php://input'), true);

                if (!$inputData) {
                    throw new \Exception('Data input tidak valid (bukan JSON).');
                }

                $submittedToken = $inputData['csrf_token'] ?? '';
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputSumberInfomasiDetailBukutamu', $submittedToken))) {
                    $log->info("<================= TOKEN TIDAK VALID =================>");
                    throw new \Exception('TOKEN TIDAK VALID');
                    return;
                }

                $kdSumberInformasi = $inputData['kd_sumber_informasi_buku_tamu'] ?? '';
                $cekSumberInformasi = $this->model('BukuTamuModels')->cekSumberInformasiByKode($kdSumberInformasi);

                if (!$cekSumberInformasi) {
                    $log->info("<================= SUMBER INFORMASI TIDAK ADA =================>");
                    throw new \Exception('Sumber Informasi Tidak ditemukan.');
                }

                $kdSumberInformasiDetail = $inputData['kd_sumber_informasi_detail_buku_tamu'] ?? '';
                $cekSumberInformasiDetail = $this->model('BukuTamuModels')->cekSumberInformasiDetailByKode($kdSumberInformasiDetail);

                if (!$cekSumberInformasiDetail) {
                    $log->info("<================= SUMBER INFORMASI DETAIL TIDAK ADA =================>");
                    throw new \Exception('Sumber Informasi Detail Tidak ditemukan.');
                }

                $namaSumberInformasiDetail = $inputData['nm_sumber_informasi_detail'] ?? '';

                $validator = v::stringType()
                    ->notEmpty()
                    ->length(1, 50)
                    ->regex('/^[a-zA-Z\s&]+$/u');

                if (!$validator->validate($namaSumberInformasiDetail)) {
                    $log->error("Validasi gagal untuk input nama_sumber_informasi_detail", [
                        'invalid_input' => $namaSumberInformasiDetail,
                        'expected_format' => 'Hanya huruf, spasi, & dan maksimal 50 karakter'
                    ]);

                    throw new \Exception("Input tidak valid: \"{$namaSumberInformasiDetail}\". Nama hanya boleh mengandung huruf");
                }

                $result = $this->model('BukuTamuModels')->ubahSumberInformasiDetail($inputData);

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil diubah.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data.']);
                }
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiSimpanAlasanKunjungan()
    {
        header('Content-Type: application/json');
        $log = AppLogger::getLogger('SIMPAN-ALASAN-KUNJUNGAN');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $log->info("<================= MULAI PROSES UNTUK SIMPAN ALASAN KUJUNGAN BUKU TAMU =================>");
                $log->info("<================= MULAI PROSES DI CONTROLLER validasiSimpanAlasanKunjungan =================>");

                $inputData = json_decode(file_get_contents('php://input'), true);

                if (!$inputData) {
                    throw new \Exception('Data input tidak valid (bukan JSON).');
                }

                $submittedToken = $inputData['csrf_token'] ?? '';
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputAlasanKunjunganBukuTamu', $submittedToken))) {
                    $log->info("<================= TOKEN TIDAK VALID =================>");
                    throw new \Exception('TOKEN TIDAK VALID');
                    return;
                }

                $validasiNamaAlasanKunjungan = v::stringType()
                    ->notEmpty()
                    ->length(1, 50)
                    ->regex('/^[a-zA-Z\s&]+$/u');

                $namaInput = $inputData['nama_alasan_kunjungan'];
                $log->info("Nama Sumber Informasi: $namaInput");

                if (!$validasiNamaAlasanKunjungan->validate($namaInput)) {
                    throw new \Exception('Nama Sumber Informasi mengandung karakter lain selain huruf.');
                }

                $result = $this->model('BukuTamuModels')->simpanAlasanKunjungan($inputData);

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiSimpanAlasanKunjungan');
            }
        } catch (\Throwable $th) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
            exit;
        }
    }

    public function validasiUbahAlasanKunjungan()
    {
        header('Content-Type: application/json');
        $log = AppLogger::getLogger('UBAH-ALASAN-KUNJUNGAN');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $log->info("<================= MULAI PROSES UNTUK UBAH-ALASAN-KUNJUNGAN =================>");
                $log->info("<================= MULAI PROSES DI CONTROLLER validasiUbahAlasanKunjungan =================>");

                $inputData = json_decode(file_get_contents('php://input'), true);

                if (!$inputData) {
                    throw new \Exception('Data input tidak valid (bukan JSON).');
                }

                $submittedToken = $inputData['csrf_token'] ?? '';
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputAlasanKunjunganBukuTamu', $submittedToken))) {
                    $log->info("<================= TOKEN TIDAK VALID =================>");
                    throw new \Exception('TOKEN TIDAK VALID');
                    return;
                }

                $validasiNamaAlasanKunjungan = v::stringType()
                    ->notEmpty()
                    ->length(1, 50)
                    ->regex('/^[a-zA-Z\s&]+$/u');

                $namaInput = $inputData['ubah_nama_alasan_kunjungan'];
                $log->info("Nama Sumber Informasi: $namaInput");

                if (!$validasiNamaAlasanKunjungan->validate($namaInput)) {
                    throw new \Exception('Nama Sumber Informasi mengandung karakter lain selain huruf.');
                }

                $result = $this->model('BukuTamuModels')->ubahAlasanKunjungan($inputData);

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiUbahAlasanKunjungan');
            }
        } catch (\Throwable $th) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
            exit;
        }
    }

    public function validasiSimpanPengunjungBaru()
    {
        header('Content-Type: application/json');
        $log = AppLogger::getLogger('SIMPAN-PENGUNJUNG-BARU-BUKU-TAMU');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $log->info("<================= MULAI PROSES UNTUK UBAH-ALASAN-KUNJUNGAN =================>");
                $log->info("<================= MULAI PROSES DI CONTROLLER validasiUbahAlasanKunjungan =================>");

                $inputData = json_decode(file_get_contents('php://input'), true);

                if (!$inputData) {
                    throw new \Exception('Data input tidak valid (bukan JSON).');
                }

                $submittedToken = $inputData['csrf_token'] ?? '';
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputBukuTamu', $submittedToken))) {
                    $log->info("<================= TOKEN TIDAK VALID =================>");
                    throw new \Exception('TOKEN TIDAK VALID');
                    return;
                }

                $validasiInput = v::stringType()
                    ->notEmpty()
                    ->length(1, 50)
                    ->regex('/^[a-zA-Z\s&]+$/u');

                $namaPengunjung = $inputData['nama_pengunjung'];
                $log->info("Nama Pengunjung: $namaPengunjung");

                if (!$validasiInput->validate($namaPengunjung)) {
                    throw new \Exception("Nama Pengunjung: \"{$namaPengunjung}\" mengandung karakter lain selain huruf.");
                }

                $alasnKunjunganDetail = $inputData['alasan_kunjungan_detail'];

                if (!empty($alasnKunjunganDetail)) {
                    $log->info("Alasan Kunjungan Detail: $namaPengunjung");
                    if (!$validasiInput->validate($alasnKunjunganDetail)) {
                        throw new \Exception("Alasan Kunjungan Detail: \"{$alasnKunjunganDetail}\" mengandung karakter lain selain huruf.");
                    }
                }

                $result = $this->model('BukuTamuModels')->simpanPengunjungBaruBukuTamu($inputData);

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiSimpanPengunjungBaru');
            }
        } catch (\Throwable $th) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
            exit;
        }
    }
}
