<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Carbon\Carbon;

use App\Models\SpvSales;
use App\Models\Karyawan;
use App\Models\Posisition;
use App\Helper\DeviceHelper;
use App\Helper\GeoDetector;
use APp\Helper\AppLogger;

use Exception;

use App\Core\Database;

class SalesModels
{
    private $db;

    use HasFactory;

    public function __construct()
    {
        $this->db = new Database;
    }

    private function generateKdSpvSales()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'SPVSL-' . $currentMonth . '-';

        $lastSpvSales = SpvSales::where('kd_spv_sales', 'LIKE', $prefix . '%')
            ->orderBy('kd_spv_sales', 'DESC')
            ->first();

        if (!$lastSpvSales) {
            return $prefix . '0000';
        }

        $lastCode = $lastSpvSales->kd_spv_sales;
        $lastNumber = substr($lastCode, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    public function getAllSpvSales()
    {
        $data = SpvSales::with('Karyawan')->get();

        $result = $data->map(function ($d) {
            return [
                'kd_spv_sales' => $d->kd_spv_sales,
                'kd_karyawan' => $d->kd_karyawan,
                'status_spv_sales' => $d->status_spv_sales,
                'karyawan' => [
                    'nama_karyawan' => $d->karyawan->nama_karyawan,
                    'nama_panggilan_karyawan' => $d->karyawan->nama_panggilan_karyawan,
                ]
            ];
        });

        return $result;
    }

    public function cekSpvSalesBySpvSales($kdSpvSales)
    {
        $result = SpvSales::where('kd_spv_sales', '=', $kdSpvSales)->first();
        return $result;
    }

    public function cekSpvSalesByKdKaryawan($kdKaryawan)
    {
        $result = SpvSales::where('kd_karyawan', '=', $kdKaryawan)->first();
        return $result;
    }

    public function simpanSpvSales($data)
    {
        Capsule::beginTransaction();
        $log = AppLogger::getLogger('SPV-SALES');

        try {
            $log->info("<================= MULAI PROSES SIMPAN DATA KE DATABASE =================>");
            $log->info("Data dari controller: " . json_encode($data));

            $namaSpvSales = SpvSales::where('kd_karyawan', $data['kd_karyawan'])->first();
            if ($namaSpvSales) {
                throw new \Exception("NAMA SPV: \"{$data['nama_spv_sales']}\". SUDAH ADA ATAU TERDAFTAR");
            }

            $karyawan = Karyawan::find($data['kd_karyawan']);
            if (!$karyawan) {
                $log->error("Karyawan tidak ditemukan", [
                    'invalid_input' => $karyawan,
                    'expected_format' => 'KARYAWAN TIDAK DI TEMUKAN'
                ]);
                throw new \Exception("Karyawan tidak ditemukan");
            }

            $posisition = Posisition::find($data['kd_position']);
            if (!$posisition) {
                $log->error("Validasi gagal untuk input nama_karyawan", [
                    'invalid_input' => $posisition,
                    'expected_format' => 'POSISI TIDAK DI TEMUKAN'
                ]);
                throw new \Exception("Posistion tidak ditemukan");
            }

            $updateSuccess = $karyawan->update([
                'kd_divisi' => $posisition->kd_divisi,
                'kd_departement' => $posisition->kd_departement,
                'kd_position' => $posisition->kd_position,
                'daftar_spv_sales' => "YA",
            ]);

            if (!$updateSuccess) {
                $log->error("Gagal mengupdate data karyawan", [
                    'invalid_input' => $updateSuccess,
                    'expected_format' => 'GAGAL UPDATA KARYAWAN'
                ]);
                throw new \Exception("Gagal mengupdate data karyawan.");
            }

            $kd_spv_sales = $this->generateKdSpvSales();
            $log->info("berhasil buat code PK");

            $tgl_input = Carbon::now()->toDateString();
            $bln_input = Carbon::now()->format('m');
            $thn_input = Carbon::now()->year;
            $waktu_input = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i');

            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $deviceInfo = DeviceHelper::detectDevice($userAgent);
            $deviceType = $deviceInfo['deviceType'];
            $device = $deviceInfo['browser'];

            $ipDetector = GeoDetector::getDeviceLocation();
            $ipDevice = isset($ipDetector['ip']) ? $ipDetector['ip'] : 'Unknown IP';

            $SpvSales = new SpvSales();
            $SpvSales->kd_spv_sales = $kd_spv_sales;
            $SpvSales->kd_karyawan = $data['kd_karyawan'];
            $SpvSales->status_spv_sales = 'ACTIVE';
            $SpvSales->user_input = $data['kd_user'];
            $SpvSales->tgl_input = $tgl_input;
            $SpvSales->bln_input = $bln_input;
            $SpvSales->thn_input = $thn_input;
            $SpvSales->waktu_input = $waktu_input;
            $SpvSales->device = $device;
            $SpvSales->alamat_device = $ipDevice;
            $SpvSales->type_device = $deviceType;

            $log->info("<================= PROSES SIMPAN DATA KE DATABASE BERHASIL =================>");
            $SpvSales->save();
            $log->info("<================= DATA BERHASIL TERSIMPAN KE DATABASE =================>");

            Capsule::commit();

            return $SpvSales;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses simpan SpvSales: " . $e->getMessage());
        }
    }

    public function ubahSpvSales($data)
    {
        Capsule::beginTransaction();
        $log = AppLogger::getLogger('UBAH-SPV-SALES');

        try {
            $log->info("<================= MULAI PROSES UBAH DATA KE DATABASE =================>");
            $log->info("Data dari controller: " . json_encode($data));

            $spvSales = SpvSales::find($data['kd_spv_sales']);

            if ($spvSales) {
                $spvSales->update([
                    'status_spv_sales' => $data['status_spv_sales'],
                ]);
            } else {
                $log->info("gagal proses ubah data di database");
                throw new \Exception("update gagal di ubahSpvSales.");
            }
            

            $log->info("data di database SumberInformasiBukuTamu berhasil di ubah");

            Capsule::commit();
            $log->info("proses ubah data di database berhasil di simpan");
            $log->info("<========================== SELESAI ==========================>");

            return $spvSales;
        } catch (\Throwable $th) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
