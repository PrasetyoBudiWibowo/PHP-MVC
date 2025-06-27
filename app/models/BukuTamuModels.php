<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Carbon\Carbon;

use App\Models\SumberInformasiBukuTamu;
use App\Models\SumberInformasiDetailBukuTamu;
use App\Helper\DeviceHelper;
use App\Helper\GeoDetector;
use APp\Helper\AppLogger;

use Exception;

use App\Core\Database;

class BukuTamuModels
{
    private $db;

    use HasFactory;

    public function __construct()
    {
        $this->db = new Database;
    }

    private function generateKdSumberInformasi()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'KSI-' . $currentMonth . '-';

        $lastSumberInformasi = SumberInformasiBukuTamu::where('kd_sumber_informasi_buku_tamu', 'LIKE', $prefix . '%')
            ->orderBy('kd_sumber_informasi_buku_tamu', 'DESC')
            ->first();

        if (!$lastSumberInformasi) {
            return $prefix . '0000';
        }

        $lastCode = $lastSumberInformasi->kd_sumber_informasi_buku_tamu;
        $lastNumber = substr($lastCode, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    private function generateKdSumberInformasiDetail()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'KSID-' . $currentMonth . '-';

        $lastSumberInformasiDetail = SumberInformasiDetailBukuTamu::where('kd_sumber_informasi_detail_buku_tamu', 'LIKE', $prefix . '%')
            ->orderBy('kd_sumber_informasi_detail_buku_tamu', 'DESC')
            ->first();

        if (!$lastSumberInformasiDetail) {
            return $prefix . '0000';
        }

        $lastCode = $lastSumberInformasiDetail->kd_sumber_informasi_detail_buku_tamu;
        $lastNumber = substr($lastCode, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    public function getAllSumberInfromasi()
    {
        $data = SumberInformasiBukuTamu::all();
        return $data;
    }

    public function getAllSumberInformasiDetail()
    {
        $data = SumberInformasiDetailBukuTamu::with('SumberInformasiBukuTamu')->get();

        $result = $data->map(function ($d) {
            return [
                'kd_sumber_informasi_detail_buku_tamu' => $d->kd_sumber_informasi_detail_buku_tamu,
                'kd_sumber_informasi_buku_tamu' => $d->kd_sumber_informasi_buku_tamu,
                'nm_sumber_informasi_detail' => $d->nm_sumber_informasi_detail,
                'tampil_buku_tamu' => $d->tampil_buku_tamu,
                'sumber_informasi' => [
                    'nm_sumber_informasi' => $d->SumberInformasiBukuTamu->nm_sumber_informasi,
                    'tampil_buku_tamu' => $d->SumberInformasiBukuTamu->tampil_buku_tamu,
                ]
            ];
        });

        return $result;

    }

    public function cekSumberInformasiByKode($kdSumberInforamsi)
    {
        $result = SumberInformasiBukuTamu::where('kd_sumber_informasi_buku_tamu', '=', $kdSumberInforamsi)->first();
        return $result;
    }

    public function cekSumberInformasiDetailByKode($kdSumberInforamsiDetail)
    {
        $result = SumberInformasiDetailBukuTamu::where('kd_sumber_informasi_detail_buku_tamu', '=', $kdSumberInforamsiDetail)->first();
        return $result;
    }

    public function simpanSumberInformasi($data)
    {
        Capsule::beginTransaction();
        $log = AppLogger::getLogger('sumberinfomrasi');

        try {
            $log->info("mulai proses simpan ke database");
            $log->info("Data dari controller: " . json_encode($data));

            $kd_sumber_informasi_buku_tamu = $this->generateKdSumberInformasi();
            $log->info("berhasil buat code PK: $kd_sumber_informasi_buku_tamu");

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

            $sumberInformasiBukuTamu = new SumberInformasiBukuTamu();
            $sumberInformasiBukuTamu->kd_sumber_informasi_buku_tamu = $kd_sumber_informasi_buku_tamu;
            $sumberInformasiBukuTamu->nm_sumber_informasi = $data['nm_sumber_informasi'];
            $sumberInformasiBukuTamu->tampil_buku_tamu = 'YA';
            $sumberInformasiBukuTamu->user_input = $data['kd_user'];
            $sumberInformasiBukuTamu->tgl_input = $tgl_input;
            $sumberInformasiBukuTamu->bln_input = $bln_input;
            $sumberInformasiBukuTamu->thn_input = $thn_input;
            $sumberInformasiBukuTamu->waktu_input = $waktu_input;
            $sumberInformasiBukuTamu->device = $device;
            $sumberInformasiBukuTamu->alamat_device = $ipDevice;
            $sumberInformasiBukuTamu->type_device = $deviceType;

            $sumberInformasiBukuTamu->save();
            $log->info("data yang tersimpan $sumberInformasiBukuTamu");

            Capsule::commit();
            $log->info("berhasil simpan ke database");

            return $sumberInformasiBukuTamu;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses simpan Depaetement: " . $e->getMessage());
        }
    }

    public function ubahSumberInformasi($data)
    {
        Capsule::beginTransaction();
        $log = AppLogger::getLogger('UbahDataSumberInformasi');

        try {
            $log->info("mulai proses ubah data di database");

            $sumberInforamsi = SumberInformasiBukuTamu::find($data['kd_sumber_informasi_buku_tamu']);

            if ($sumberInforamsi) {
                $sumberInforamsi->update([
                    'nm_sumber_informasi' => $data['nm_sumber_informasi'],
                    'tampil_buku_tamu' => $data['tampil_buku_tamu'],
                ]);
            } else {
                $log->info("gagal proses ubah data di database");
                throw new \Exception("Tipe update tidak valid di ubahDepartement.");
            }

            $log->info("data di database SumberInformasiBukuTamu berhasil di ubah");

            Capsule::commit();
            $log->info("proses ubah data di database SumberInformasiBukuTamu berhasil di simpan");
            $log->info("<========================== MULAI SELESAI ==========================>");

            return $sumberInforamsi;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses simpan ubahSumberInformasi: " . $e->getMessage());
        }
    }

    public function simpanSumberInformasiDetail($data)
    {
        Capsule::beginTransaction();
        $log = AppLogger::getLogger('SUMBER-INFORMASI-DETAIL');

        try {
            $log->info("<================= MULAI PROSES SIMPAN DATA KE DATABASE =================>");
            $log->info("Data dari controller: " . json_encode($data));

            $kd_sumber_informasi_detail_buku_tamu = $this->generateKdSumberInformasiDetail();
            $log->info("berhasil buat code PK: $kd_sumber_informasi_detail_buku_tamu");

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

            $sumberInformasiDetailBukuTamu = new SumberInformasiDetailBukuTamu();
            $sumberInformasiDetailBukuTamu->kd_sumber_informasi_detail_buku_tamu = $kd_sumber_informasi_detail_buku_tamu;
            $sumberInformasiDetailBukuTamu->kd_sumber_informasi_buku_tamu = $data['kd_sumber_informasi_buku_tamu'];
            $sumberInformasiDetailBukuTamu->nm_sumber_informasi_detail = $data['nm_sumber_informasi_detail'];
            $sumberInformasiDetailBukuTamu->tampil_buku_tamu = "YA";
            $sumberInformasiDetailBukuTamu->user_input = $data['kd_user'];
            $sumberInformasiDetailBukuTamu->tgl_input = $tgl_input;
            $sumberInformasiDetailBukuTamu->bln_input = $bln_input;
            $sumberInformasiDetailBukuTamu->thn_input = $thn_input;
            $sumberInformasiDetailBukuTamu->waktu_input = $waktu_input;
            $sumberInformasiDetailBukuTamu->device = $device;
            $sumberInformasiDetailBukuTamu->alamat_device = $ipDevice;
            $sumberInformasiDetailBukuTamu->type_device = $deviceType;

            $log->info("<================= PROSES SIMPAN DATA KE DATABASE BERHASIL =================>");

            $sumberInformasiDetailBukuTamu->save();
            $log->info("<================= DATA BERSHASIL TESIMPAN KE DATABASE =================>");

            Capsule::commit();

            return $sumberInformasiDetailBukuTamu;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses simpan Depaetement: " . $e->getMessage());
        }
    }

    public function ubahSumberInformasiDetail($data)
    {
        Capsule::beginTransaction();
        $log = AppLogger::getLogger('UbahDataSumberInformasiDetail');

        try {
            $log->info("mulai proses ubah data di database");

            $sumberInforamsiDetail = SumberInformasiDetailBukuTamu::find($data['kd_sumber_informasi_detail_buku_tamu']);

            if ($sumberInforamsiDetail) {
                $sumberInforamsiDetail->update([
                    'nm_sumber_informasi_detail' => $data['nm_sumber_informasi_detail'],
                    'tampil_buku_tamu' => $data['tampil_buku_tamu'],
                ]);
            } else {
                $log->info("gagal proses ubah data di database");
                throw new \Exception("Tipe update tidak valid di ubahDepartement.");
            }

            $log->info("data di database SumberInformasiBukuTamu berhasil di ubah");

            Capsule::commit();
            $log->info("proses ubah data di database SumberInformasiBukuTamuDetail berhasil di simpan");
            $log->info("<========================== MULAI SELESAI ==========================>");

            return $sumberInforamsiDetail;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses simpan ubahSumberInformasi: " . $e->getMessage());
        }
    }
}
