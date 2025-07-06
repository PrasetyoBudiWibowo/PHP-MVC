<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Carbon\Carbon;

use App\Models\SumberInformasiBukuTamu;
use App\Models\SumberInformasiDetailBukuTamu;
use App\Models\AlasanKunjunganBukuTamu;
use App\Models\BukuTamu;
use App\Models\Provinsi;
use App\Models\KotaKabupaten;
use App\Models\Kecamatan;
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

    private function generatedKdAlasanKunjunganBukuTamu()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'AKBT-' . $currentMonth . '-';

        $lastAlasanKunjungan = AlasanKunjunganBukuTamu::where('kd_alasan_kunjungan_buku_tamu', 'LIKE', $prefix . '%')
            ->orderBy('kd_alasan_kunjungan_buku_tamu', 'DESC')
            ->first();

        if (!$lastAlasanKunjungan) {
            return $prefix . '0000';
        }

        $lastCode = $lastAlasanKunjungan->kd_alasan_kunjungan_buku_tamu;
        $lastNumber = substr($lastCode, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    private function generateKdBukuTamu()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'AKBT-' . $currentMonth . '-';

        $lastKunjunganBukuTamu = BukuTamu::where('kd_buku_tamu', 'LIKE', $prefix . '%')
            ->orderBy('kd_buku_tamu', 'DESC')
            ->first();

        if (!$lastKunjunganBukuTamu) {
            return $prefix . '0000';
        }

        $lastCode = $lastKunjunganBukuTamu->kd_buku_tamu;
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

    public function getAllAlasanKunjungan()
    {
        $data = AlasanKunjunganBukuTamu::all();
        return $data;
    }

    public function getAllKunjunganBukuTamu()
    {
        $data = BukuTamu::with('alasan_kunjungan')
            ->with('sales')
            ->with('provinsi')
            ->with('kota_kabupaten')
            ->with('kecamatan')
            ->with('sumber_informasi_buku_tamu')
            ->with('sumber_informasi_detail_buku_tamu')
            ->get();

        $result = $data->map(function ($d) {
            return [
                'kd_buku_tamu' => $d->kd_buku_tamu,
                'kd_buku_tamu_awal' => $d->kd_buku_tamu_awal,
                'nama_pengunjung' => $d->nama_pengunjung,
                'status_kunjungan' => $d->status_kunjungan,
                'kd_alasan_kunjungan_buku_tamu' => $d->kd_alasan_kunjungan_buku_tamu,
                'kd_sumber_informasi_buku_tamu' => $d->kd_sumber_informasi_buku_tamu,
                'kd_sumber_informasi_detail_buku_tamu' => $d->kd_sumber_informasi_detail_buku_tamu,
                'tgl_kunjungan' => $d->tgl_kunjungan,
                'bln_kunjungan' => $d->bln_kunjungan,
                'thn_kunjungan' => $d->thn_kunjungan,
                'waktu_kunjungan' => $d->waktu_kunjungan,
                'kd_master_sales' => $d->kd_master_sales,
                'kd_provinsi' => $d->kd_provinsi,
                'kd_kota_kabupaten' => $d->kd_kota_kabupaten,
                'kd_kecamatan' => $d->kd_kecamatan,
                'sales' => [
                    'kd_karyawan' => $d->sales->kd_karyawan,
                    'karyawan' => [
                        'nama_karyawan' => $d->sales->karyawan->nama_karyawan,
                        'nama_panggilan_karyawan' => $d->sales->karyawan->nama_panggilan_karyawan,
                    ],
                ],
                'alasan_kunjungan' => [
                    'nama_alasan_kunjungan' => $d->alasan_kunjungan->nama_alasan_kunjungan
                ],
                'sumber_informasi' => [
                    'nm_sumber_informasi' => $d->sumber_informasi_buku_tamu->nm_sumber_informasi ?? null
                ],
                'provinsi' => [
                    'nama_provinsi' => $d->provinsi->nama_provinsi,
                ],
                'kota_kabupaten' => [
                    'nama_kota_kabupaten' => $d->kota_kabupaten->nama_kota_kabupaten ?? null,
                ],
                'kecamatan' => [
                    'nama_kecamatan' => $d->kecamatan->nama_kecamatan ?? null,
                ],
                'sumber_informasi_buku_tamu' => [
                    'nm_sumber_informasi' => $d->sumber_informasi_buku_tamu->nm_sumber_informasi ?? null,
                ],
                'sumber_informasi_detail_buku_tamu' => [
                    'nm_sumber_informasi_detail' => $d->sumber_informasi_detail_buku_tamu->nm_sumber_informasi_detail ?? null,
                ],
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

    public function simpanAlasanKunjungan($data)
    {
        Capsule::beginTransaction();
        $log = AppLogger::getLogger('SIMPAN ALASAN KUNJUNGAN BUKU TAMU');

        try {
            $log->info("<================= MULAI PROSES SIMPAN DATA KE DATABASE =================>");
            $log->info("Data dari controller: " . json_encode($data));

            $cekNamaAlasanKunjungan = AlasanKunjunganBukuTamu::where('nama_alasan_kunjungan', $data['nama_alasan_kunjungan'])->first();

            if ($cekNamaAlasanKunjungan) {
                $log->error("Validasi gagal untuk input nama_alasan_kunjungan", [
                    'invalid_input' => $cekNamaAlasanKunjungan,
                    'expected_format' => 'NAMA ALASAN KUNJUNGAN SUDAH ADA'
                ]);
                throw new \Exception("NAMA ALASAN KUNJUNGAN DENGAN NAMA: \"{$data['nama_alasan_kunjungan']}\" SUDAH ADA");
            }

            $kd_alasan_kunjungan_buku_tamu = $this->generatedKdAlasanKunjunganBukuTamu();
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

            $alasaKunjungan = new AlasanKunjunganBukuTamu();
            $alasaKunjungan->kd_alasan_kunjungan_buku_tamu = $kd_alasan_kunjungan_buku_tamu;
            $alasaKunjungan->nama_alasan_kunjungan = $data['nama_alasan_kunjungan'];
            $alasaKunjungan->tampil_buku_tamu = 'YA';
            $alasaKunjungan->user_input = $data['kd_user'];
            $alasaKunjungan->tgl_input = $tgl_input;
            $alasaKunjungan->bln_input = $bln_input;
            $alasaKunjungan->thn_input = $thn_input;
            $alasaKunjungan->waktu_input = $waktu_input;
            $alasaKunjungan->device = $device;
            $alasaKunjungan->alamat_device = $ipDevice;
            $alasaKunjungan->type_device = $deviceType;

            $log->info("<================= PROSES SIMPAN DATA KE DATABASE BERHASIL =================>");
            $alasaKunjungan->save();
            $log->info("<================= DATA BERHASIL TERSIMPAN KE DATABASE =================>");

            Capsule::commit();

            return $alasaKunjungan;
        } catch (\Throwable $th) {
            Capsule::rollBack();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
            exit;
        }
    }

    public function ubahAlasanKunjungan($data)
    {
        Capsule::beginTransaction();
        $log = AppLogger::getLogger('UBAH ALASAN KUNJUNGAN BUKU TAMU');

        try {
            $log->info("<================= MULAI PROSES UBAH DATA KE DATABASE =================>");
            $log->info("Data dari controller: " . json_encode($data));

            $cekNamaAlasanKunjungan = AlasanKunjunganBukuTamu::where('nama_alasan_kunjungan', $data['ubah_nama_alasan_kunjungan'])->first();

            if ($cekNamaAlasanKunjungan) {
                $log->error("Validasi gagal untuk input nama_alasan_kunjungan", [
                    'invalid_input' => $data['ubah_nama_alasan_kunjungan'],
                    'expected_format' => 'NAMA ALASAN KUNJUNGAN SUDAH ADA'
                ]);

                throw new \Exception("NAMA ALASAN KUNJUNGAN YANG INGIN DISIMPAN: \"{$data['ubah_nama_alasan_kunjungan']}\" " .
                    "SAMA DENGAN NAMA YANG SUDAH ADA: \"{$cekNamaAlasanKunjungan->nama_alasan_kunjungan}\"");
            }

            $alasaKunjungan = AlasanKunjunganBukuTamu::find($data['kd_alasan_kunjungan_buku_tamu']);

            if ($alasaKunjungan) {
                $alasaKunjungan->update([
                    'nama_alasan_kunjungan' => $data['ubah_nama_alasan_kunjungan'],
                    'tampil_buku_tamu' => $data['tampil_buku_tamu']
                ]);
            } else {
                $log->info("gagal proses ubah data di database");
                throw new \Exception("Ubah data gagal.");
            }

            $log->info("data di database AlasanKunjunganBukuTamu berhasil di ubah");

            Capsule::commit();
            $log->info("proses ubah data di database berhasil di simpan");
            $log->info("<========================== SELESAI ==========================>");

            return $alasaKunjungan;
        } catch (\Throwable $th) {
            Capsule::rollBack();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
            exit;
        }
    }

    public function simpanPengunjungBaruBukuTamu($data)
    {
        Capsule::beginTransaction();
        $log = AppLogger::getLogger('SIMPAN KUNJUNGAN BARU BUKU TAMU');

        try {
            $log->info("<================= MULAI PROSES UBAH DATA KE DATABASE =================>");
            $log->info("Data dari controller: " . json_encode($data));

            $cekProvinsi = Provinsi::find($data['kd_provinsi']);
            $cekKotaKabupaten = KotaKabupaten::find($data['kd_kota_kabupaten']);

            if (!$cekProvinsi) {
                $log->error("Validasi gagal untuk Provinsi", [
                    'invalid_input' => 'PROVINSI',
                    'expected_format' => 'PROVINSI TIDAK DITEMUKAN'
                ]);

                throw new \Exception("PROVINSI TIDAK DITEMUKAN");
            }

            if (!$cekKotaKabupaten) {
                $log->error("Validasi gagal untuk KotaKabupaten", [
                    'invalid_input' => 'KOTA / KABUPATEN',
                    'expected_format' => 'KOTA / KABUPATEN TIDAK DITEMUKAN'
                ]);

                throw new \Exception("KOTA / KABUPATEN TIDAK DITEMUKAN");
            }

            if (!empty($data['kd_kecamatan'])) {
                $cekKecamatan = Kecamatan::find($data['kd_kecamatan']);

                if (!$cekKecamatan) {
                    $log->error("Validasi gagal untuk Kecamatan", [
                        'invalid_input' => 'KECAMATAN',
                        'expected_format' => 'KECAMATAN TIDAK DITEMUKAN'
                    ]);

                    throw new \Exception("KECAMATAN TIDAK DITEMUKAN");
                }
            }

            $cekAlasanKunjungan = AlasanKunjunganBukuTamu::find($data['kd_alasan_kunjungan_buku_tamu']);

            if (!$cekAlasanKunjungan) {
                $log->error("Validasi gagal untuk AlasanKunjunganBukuTamu", [
                    'invalid_input' => 'ALASAN KUNJUNGAN',
                    'expected_format' => 'ALASAN KUNJUNGAN TIDAK DITEMUKAN'
                ]);

                throw new \Exception("ALASAN KUNJUNGAN TIDAK DITEMUKAN");
            }

            if (!empty($data['kd_sumber_informasi_buku_tamu'])) {

                $cekSumberInformasi = SumberInformasiBukuTamu::find($data['kd_sumber_informasi_buku_tamu']);

                if (!$cekSumberInformasi) {
                    $log->error("Validasi gagal untuk SumberInformasiBukuTamu", [
                        'invalid_input' => 'SUMBER INFORMASI',
                        'expected_format' => 'SUMBER INFORMASI TIDAK DITEMUKAN'
                    ]);

                    throw new \Exception("SUMBER INFORMASI TIDAK DITEMUKAN");
                }
            }

            if (!empty($data['kd_sumber_informasi_detail_buku_tamu'])) {

                $cekSumberInformasiDetail = SumberInformasiDetailBukuTamu::find($data['kd_sumber_informasi_detail_buku_tamu']);

                if (!$cekSumberInformasiDetail) {
                    $log->error("Validasi gagal untuk input SumberInformasiDetailBukuTamu", [
                        'invalid_input' => 'SUMBER INFORMASI DETAIL',
                        'expected_format' => 'SUMBER INFORMASI DETAIL TIDAK DITEMUKAN'
                    ]);

                    throw new \Exception("SUMBER INFORMASI DETAIL TIDAK DITEMUKAN");
                }
            }

            $kd_buku_tamu = $this->generateKdBukuTamu();
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

            $kunjunganBaruBukutamu = new BukuTamu();
            $kunjunganBaruBukutamu->kd_buku_tamu = $kd_buku_tamu;
            $kunjunganBaruBukutamu->nama_pengunjung = $data['nama_pengunjung'];
            $kunjunganBaruBukutamu->kd_master_sales = $data['kd_master_sales'];
            $kunjunganBaruBukutamu->status_kunjungan = "BARU";
            $kunjunganBaruBukutamu->kd_provinsi = $data['kd_provinsi'];
            $kunjunganBaruBukutamu->kd_kota_kabupaten = $data['kd_kota_kabupaten'];
            $kunjunganBaruBukutamu->kd_kecamatan = $data['kd_kecamatan'];
            $kunjunganBaruBukutamu->kd_alasan_kunjungan_buku_tamu = $data['kd_alasan_kunjungan_buku_tamu'];
            $kunjunganBaruBukutamu->alasan_kunjungan_detail = $data['alasan_kunjungan_detail'];
            $kunjunganBaruBukutamu->kd_sumber_informasi_buku_tamu = $data['kd_sumber_informasi_buku_tamu'];
            $kunjunganBaruBukutamu->detail_sumber_informasi = $data['detail_sumber_informasi'];
            $kunjunganBaruBukutamu->kd_sumber_informasi_detail_buku_tamu = $data['kd_sumber_informasi_detail_buku_tamu'];
            $kunjunganBaruBukutamu->tgl_kunjungan = $data['tgl_kunjungan'];
            $kunjunganBaruBukutamu->bln_kunjungan = $data['bln_kunjungan'];
            $kunjunganBaruBukutamu->thn_kunjungan = $data['thn_kunjungan'];
            $kunjunganBaruBukutamu->waktu_kunjungan = $data['waktu_kunjungan'];
            $kunjunganBaruBukutamu->note = $data['note'];
            $kunjunganBaruBukutamu->user_input = $data['kd_user'];
            $kunjunganBaruBukutamu->tgl_input = $tgl_input;
            $kunjunganBaruBukutamu->bln_input = $bln_input;
            $kunjunganBaruBukutamu->thn_input = $thn_input;
            $kunjunganBaruBukutamu->waktu_input = $waktu_input;
            $kunjunganBaruBukutamu->device = $device;
            $kunjunganBaruBukutamu->alamat_device = $ipDevice;
            $kunjunganBaruBukutamu->type_device = $deviceType;

            $log->info("<================= PROSES SIMPAN DATA KE DATABASE BERHASIL =================>");
            $kunjunganBaruBukutamu->save();
            $log->info("<================= DATA BERHASIL TERSIMPAN KE DATABASE =================>");

            Capsule::commit();

            return $kunjunganBaruBukutamu;
        } catch (\Throwable $th) {
            Capsule::rollBack();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
            exit;
        }
    }


    // data dummy
    public function simpanDummyPengunjungBukuTamu($data)
    {
        Capsule::beginTransaction();
        $log = AppLogger::getLogger('SIMPAN DUMMY KUNJUNGAN BUKU TAMU');

        try {
            $log->info("<================= MULAI PROSES SIMPAN DATA KE DATABASE =================>");
            $log->info("Data dari controller::");


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

            $simpanDummyKunjunganBukuTamu = [];

            foreach ($data as $d) {

                if ($d['thn_kunjungan'] !== "0000") {
                    $cekProvinsi = Provinsi::find($d['kd_provinsi']);
                    $cekKotaKabupaten = KotaKabupaten::find($d['kd_kota_kabupaten']);

                    if (!$cekProvinsi) {
                        $log->error("Validasi gagal untuk Provinsi", [
                            'invalid_input' => 'PROVINSI',
                            'expected_format' => 'PROVINSI TIDAK DITEMUKAN'
                        ]);

                        throw new \Exception("PROVINSI TIDAK DITEMUKAN");
                    }

                    if (!$cekKotaKabupaten) {
                        $log->error("Validasi gagal untuk KotaKabupaten", [
                            'invalid_input' => 'KOTA / KABUPATEN',
                            'expected_format' => 'KOTA / KABUPATEN TIDAK DITEMUKAN'
                        ]);

                        throw new \Exception("KOTA / KABUPATEN TIDAK DITEMUKAN");
                    }

                    if (!empty($d['kd_kecamatan'])) {
                        $cekKecamatan = Kecamatan::find($d['kd_kecamatan']);

                        if (!$cekKecamatan) {
                            $log->error("Validasi gagal untuk Kecamatan", [
                                'invalid_input' => 'KECAMATAN',
                                'expected_format' => 'KECAMATAN TIDAK DITEMUKAN'
                            ]);

                            throw new \Exception("KECAMATAN TIDAK DITEMUKAN");
                        }
                    }

                    $cekAlasanKunjungan = AlasanKunjunganBukuTamu::find($d['kd_alasan_kunjungan_buku_tamu']);

                    if (!$cekAlasanKunjungan) {
                        $log->error("Validasi gagal untuk AlasanKunjunganBukuTamu", [
                            'invalid_input' => 'ALASAN KUNJUNGAN',
                            'expected_format' => 'ALASAN KUNJUNGAN TIDAK DITEMUKAN'
                        ]);

                        throw new \Exception("ALASAN KUNJUNGAN TIDAK DITEMUKAN");
                    }

                    if (!empty($d['kd_sumber_informasi_buku_tamu'])) {

                        $cekSumberInformasi = SumberInformasiBukuTamu::find($d['kd_sumber_informasi_buku_tamu']);

                        if (!$cekSumberInformasi) {
                            $log->error("Validasi gagal untuk SumberInformasiBukuTamu", [
                                'invalid_input' => 'SUMBER INFORMASI',
                                'expected_format' => 'SUMBER INFORMASI TIDAK DITEMUKAN'
                            ]);

                            throw new \Exception("SUMBER INFORMASI TIDAK DITEMUKAN");
                        }
                    }

                    if (!empty($d['kd_sumber_informasi_detail_buku_tamu'])) {

                        $cekSumberInformasiDetail = SumberInformasiDetailBukuTamu::find($d['kd_sumber_informasi_detail_buku_tamu']);

                        if (!$cekSumberInformasiDetail) {
                            $log->error("Validasi gagal untuk input SumberInformasiDetailBukuTamu", [
                                'invalid_input' => 'SUMBER INFORMASI DETAIL',
                                'expected_format' => 'SUMBER INFORMASI DETAIL TIDAK DITEMUKAN'
                            ]);

                            throw new \Exception("SUMBER INFORMASI DETAIL TIDAK DITEMUKAN");
                        }
                    }

                    $kd_buku_tamu = $this->generateKdBukuTamu();

                    $kunjunganBaruBukutamu = new BukuTamu();
                    $kunjunganBaruBukutamu->kd_buku_tamu = $kd_buku_tamu;
                    $kunjunganBaruBukutamu->nama_pengunjung = $d['nama_pengunjung'];
                    $kunjunganBaruBukutamu->kd_master_sales = $d['kd_master_sales'];
                    $kunjunganBaruBukutamu->status_kunjungan = "BARU";
                    $kunjunganBaruBukutamu->kd_provinsi = $d['kd_provinsi'];
                    $kunjunganBaruBukutamu->kd_kota_kabupaten = $d['kd_kota_kabupaten'];
                    $kunjunganBaruBukutamu->kd_kecamatan = $d['kd_kecamatan'];
                    $kunjunganBaruBukutamu->kd_alasan_kunjungan_buku_tamu = $d['kd_alasan_kunjungan_buku_tamu'];
                    $kunjunganBaruBukutamu->kd_sumber_informasi_buku_tamu = $d['kd_sumber_informasi_buku_tamu'];
                    $kunjunganBaruBukutamu->kd_sumber_informasi_detail_buku_tamu = $d['kd_sumber_informasi_detail_buku_tamu'];
                    $kunjunganBaruBukutamu->tgl_kunjungan = $d['tgl_kunjungan'];
                    $kunjunganBaruBukutamu->bln_kunjungan = $d['bln_kunjungan'];
                    $kunjunganBaruBukutamu->thn_kunjungan = $d['thn_kunjungan'];
                    $kunjunganBaruBukutamu->waktu_kunjungan = $d['waktu_kunjungan'];
                    $kunjunganBaruBukutamu->user_input = $d['kd_user'];
                    $kunjunganBaruBukutamu->tgl_input = $tgl_input;
                    $kunjunganBaruBukutamu->bln_input = $bln_input;
                    $kunjunganBaruBukutamu->thn_input = $thn_input;
                    $kunjunganBaruBukutamu->waktu_input = $waktu_input;
                    $kunjunganBaruBukutamu->device = $device;
                    $kunjunganBaruBukutamu->alamat_device = $ipDevice;
                    $kunjunganBaruBukutamu->type_device = $deviceType;

                    $kunjunganBaruBukutamu->save();

                    if (!$kunjunganBaruBukutamu) {
                        throw new Exception("Gagal simpan karyawan.");
                    }

                    $simpanDummyKunjunganBukuTamu[] = $kunjunganBaruBukutamu;
                }
            }

            $log->info("<================= PROSES SIMPAN DATA KE DATABASE BERHASIL =================>");
            $log->info("<================= DATA BERHASIL TERSIMPAN KE DATABASE =================>");

            Capsule::commit();
            return $simpanDummyKunjunganBukuTamu;
        } catch (\Throwable $th) {
            Capsule::rollBack();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
            exit;
        }
    }
}
