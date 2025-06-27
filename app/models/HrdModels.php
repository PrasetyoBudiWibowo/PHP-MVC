<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Carbon\Carbon;

use App\Models\Divisi;
use App\Models\Departement;
use App\Models\Posisition;
use App\Models\Karyawan;
use App\Models\Negara;
use App\Models\HistoryKontrakKaryawan;
use App\Models\HistoryPenempatanKaryawan;
use App\Helper\DeviceHelper;
use App\Helper\GeoDetector;

use Exception;

use App\Core\Database;

class HrdModels
{
    private $db;

    use HasFactory;

    public function __construct()
    {
        $this->db = new Database;
    }

    // get data
    public function allDivisi()
    {
        $data = Divisi::all();
        return $data;
    }

    public function allDepartement()
    {
        $data = Departement::with('Divisi')->get();

        $result = [];
        foreach ($data as $d) {
            $result[] = [
                'kd_departement' => $d->kd_departement,
                'nama_departement' => $d->nama_departement,
                'kd_divisi' => $d->kd_divisi,
                'divisi' => [
                    'kd_divisi' => $d->Divisi->kd_divisi,
                    'nama_divisi' => $d->Divisi->nama_divisi,
                ],
            ];
        }

        return $result;

        // cara kedua
        // $data = Departement::select('kd_departement', 'nama_departement', 'kd_divisi')
        //     ->with('Divisi:id,kd_divisi,nama_divisi')
        //     ->get();
    }

    public function allPosisition()
    {
        $data = Posisition::with('Departement')->with('Divisi')->get();

        $result = $data->map(function ($posisi) {
            return [
                'kd_position' => $posisi->kd_position,
                'nama_position' => $posisi->nama_position,
                'kd_departement' => $posisi->kd_departement,
                'departement' => [
                    'kd_departement' => $posisi->kd_departement,
                    'nama_departement' => $posisi->Departement->nama_departement,
                    'divisi' => [
                        'kd_divisi' => $posisi->Divisi->kd_divisi,
                        'nama_divisi' => $posisi->Divisi->nama_divisi,
                    ],
                ],
            ];
        });

        return $result;
    }

    public function allCountry()
    {
        $data = Negara::all();
        return $data;
    }

    public function allKaryawan()
    {
        $karyawan = Karyawan::with('Divisi')
            ->with('Departement')
            ->with('Posisi')
            ->with('HistoryKontrak')
            ->get();

        $result = [];

        foreach ($karyawan as $kr) {
            $result[] = [
                'kd_karyawan' => $kr->kd_karyawan,
                'nama_karyawan' => $kr->nama_karyawan,
                'nama_panggilan_karyawan' => $kr->nama_panggilan_karyawan,
                'gender' => $kr->gender,
                'tgl_lahir' => $kr->tgl_lahir,
                'bln_lahir' => $kr->bln_lahir,
                'thn_lahir' => $kr->thn_lahir,
                'email_pribadi' => $kr->email_pribadi,
                'kd_negara' => $kr->kd_negara,
                'agama' => $kr->agama,
                'npwp' => $kr->npwp,
                'no_ktp' => $kr->npwp,
                'tgl_awal_kontrak' => $kr->tgl_awal_kontrak,
                'tgl_bergabung' => $kr->tgl_bergabung,
                'bln_bergabung' => $kr->bln_bergabung,
                'thn_bergabung' => $kr->thn_bergabung,
                'tgl_akhir_kontrak' => $kr->tgl_akhir_kontrak,
                'tgl_keluar' => $kr->tgl_keluar,
                'bln_keluar' => $kr->bln_keluar,
                'thn_keluar' => $kr->thn_keluar,
                'foto_karyawan' => $kr->foto_karyawan,
                'format_gambar' => $kr->format_gambar,
                'gaji_angka' => $kr->gaji_angka,
                'tempat_lahir' => $kr->tempat_lahir,
                'provinsi_lahir' => $kr->provinsi_lahir,
                'kota_kab_lahir' => $kr->kota_kab_lahir,
                'kecamatan_lahir' => $kr->kecamatan_lahir,
                'provinsi_tinggal' => $kr->provinsi_tinggal,
                'kota_kab_tinggal' => $kr->kota_kab_tinggal,
                'kecamatan_tinggal' => $kr->kecamatan_tinggal,
                'alamat_tinggal' => $kr->alamat_tinggal,
                'kd_divisi' => $kr->kd_divisi,
                'kd_departement' => $kr->kd_departement,
                'kd_position' => $kr->kd_position,
                'status_karyawan' => $kr->status_karyawan,
                'daftar_sistem' => $kr->daftar_sistem,
                'no_telp1' => $kr->no_telp1,
                'no_telp2' => $kr->no_telp2,
                'no_telp3' => $kr->no_telp3,
                'daftar_sales' => $kr->daftar_sales,
                'daftar_spv_sales' => $kr->daftar_spv_sales,
                'negara' => [
                    'kd_negara' => $kr->Negara->kd_negara ?? null,
                    'name'      => $kr->Negara->name ?? null,
                ],
                'divisi' => [
                    'kd_divisi' => $kr->Divisi->kd_divisi,
                    'nama_divisi' => $kr->Divisi->nama_divisi,
                ],
                'departement' => [
                    'kd_departement' => $kr->Departement->kd_departement,
                    'nama_departement' => $kr->Departement->nama_departement,
                ],
                'posisi' => [
                    'kd_position' => $kr->Posisi->kd_position ?? null,
                    'nama_position' => $kr->Posisi->nama_position ?? null,
                ],
                'ProvinsiLahir' => [
                    'kd_provinsi' => $kr->ProvinsiLahir->kd_provinsi,
                    'nama_provinsi' => $kr->ProvinsiLahir->nama_provinsi,
                ],
                'ProvinsiTinggal' => [
                    'kd_provinsi' => $kr->ProvinsiTinggal->kd_provinsi,
                    'nama_provinsi' => $kr->ProvinsiTinggal->nama_provinsi,
                ],
                'KotaKabLahir' => [
                    'kd_kota_kabupaten' => $kr->KotaKabLahir->kd_kota_kabupaten,
                    'nama_kota_kabupaten' => $kr->KotaKabLahir->nama_kota_kabupaten,
                ],
                'KotaKabTinggal' => [
                    'kd_kota_kabupaten' => $kr->KotaKabTinggal->kd_kota_kabupaten,
                    'nama_kota_kabupaten' => $kr->KotaKabTinggal->nama_kota_kabupaten,
                ],
                'KecamatanLahir' => [
                    'kd_kecamatan' => $kr->KecamatanLahir->kd_kecamatan,
                    'nama_kecamatan' => $kr->KecamatanLahir->nama_kecamatan,
                ],
                'KecamatanTinggal' => [
                    'kd_kecamatan' => $kr->KecamatanTinggal->kd_kecamatan,
                    'nama_kecamatan' => $kr->KecamatanTinggal->nama_kecamatan,
                ],
                'historyKontrak' => $kr->HistoryKontrak->map(function ($kontrak) {
                    return [
                        'kd_hsr_kontrak_karyawan' => $kontrak->kd_hsr_kontrak_karyawan,
                        'kd_karyawan' => $kontrak->kd_karyawan,
                        'tgl_awal' => $kontrak->tgl_awal,
                        'tgl_akhir' => $kontrak->tgl_akhir,
                        'status_kontrak' => $kontrak->status_kontrak,
                        'note' => $kontrak->note,
                        'karyawan' => [
                            'nama_karyawan' => $kontrak->karyawan->nama_karyawan
                        ]
                    ];
                }),
                'HistoryPenempatan' => $kr->HistoryPenempatan->map(function ($penempatan) {
                    return [
                        'kd_penempatan_karyawan' => $penempatan->kd_penempatan_karyawan,
                        'kd_karyawan' => $penempatan->kd_karyawan,
                        'tgl_awal_penempatan' => $penempatan->tgl_awal_penempatan,
                        'tgl_akhir_penempatan' => $penempatan->tgl_akhir_penempatan,
                        'doc_penempatan' => $penempatan->doc_penempatan,
                        'note' => $penempatan->note,
                        'doc_penempatan' => $penempatan->doc_penempatan,
                        'karyawan' => [
                            'nama_karyawan' => $penempatan->karyawan->nama_karyawan
                        ],
                        'divisi' => [
                            'kd_divisi' => $penempatan->Divisi->kd_divisi,
                            'nama_divisi' => $penempatan->Divisi->nama_divisi,
                        ],
                        'departement' => [
                            'kd_departement' => $penempatan->Departement->kd_departement,
                            'nama_departement' => $penempatan->Departement->nama_departement,
                        ],
                        'posisi' => [
                            'kd_position' => $penempatan->Posisi->kd_position ?? null,
                            'nama_position' => $penempatan->Posisi->nama_position ?? null,
                        ],
                    ];
                })
            ];
        }

        return $result;
    }

    // private function
    private function generateKdDivisi()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'DVS-' . $currentMonth . '-';

        $lastDivisi = Divisi::where('kd_divisi', 'LIKE', $prefix . '%')
            ->orderBy('kd_divisi', 'DESC')
            ->first();

        if (!$lastDivisi) {
            return $prefix . '0000';
        }

        $lastId = $lastDivisi->kd_divisi;
        $lastNumber = substr($lastId, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    private function generateKdDepartement()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'DPT-' . $currentMonth . '-';

        $lasDepartement = Departement::where('kd_departement', 'LIKE', $prefix . '%')
            ->orderBy('kd_departement', 'DESC')
            ->first();

        if (!$lasDepartement) {
            return $prefix . '0000';
        }

        $lastId = $lasDepartement->kd_departement;
        $lastNumber = substr($lastId, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    private function generateKdPosisi()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'PST-' . $currentMonth . '-';

        $lastPosisition = Posisition::where('kd_position', 'LIKE', $prefix . '%')
            ->orderBy('kd_position', 'DESC')
            ->first();

        if (!$lastPosisition) {
            return $prefix . '0000';
        }

        $lastId = $lastPosisition->kd_position;
        $lastNumber = substr($lastId, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    private function generateKdKaryawan()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'KRY-' . $currentMonth . '-';

        $lastKaryawan = Karyawan::where('kd_karyawan', 'LIKE', $prefix . '%')
            ->orderBy('kd_karyawan', 'DESC')
            ->first();

        if (!$lastKaryawan) {
            return $prefix . '0000';
        }

        $lastId = $lastKaryawan->kd_karyawan;
        $lastNumber = substr($lastId, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    private function generateKodeHstKontrakKaryawan()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'HST-KRY-' . $currentMonth . '-';

        $HistoryKontrakKaryawan = HistoryKontrakKaryawan::where('kd_hsr_kontrak_karyawan', 'LIKE', $prefix . '%')
            ->orderBy('kd_hsr_kontrak_karyawan', 'DESC')
            ->first();

        if (!$HistoryKontrakKaryawan) {
            return $prefix . '0000';
        }

        $lastId = $HistoryKontrakKaryawan->kd_hsr_kontrak_karyawan;
        $lastNumber = substr($lastId, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    private function generateKodePenempatanKontrakKaryawan()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'PMT-KRY-' . $currentMonth . '-';

        $HistoryPenempatanKaryawan = HistoryPenempatanKaryawan::where('kd_penempatan_karyawan', 'LIKE', $prefix . '%')
            ->orderBy('kd_penempatan_karyawan', 'DESC')
            ->first();

        if (!$HistoryPenempatanKaryawan) {
            return $prefix . '0000';
        }

        $lastId = $HistoryPenempatanKaryawan->kd_penempatan_karyawan;
        $lastNumber = substr($lastId, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    public function generateImageKry($data)
    {
        $formatDate = Carbon::now()->format('Ym');
        $prefix = 'IMGKRY-' . $formatDate . '-';

        if ($data['kd_karyawan'] !== null) {
            $oldImg = Karyawan::where('kd_karyawan', $data['kd_karyawan'])->value('foto_karyawan');

            if (!empty($oldImg)) {
                $oldFilePath = $_SERVER['DOCUMENT_ROOT'] . '/mvc-project/public/img/karyawan/' . $oldImg;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
        }

        $lastImage = Karyawan::where('foto_karyawan', 'LIKE', $prefix . '%')->max('foto_karyawan');

        if ($lastImage) {
            $lastNumber = (int) substr($lastImage, 11, 4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newImage = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT) . '-' . $data['kd_karyawan'];

        return $newImage;
    }

    private function simpanHistoryKontrak($data)
    {
        try {
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

            $kdHsrKontrakKaryawan = $this->generateKodeHstKontrakKaryawan();

            $hstKontrakKaryawan = new HistoryKontrakKaryawan();
            $hstKontrakKaryawan->kd_hsr_kontrak_karyawan = $kdHsrKontrakKaryawan;
            $hstKontrakKaryawan->kd_karyawan = $data['kd_karyawan'];
            $hstKontrakKaryawan->tgl_awal = $data['tgl_awal'];
            $hstKontrakKaryawan->tgl_akhir = $data['tgl_akhir'];
            $hstKontrakKaryawan->status_kontrak = $data['status_kontrak'];
            $hstKontrakKaryawan->note = $data['note'];
            $hstKontrakKaryawan->user_input = $data['user_input'];
            $hstKontrakKaryawan->tgl_input = $tgl_input;
            $hstKontrakKaryawan->bln_input = $bln_input;
            $hstKontrakKaryawan->thn_input = $thn_input;
            $hstKontrakKaryawan->waktu_input = $waktu_input;
            $hstKontrakKaryawan->alamat_device = $ipDevice;
            $hstKontrakKaryawan->type_device = $deviceType;
            $hstKontrakKaryawan->device = $device;

            if (!$hstKontrakKaryawan->save()) {
                throw new \Exception("Gagal menyimpan history kontrak karyawan.");
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Gagal proses simpanHistoryKontrak: " . $e->getMessage());
        }
    }

    private function simpanHistoryPenempatan($data)
    {
        try {
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

            $kdHistoryPemenpatan = $this->generateKodeHstKontrakKaryawan();

            $historyPenempatan = new HistoryPenempatanKaryawan();
            $historyPenempatan->kd_penempatan_karyawan = $kdHistoryPemenpatan;
            $historyPenempatan->kd_karyawan = $data['kd_karyawan'];
            $historyPenempatan->tgl_awal_penempatan = $data['tgl_awal_penempatan'];
            $historyPenempatan->tgl_akhir_penempatan = $data['tgl_akhir_penempatan'];
            $historyPenempatan->doc_penempatan = $data['doc_penempatan'];
            $historyPenempatan->format_document = $data['format_document'];
            $historyPenempatan->note = $data['note'];
            $historyPenempatan->kd_divisi = $data['kd_divisi'];
            $historyPenempatan->kd_departement = $data['kd_departement'];
            $historyPenempatan->kd_posisi = $data['kd_posisi'];
            $historyPenempatan->user_input = $data['user_input'];
            $historyPenempatan->tgl_input = $tgl_input;
            $historyPenempatan->bln_input = $bln_input;
            $historyPenempatan->thn_input = $thn_input;
            $historyPenempatan->waktu_input = $waktu_input;
            $historyPenempatan->alamat_device = $ipDevice;
            $historyPenempatan->type_device = $deviceType;
            $historyPenempatan->device = $device;

            $historyPenempatan->save();

            Capsule::commit();

            return true;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses simpanHistoryPenempatan: " . $e->getMessage());
        }
    }

    // bagian cek
    public function cekNamaDivisi($namaDivisi)
    {
        $result = Divisi::where('nama_divisi', $namaDivisi)->first();
        return $result;
    }


    public function cekDivisiByKd($kdDivisi)
    {
        $result = Divisi::where('kd_divisi', $kdDivisi)->first();
        return $result;
    }

    public function cekDepartementByKd($kdDepartement)
    {
        $result = Departement::where('kd_departement', $kdDepartement)->first();
        return $result;
    }

    public function cekKaryawanByKd($kdKarywan)
    {
        $dataKaryawan = Karyawan::where('kd_karyawan', $kdKarywan)->first();
        return $dataKaryawan;
    }

    // Bagian Divisi
    public function simpanDivisi($data)
    {
        Capsule::beginTransaction();

        try {
            $kd_divisi = $this->generateKdDivisi();

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

            $divisi = new Divisi();

            $divisi->kd_divisi = $kd_divisi;
            $divisi->nama_divisi = $data['nama_divisi'];
            $divisi->user_input = $data['kd_user'];
            $divisi->tgl_input = $tgl_input;
            $divisi->bln_input = $bln_input;
            $divisi->thn_input = $thn_input;
            $divisi->waktu_input = $waktu_input;
            $divisi->alamat_device = $ipDevice;
            $divisi->type_device = $deviceType;
            $divisi->device = $device;

            $divisi->save();

            Capsule::commit();

            return $divisi;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses simpan divisi: " . $e->getMessage());
        }
    }

    public function ubahDivisi($data)
    {
        Capsule::beginTransaction();

        try {
            $divisi = Divisi::find($data['kd_divisi']);

            if ($divisi) {
                $divisi->update([
                    'nama_divisi' => $data['nama_divisi']
                ]);
            } else {
                throw new \Exception("Tipe update tidak valid di ubahDivisi.");
            }

            Capsule::commit();

            return $divisi;
        } catch (\Exception $e) {
            Capsule::rollBack();
            throw new \Exception("Gagal ubah data divisi: " . $e->getMessage());
        }
    }

    // Bagian Departement
    public function simpanDepartement($data)
    {
        Capsule::beginTransaction();

        try {
            $kd_departement = $this->generateKdDepartement();

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

            $departement = new Departement();
            $departement->kd_departement = $kd_departement;
            $departement->kd_divisi = $data['kd_divisi'];
            $departement->nama_departement = $data['nama_departement'];
            $departement->user_input = $data['kd_user'];
            $departement->tgl_input = $tgl_input;
            $departement->bln_input = $bln_input;
            $departement->thn_input = $thn_input;
            $departement->waktu_input = $waktu_input;
            $departement->alamat_device = $ipDevice;
            $departement->type_device = $deviceType;
            $departement->device = $device;

            $departement->save();

            Capsule::commit();

            return $departement;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses simpan Depaetement: " . $e->getMessage());
        }
    }

    public function ubahDepartement($data)
    {
        Capsule::beginTransaction();

        try {
            $departement = Departement::find($data['kd_departement']);

            if ($departement) {
                $departement->update([
                    'nama_departement' => $data['nama_departement']
                ]);
            } else {
                throw new \Exception("Tipe update tidak valid di ubahDepartement.");
            }

            Capsule::commit();

            return $departement;
        } catch (\Exception $e) {
            Capsule::rollBack();
            throw new \Exception("Gagal ubah data departement: " . $e->getMessage());
        }
    }

    // bagian posisiton title
    public function simpanPosisitionTitle($data)
    {
        Capsule::beginTransaction();

        try {
            $kd_position = $this->generateKdPosisi();

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

            $posisition = new Posisition();
            $posisition->kd_position = $kd_position;
            $posisition->kd_divisi = $data['kd_divisi'];
            $posisition->kd_departement = $data['kd_departement'];
            $posisition->nama_position = $data['nama_position'];
            $posisition->user_input = $data['kd_user'];
            $posisition->tgl_input = $tgl_input;
            $posisition->bln_input = $bln_input;
            $posisition->thn_input = $thn_input;
            $posisition->waktu_input = $waktu_input;
            $posisition->alamat_device = $ipDevice;
            $posisition->type_device = $deviceType;
            $posisition->device = $device;

            $posisition->save();

            Capsule::commit();

            return $posisition;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses simpan job title: " . $e->getMessage());
        }
    }

    public function simpanDataPerpanjangKontrakKaryawan($data)
    {
        Capsule::beginTransaction();
        try {

            $karyawan = Karyawan::find($data['kd_karyawan']);

            if ($karyawan) {
                $karyawan->update([
                    'tgl_awal_kontrak' => $data['tgl_awal_kontrak'],
                    'tgl_akhir_kontrak' => $data['tgl_akhir_kontrak'],
                    'status_kontrak' => $data['status_kontrak'],
                ]);

                $historyKontrakKaryawan = $this->simpanHistoryKontrak(
                    [
                        'kd_karyawan' => $karyawan->kd_karyawan,
                        'tgl_awal' => $karyawan->tgl_awal_kontrak,
                        'tgl_akhir' => $karyawan->tgl_akhir_kontrak,
                        'note' => $data['note'],
                        'status_kontrak' => $karyawan->status_kontrak,
                        'user_input' => $karyawan->user_input,
                    ]
                );
            } else {
                throw new \Exception("THAHAHAHAHAHAHA.");
            }

            if (!$historyKontrakKaryawan) {
                throw new Exception("prose simpan history kontrak karyawan");
            }

            Capsule::commit();

            return $karyawan;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses simpanDataPerpanjangKontrakKaryawan: " . $e->getMessage());
        }
    }

    //data dummy
    public function simpanBanyakKaryawan($data)
    {
        Capsule::beginTransaction();

        try {
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

            $simpanBanyakKaryawan = [];

            foreach ($data as $datas) {
                $kdKaryawan = $this->generateKdKaryawan();

                $karyawan = new Karyawan();
                $karyawan->kd_karyawan = $kdKaryawan;
                $karyawan->nama_karyawan = $datas['nama_karyawan'];
                $karyawan->nama_panggilan_karyawan = $datas['nama_panggilan_karyawan'];
                $karyawan->gender = $datas['gender'];
                $karyawan->tgl_lahir = $datas['tgl_lahir'];
                $karyawan->bln_lahir = $datas['bln_lahir'];
                $karyawan->thn_lahir = $datas['thn_lahir'];
                $karyawan->tgl_awal_kontrak = $datas['tgl_awal_kontrak'];
                $karyawan->tgl_bergabung = $datas['tgl_bergabung'];
                $karyawan->bln_bergabung = $datas['bln_bergabung'];
                $karyawan->thn_bergabung = $datas['thn_bergabung'];
                $karyawan->tgl_akhir_kontrak = $datas['tgl_akhir_kontrak'];
                $karyawan->gaji_angka = $datas['gaji_angka'];
                $karyawan->provinsi_lahir = $datas['provinsi_lahir'];
                $karyawan->kota_kab_lahir = $datas['kota_kab_lahir'];
                $karyawan->kecamatan_lahir = $datas['kecamatan_lahir'];
                $karyawan->provinsi_tinggal = $datas['provinsi_tinggal'];
                $karyawan->kota_kab_tinggal = $datas['kota_kab_tinggal'];
                $karyawan->kecamatan_tinggal = $datas['kecamatan_tinggal'];
                $karyawan->alamat_tinggal = $datas['alamat_tinggal'];
                $karyawan->kd_divisi = $datas['kd_divisi'];
                $karyawan->kd_departement = $datas['kd_departement'];
                $karyawan->kd_position = $datas['kd_position'];
                $karyawan->status_karyawan = 'AKTIF';
                $karyawan->daftar_sistem = 'TIDAK';
                $karyawan->no_telp1 = $datas['no_telp1'];
                $karyawan->user_input = $datas['kd_user'];
                $karyawan->tgl_input = $tgl_input;
                $karyawan->bln_input = $bln_input;
                $karyawan->thn_input = $thn_input;
                $karyawan->waktu_input = $waktu_input;
                $karyawan->alamat_device = $ipDevice;
                $karyawan->type_device = $deviceType;
                $karyawan->device = $device;

                $karyawan->save();

                if (!$karyawan) {
                    throw new Exception("Gagal simpan karyawan.");
                }

                $historyKontrakKaryawan = $this->simpanHistoryKontrak(
                    [
                        'kd_karyawan' => $karyawan->kd_karyawan,
                        'tgl_awal' => $karyawan->tgl_awal_kontrak,
                        'tgl_akhir' => $karyawan->tgl_akhir_kontrak,
                        'note' => null,
                        'status_kontrak' => 'PEGAWAI BARU',
                        'user_input' => $karyawan->user_input,
                    ]
                );

                $historyPenempatanKaryawan = $this->simpanHistoryPenempatan(
                    [
                        'kd_karyawan' => $karyawan->kd_karyawan,
                        'tgl_awal_penempatan' => $karyawan->tgl_awal_kontrak,
                        'tgl_akhir_penempatan' => null,
                        'doc_penempatan' => null,
                        'format_document' => null,
                        'note' => null,
                        'kd_divisi' => $karyawan->kd_divisi,
                        'kd_departement' => $karyawan->kd_departement,
                        'kd_posisi' => $karyawan->kd_position,
                        'user_input' => $karyawan->user_input,
                    ]
                );

                if (!$historyKontrakKaryawan) {
                    throw new Exception("prose simpan history kontrak karyawan");
                }

                if (!$historyPenempatanKaryawan) {
                    throw new Exception("prose simpan history penempatan karyawan");
                }

                $simpanBanyakKaryawan[] = $karyawan;
            }

            Capsule::commit();
            return $simpanBanyakKaryawan;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses simpanBanyakKaryawan: " . $e->getMessage());
        }
    }

    //
    public function ubahDataKaryawan($data)
    {
        Capsule::beginTransaction();

        try {
            $karyawan = Karyawan::find($data['kd_karyawan']);

            if ($karyawan) {
                if ($data['type'] === 'FOTO') {
                    $karyawan->update([
                        'foto_karyawan' => $data['foto_karyawan'],
                        'format_gambar' => $data['format_gambar'],
                    ]);
                } else if ($data['type'] === 'PERSONAL KARYAWAN') {
                    $tgl_ubah = Carbon::now()->toDateString();
                    $bln_ubah = Carbon::now()->format('m');
                    $thn_ubah = Carbon::now()->year;
                    $waktu_ubah = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i');

                    $userAgent = $_SERVER['HTTP_USER_AGENT'];
                    $deviceInfo = DeviceHelper::detectDevice($userAgent);
                    $deviceType = $deviceInfo['deviceType'];
                    $device = $deviceInfo['browser'];

                    $ipDetector = GeoDetector::getDeviceLocation();
                    $ipDevice = isset($ipDetector['ip']) ? $ipDetector['ip'] : 'Unknown IP';


                    $karyawan->update([
                        'nama_karyawan' => $data['nama_karyawan'],
                        'nama_panggilan_karyawan' => $data['nama_panggilan_karyawan'],
                        'gender' => $data['gender'],
                        'tgl_lahir' => $data['tgl_lahir'],
                        'kd_negara' => $data['kd_negara'],
                        'provinsi_lahir' => $data['provinsi_lahir'],
                        'kota_kab_lahir' => $data['kota_kab_lahir'],
                        'kecamatan_lahir' => $data['kecamatan_lahir'],
                        'alamat_lahir' => $data['alamat_lahir'] === "" ? NULL : $data['alamat_lahir'],
                        'tinggi_karyawan' => $data['tinggi_karyawan'] === "" ? NULL : $data['tinggi_karyawan'],
                        'berat_karyawan' => $data['berat_karyawan'] === "" ? NULL : $data['berat_karyawan'],
                        'tgl_ubah' => $tgl_ubah,
                        'bln_ubah' => $bln_ubah,
                        'thn_ubah' => $thn_ubah,
                        'type_device_ubah' => $deviceType,
                        'device_ubah' => $device,
                    ]);
                }
            } else {
                throw new \Exception("karyawan tidak ada taafadasbjbnk.");
            }

            Capsule::commit();

            return $karyawan;
        } catch (\Exception $e) {
            Capsule::rollBack();
            throw new \Exception("Gagal memperbarui data karyawan: " . $e->getMessage());
        }
    }
}
