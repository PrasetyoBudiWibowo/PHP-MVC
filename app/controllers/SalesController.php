<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfToken;
use Respect\Validation\Validator as v;

use App\Helper\AppLogger;

class SalesController extends Controller
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
        $data['judul'] = 'Halaman Dasboard Sales';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarSales');
        $this->view('module/SALES/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function spv()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('inputSpvSales')->getValue();

        $data['judul'] = 'Halaman SPV Sales';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarSales');
        $this->view('module/SALES/spv/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function sales()
    {
        $data['csrf_token'] = $this->csrfTokenManager->getToken('inputSales')->getValue();

        $data['judul'] = 'Halaman Sales';
        $this->view('layouts/header/header', $data);
        $this->view('layouts/sidebar/sidebarSales');
        $this->view('module/SALES/salesman/index', $data);
        $this->view('layouts/footer/footer');
    }

    public function allDataSpvSales()
    {
        $spvSales = $this->model('SalesModels')->getAllSpvSales();

        header('Content-Type: application/json');

        if ($spvSales) {
            echo json_encode(['status' => 'success', 'data' => $spvSales]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }

    public function allDataSales()
    {
        $masterSales = $this->model('SalesModels')->getAllSales();

        header('Content-Type: application/json');

        if ($masterSales) {
            echo json_encode(['status' => 'success', 'data' => $masterSales]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data.']);
        }
    }


    public function validasiSimpanSpvSales()
    {
        header('Content-Type: application/json');
        $log = AppLogger::getLogger('SPV-SALES');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $log->info("<================= MULAI PROSES UNTUK SIMPAN SPV SALES =================>");
                $log->info("<================= MULAI PROSES DI CONTROLLER validasiSimpanSpvSales =================>");

                $inputData = json_decode(file_get_contents('php://input'), true);

                if (!$inputData) {
                    throw new \Exception('Data input tidak valid (bukan JSON).');
                }

                $submittedToken = $inputData['csrf_token'] ?? '';
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputSpvSales', $submittedToken))) {
                    $log->info("<================= TOKEN TIDAK VALID =================>");
                    throw new \Exception('TOKEN TIDAK VALID');
                    return;
                }

                $kdKaryawan = $inputData['kd_karyawan'] ?? '';
                $namaSpv = $inputData['nama_spv_sales'] ?? '';

                $cekKaryawan = $this->model('HrdModels')->cekKaryawanByKd($kdKaryawan);

                if (!$cekKaryawan) {
                    $log->error("Validasi gagal untuk input nama_spv_sales", [
                        'invalid_input' => $namaSpv,
                        'expected_format' => 'KARYAWAN TIDAK DITEMUKAN'
                    ]);

                    throw new \Exception("Karyawan Dengan nama: \"{$namaSpv}\" Tidak ditemukan");
                }

                $spvSales =  $this->model('SalesModels')->cekSpvSalesByKdKaryawan($kdKaryawan);

                if ($spvSales) {
                    $log->error("Validasi gagal untuk input nama_karyawan", [
                        'invalid_input' => $namaSpv,
                        'expected_format' => 'SPV SUDAH DIDAFTARKAN'
                    ]);

                    throw new \Exception("SPV sudah dengan nama: \"{$namaSpv}\". Sudah Terdaftar");
                }

                $result = $this->model('SalesModels')->simpanSpvSales($inputData);

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiSimpanSpvSales');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function validasiUbahSpvSales()
    {
        header('Content-Type: application/json');
        $log = AppLogger::getLogger('UBAJ-SPV-SALES');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $log->info("<================= MULAI PROSES UNTUK UBAH SPV SALES =================>");
                $log->info("<================= MULAI PROSES DI CONTROLLER validasiUbahSpvSales =================>");

                $inputData = json_decode(file_get_contents('php://input'), true);

                if (!$inputData) {
                    throw new \Exception('Data input tidak valid (bukan JSON).');
                }

                $submittedToken = $inputData['csrf_token'] ?? '';
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputSpvSales', $submittedToken))) {
                    $log->info("<================= TOKEN TIDAK VALID =================>");
                    throw new \Exception('TOKEN TIDAK VALID');
                    return;
                }

                $kdSpvSales = $inputData['kd_spv_sales'] ?? '';
                $namaSpv = $inputData['ubah_nama_spv_sales'] ?? '';

                $spvSales =  $this->model('SalesModels')->cekSpvSalesBySpvSales($kdSpvSales);

                if (!$spvSales) {
                    $log->error("Validasi gagal untuk input nama_karyawan", [
                        'invalid_input' => $namaSpv,
                        'expected_format' => 'SPV SALES TIDAK ADA'
                    ]);

                    throw new \Exception("SPV sudah dengan nama: \"{$namaSpv}\". Tidak Ditemukan");
                }

                $result = $this->model('SalesModels')->ubahSpvSales($inputData);

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data.']);
                }
            }
        } catch (\Throwable $th) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
            exit;
        }
    }

    public function validasiSimpanSales()
    {
        header('Content-Type: application/json');
        $log = AppLogger::getLogger('SIMPAN-SALES');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $log->info("<================= MULAI PROSES SIMPAN SALES =================>");
                $log->info("<================= MULAI PROSES DI CONTROLLER validasiSimpanSales =================>");

                $inputData = json_decode(file_get_contents('php://input'), true);

                if (!$inputData) {
                    throw new \Exception('Data input tidak valid (bukan JSON).');
                }

                $submittedToken = $inputData['csrf_token'] ?? '';
                if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('inputSales', $submittedToken))) {
                    $log->info("<================= TOKEN TIDAK VALID =================>");
                    throw new \Exception('TOKEN TIDAK VALID');
                    return;
                }

                $kdKaryawan = $inputData['kd_karyawan'] ?? '';
                $namaSales = $inputData['nama_sales'] ?? '';

                $cekKaryawan = $this->model('HrdModels')->cekKaryawanByKd($kdKaryawan);

                if (!$cekKaryawan) {
                    $log->error("Validasi gagal untuk input cekKaryawan", [
                        'invalid_input' => $namaSales,
                        'expected_format' => 'KARYAWAN TIDAK DITEMUKAN'
                    ]);

                    throw new \Exception("Karyawan Dengan nama: \"{$namaSales}\" Tidak ditemukan");
                }

                $sales =  $this->model('SalesModels')->cekSalesByKdKaryawan($kdKaryawan);

                if ($sales) {
                    $log->error("Validasi gagal untuk input nama_sales", [
                        'invalid_input' => $sales,
                        'expected_format' => 'SALES SUDAH DIDAFTARKAN'
                    ]);

                    throw new \Exception("Sales sudah dengan nama: \"{$namaSales}\". Sudah Terdaftar");
                }

                $result = $this->model('SalesModels')->simpanSales($inputData);

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di validasiSimpanSales');
            }
        } catch (\Throwable $th) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
            exit;
        }
    }
}
