<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Carbon\Carbon;

use App\Models\Provinsi;
use App\Models\KotaKabupaten;
use App\Models\Kecamatan;
use App\Helper\DeviceHelper;
use App\Helper\GeoDetector;

use Exception;

use App\Core\Database;

class WilayahModels
{
    private $db;

    use HasFactory;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function allProvinsi()
    {
        $allProvinsi = Provinsi::where('status_tampil', 'ACTIVE')->get();
        return $allProvinsi;
    }

    public function allKotaKabupaten()
    {
        $allKabupatenKota = KotaKabupaten::where('status_tampil', 'ACTIVE')->get();
        return $allKabupatenKota;
    }

    public function allKecamatan()
    {
        $allKabupatenKota = Kecamatan::where('status_tampil', 'ACTIVE')->get();
        return $allKabupatenKota;
    }

    public function allKotaKabupatenWithProvinsi()
    {
        $kotaKabupaten = KotaKabupaten::where('status_tampil', 'ACTIVE')->with('provinsi')->get();

        $result = $kotaKabupaten->map(function ($kotaKabupaten) {
            return [
                'kd_kota_kabupaten' => $kotaKabupaten->kd_kota_kabupaten,
                'kd_provinsi' => $kotaKabupaten->kd_provinsi,
                'id_kota_kabupaten' => $kotaKabupaten->id_kota_kabupaten,
                'nama_kota_kabupaten' => $kotaKabupaten->nama_kota_kabupaten,
                'status_tampil' => $kotaKabupaten->status_tampil,
                'provinsi' => [
                    'kd_provinsi' => $kotaKabupaten->provinsi->kd_provinsi,
                    'nama_provinsi' => $kotaKabupaten->provinsi->nama_provinsi,
                ],
            ];
        });

        return $result;
    }

    public function allKecamatanWithKabKotaWithProvinsi()
    {

        $kecamatan = Kecamatan::where('status_tampil', 'ACTIVE')
            ->with(['kotaKabupaten' => function ($query) {
                $query->where('status_tampil', 'ACTIVE');
            }])
            ->get();

        $result = $kecamatan->map(function ($kecamatan) {
            return [
                'kd_kecamatan' => $kecamatan->kd_kecamatan,
                'kd_kota_kabupaten' => $kecamatan->kd_kota_kabupaten,
                'nama_kecamatan' => $kecamatan->nama_kecamatan,
                'kota_kabupaten' => [
                    'kd_kota_kabupaten' => $kecamatan->kd_kota_kabupaten,
                    'kd_provinsi' => $kecamatan->kotaKabupaten->kd_provinsi,
                    'nama_kota_kabupaten' => $kecamatan->kotaKabupaten->nama_kota_kabupaten,
                    'provinsi' => [
                        'kd_provinsi' => $kecamatan->kotaKabupaten->kd_provinsi,
                        'nama_provinsi' => $kecamatan->kotaKabupaten->provinsi->nama_provinsi,
                    ]
                ],
            ];
        });

        return $result;
    }

    private function generateKdProvinsi()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'PRV-' . $currentMonth . '-';

        $lastProvinsi = Provinsi::where('kd_provinsi', 'LIKE', $prefix . '%')
            ->orderBy('kd_provinsi', 'DESC')
            ->first();

        if (!$lastProvinsi) {
            return $prefix . '0000';
        }

        $lastId = $lastProvinsi->kd_provinsi;
        $lastNumber = substr($lastId, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    private function generateKdKotaKabupaten()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'KTK-' . $currentMonth . '-';

        $lastKotaKabupaten = KotaKabupaten::where('kd_kota_kabupaten', 'LIKE', $prefix . '%')
            ->orderBy('kd_kota_kabupaten', 'DESC')
            ->first();

        if (!$lastKotaKabupaten) {
            return $prefix . '0000';
        }

        $lastId = $lastKotaKabupaten->kd_kota_kabupaten;
        $lastNumber = substr($lastId, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    private function generateKodeKecamatan()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'KEC-' . $currentMonth . '-';

        $lastKecamatan = Kecamatan::where('kd_kecamatan', 'LIKE', $prefix . '%')
            ->orderBy('kd_kecamatan', 'DESC')
            ->first();

        if (!$lastKecamatan) {
            return $prefix . '00000';
        }

        $lastId = $lastKecamatan->kd_kecamatan;
        $lastNumber = substr($lastId, -5);

        $newNumber = str_pad(intval($lastNumber) + 1, 5, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    public function cekProvinsiByCode($kdProvinsi)
    {
        $result = Provinsi::where('kd_provinsi', $kdProvinsi)->first();
        return $result;
    }

    public function cekNamaProvinsi($namaProvinsi)
    {
        $result = Provinsi::where('nama_provinsi', $namaProvinsi)->first();
        return $result;
    }

    public function cekKotaDanProvinsi($kdKota, $kdProvinsi)
    {
        $kotaDanProvinsi = KotaKabupaten::where('kd_kota_kabupaten', $kdKota)
            ->where('kd_provinsi', $kdProvinsi)
            ->first();
        return $kotaDanProvinsi;
    }

    public function cekKotaByKode($kdKotaKabupaten)
    {
        $result = KotaKabupaten::where('kd_kota_kabupaten', $kdKotaKabupaten)->first();
        return $result;
    }

    public function cekKotaByIdProvinsi($data)
    {
        $provinsi = Provinsi::where('id_provinsi', $data)->value('kd_provinsi');

        return $provinsi ? $provinsi : null;
    }

    public function cekKecamatanaByIdKota($idKota)
    {
        $kota = KotaKabupaten::where('id_kota_kabupaten', $idKota)->value('kd_kota_kabupaten');

        return $kota ? $kota : null;
    }

    public function cekKecamatanByCode($kdKecamatan)
    {
        $result = Kecamatan::where('kd_kecamatan', $kdKecamatan)->first();
        return $result;
    }

    public function simpanProvnisi($data)
    {
        Capsule::beginTransaction();

        try {

            $kd_provinsi = $this->generateKdProvinsi();

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

            $provinsi = new Provinsi();
            $provinsi->kd_provinsi = $kd_provinsi;
            $provinsi->nama_provinsi = $data['nama_provinsi'];
            $provinsi->tgl_input = $tgl_input;
            $provinsi->bln_input = $bln_input;
            $provinsi->thn_input = $thn_input;
            $provinsi->waktu_input = $waktu_input;
            $provinsi->user_input = $data['kd_user'];
            $provinsi->alamat_device = $ipDevice;
            $provinsi->type_device = $deviceType;
            $provinsi->device = $device;

            $provinsi->save();

            Capsule::commit();

            return $provinsi;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses sinmpan provinsi: " . $e->getMessage());
        }
    }

    public function ubahProvinsi($data)
    {
        Capsule::beginTransaction();

        try {

            $provinsi = Provinsi::find($data['kd_provinsi']);

            if ($provinsi) {
                if ($data['type'] === 'nama') {
                    $provinsi->update([
                        'nama_provinsi' => $data['nama_provinsi']
                    ]);
                } elseif ($data['type'] === 'status') {
                    $provinsi->update([
                        'status_tampil' => 'NON ACTIVE'
                    ]);
                } else {
                    throw new \Exception("Tipe update tidak valid.");
                }
            }

            Capsule::commit();

            return $provinsi;
        } catch (\Exception $e) {
            Capsule::rollBack();
            throw new \Exception("Gagal ubah data provinsi: " . $e->getMessage());
        }
    }

    public function simpanKotaKabupaten($data)
    {
        Capsule::beginTransaction();

        try {

            $tgl_input = Carbon::now()->toDateString();
            $bln_input = Carbon::now()->format('m');
            $thn_input = Carbon::now()->year;
            $waktu_input = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i');

            $kd_kota_kabupaten = $this->generateKdKotaKabupaten();

            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $deviceInfo = DeviceHelper::detectDevice($userAgent);
            $deviceType = $deviceInfo['deviceType'];
            $device = $deviceInfo['browser'];

            $ipDetector = GeoDetector::getDeviceLocation();
            $ipDevice = isset($ipDetector['ip']) ? $ipDetector['ip'] : 'Unknown IP';

            $kotaKabupaten = new KotaKabupaten();
            $kotaKabupaten->kd_kota_kabupaten = $kd_kota_kabupaten;
            $kotaKabupaten->kd_provinsi = $data['kd_provinsi'];
            $kotaKabupaten->nama_kota_kabupaten = $data['nama_kota_kabupaten'];
            $kotaKabupaten->status_tampil = 'ACTIVE';
            $kotaKabupaten->tgl_input = $tgl_input;
            $kotaKabupaten->bln_input = $bln_input;
            $kotaKabupaten->thn_input = $thn_input;
            $kotaKabupaten->waktu_input = $waktu_input;
            $kotaKabupaten->user_input = $data['user_input'];
            $kotaKabupaten->alamat_device = $ipDevice;
            $kotaKabupaten->type_device = $deviceType;
            $kotaKabupaten->device = $device;

            $kotaKabupaten->save();

            Capsule::commit();

            return $kotaKabupaten;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses sinmpan provinsi: " . $e->getMessage());
        }
    }

    public function ubahDataKotaKabupaten($data)
    {
        Capsule::beginTransaction();

        try {

            $kotaKabupaten = KotaKabupaten::find($data['kd_kota_kabupaten']);

            if ($kotaKabupaten) {
                if ($data['type'] === 'nama') {
                    $kotaKabupaten->update([
                        'kd_provinsi' => $data['kd_provinsi'],
                        'nama_kota_kabupaten' => $data['nama_kota_kabupaten'],
                    ]);
                } else if ($data['type'] === 'status') {
                    $kotaKabupaten->update([
                        'status_tampil' => 'NON ACTIVE'
                    ]);
                } else {
                    throw new \Exception("Tipe update tidak valid. ubahDataKotaKabupaten");
                }
            } else {
                throw new \Exception("Kota / Kabupten tidak di temukan.");
            }

            Capsule::commit();

            return $kotaKabupaten;
        } catch (\Exception $e) {
            Capsule::rollBack();
            throw new \Exception("Gagal ubah data kota kabupaten: " . $e->getMessage());
        }
    }

    public function simpanKecamatan($data)
    {
        Capsule::beginTransaction();

        try {
            $kdKecamatan = $this->generateKodeKecamatan();

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

            $kecamatan = new Kecamatan();
            $kecamatan->kd_kecamatan = $kdKecamatan;
            $kecamatan->kd_kota_kabupaten = $data['kd_kota_kabupaten'];
            $kecamatan->nama_kecamatan = $data['nama_kecamatan'];
            $kecamatan->status_tampil = 'ACTIVE';
            $kecamatan->tgl_input = $tgl_input;
            $kecamatan->bln_input = $bln_input;
            $kecamatan->thn_input = $thn_input;
            $kecamatan->waktu_input = $waktu_input;
            $kecamatan->user_input = $data['user_input'];
            $kecamatan->alamat_device = $ipDevice;
            $kecamatan->type_device = $deviceType;
            $kecamatan->device = $device;

            $kecamatan->save();

            Capsule::commit();
            return $kecamatan;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses excel kecamatan: " . $e->getMessage());
        }
    }

    public function ubahKecamatan($data)
    {
        Capsule::beginTransaction();

        try {
            $kecamatan = Kecamatan::where('kd_kecamatan', $data['kd_kecamatan'])->first();

            if ($kecamatan) {
                if ($data['type'] === 'UBAH') {
                    $kecamatan->update([
                        'kd_kota_kabupaten' => $data['kd_kota_kabupaten'],
                        'nama_kecamatan' => $data['nama_kecamatan'],
                    ]);
                } else if ($data['type'] === 'HAPUS') {
                    $kecamatan->update([
                        'status_tampil' => 'NON ACTIVE'
                    ]);
                } else {
                    throw new \Exception("Tipe update tidak valid. ubahKecamatan");
                }
            } else {
                throw new \Exception("kecamatan tidak di temukan.");
            }

            Capsule::commit();

            return $kecamatan;
        } catch (\Exception $e) {
            Capsule::rollBack();
            throw new \Exception("Gagal ubah data kecamatan: " . $e->getMessage());
        }
    }

    public function simpanExcelProvinsi($data)
    {
        Capsule::beginTransaction();

        try {

            $tgl_input = Carbon::now()->toDateString();
            $bln_input = Carbon::now()->format('m');
            $thn_input = Carbon::now()->year;
            $waktu_input = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i');

            $savedProvinsi = [];

            foreach ($data as $d) {
                $kd_provinsi = $this->generateKdProvinsi();

                $userAgent = $_SERVER['HTTP_USER_AGENT'];
                $deviceInfo = DeviceHelper::detectDevice($userAgent);
                $deviceType = $deviceInfo['deviceType'];
                $device = $deviceInfo['browser'];

                $ipDetector = GeoDetector::getDeviceLocation();
                $ipDevice = isset($ipDetector['ip']) ? $ipDetector['ip'] : 'Unknown IP';

                $provinsi = new Provinsi();
                $provinsi->kd_provinsi = $kd_provinsi;
                $provinsi->id_provinsi = $d['id_provinsi'];
                $provinsi->nama_provinsi = $d['nama_provinsi'];
                $provinsi->tgl_input = $tgl_input;
                $provinsi->bln_input = $bln_input;
                $provinsi->thn_input = $thn_input;
                $provinsi->waktu_input = $waktu_input;
                $provinsi->user_input = $d['kd_user'];
                $provinsi->alamat_device = $ipDevice;
                $provinsi->type_device = $deviceType;
                $provinsi->device = $device;

                $provinsi->save();

                $savedProvinsi[] = $provinsi;
            }

            Capsule::commit();

            return $savedProvinsi;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses excel provinsi: " . $e->getMessage());
        }
    }

    public function simpanExcelKotaKabupaten($data)
    {
        Capsule::beginTransaction();

        try {

            $tgl_input = Carbon::now()->toDateString();
            $bln_input = Carbon::now()->format('m');
            $thn_input = Carbon::now()->year;
            $waktu_input = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i');

            $saveKotaKabupaten = [];

            foreach ($data as $d) {

                $kd_kota_kabupaten = $this->generateKdKotaKabupaten();

                $userAgent = $_SERVER['HTTP_USER_AGENT'];
                $deviceInfo = DeviceHelper::detectDevice($userAgent);
                $deviceType = $deviceInfo['deviceType'];
                $device = $deviceInfo['browser'];

                $ipDetector = GeoDetector::getDeviceLocation();
                $ipDevice = isset($ipDetector['ip']) ? $ipDetector['ip'] : 'Unknown IP';

                $kotaKabupaten = new KotaKabupaten();
                $kotaKabupaten->kd_kota_kabupaten = $kd_kota_kabupaten;
                $kotaKabupaten->kd_provinsi = $d['kd_provinsi'];
                $kotaKabupaten->id_kota_kabupaten = $d['id_kota_kabupaten'];
                $kotaKabupaten->nama_kota_kabupaten = $d['nama_kota_kabupaten'];
                $kotaKabupaten->status_tampil = 'ACTIVE';
                $kotaKabupaten->tgl_input = $tgl_input;
                $kotaKabupaten->bln_input = $bln_input;
                $kotaKabupaten->thn_input = $thn_input;
                $kotaKabupaten->waktu_input = $waktu_input;
                $kotaKabupaten->user_input = $d['user_input'];
                $kotaKabupaten->alamat_device = $ipDevice;
                $kotaKabupaten->type_device = $deviceType;
                $kotaKabupaten->device = $device;

                $kotaKabupaten->save();

                $saveKotaKabupaten[] = $kotaKabupaten;
            }

            Capsule::commit();

            return $saveKotaKabupaten;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses excel Kota/Kabupaten: " . $e->getMessage());
        }
    }

    public function simpanExcelKecamatan($data)
    {
        Capsule::beginTransaction();

        try {
            $tgl_input = Carbon::now()->toDateString();
            $bln_input = Carbon::now()->format('m');
            $thn_input = Carbon::now()->year;
            $waktu_input = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i');

            $saveKecamatan = [];

            foreach ($data as $d) {

                $kdKecamatan = $this->generateKodeKecamatan();

                $userAgent = $_SERVER['HTTP_USER_AGENT'];
                $deviceInfo = DeviceHelper::detectDevice($userAgent);
                $deviceType = $deviceInfo['deviceType'];
                $device = $deviceInfo['browser'];

                $ipDetector = GeoDetector::getDeviceLocation();
                $ipDevice = isset($ipDetector['ip']) ? $ipDetector['ip'] : 'Unknown IP';

                $kecamatan = new Kecamatan();
                $kecamatan->kd_kecamatan = $kdKecamatan;
                $kecamatan->kd_kota_kabupaten = $d['kd_kota_kabupaten'];
                $kecamatan->id_kecamatan = $d['id_kecamatan'];
                $kecamatan->nama_kecamatan = $d['nama_kecamatan'];
                $kecamatan->status_tampil = 'ACTIVE';
                $kecamatan->tgl_input = $tgl_input;
                $kecamatan->bln_input = $bln_input;
                $kecamatan->thn_input = $thn_input;
                $kecamatan->waktu_input = $waktu_input;
                $kecamatan->user_input = $d['user_input'];
                $kecamatan->alamat_device = $ipDevice;
                $kecamatan->type_device = $deviceType;
                $kecamatan->device = $device;

                $kecamatan->save();

                $saveKecamatan[] = $kecamatan;
            }

            Capsule::commit();

            return $saveKecamatan;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses excel kecamatan: " . $e->getMessage());
        }
    }
}
