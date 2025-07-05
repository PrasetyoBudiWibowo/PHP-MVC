<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use Faker\Factory as Faker;
use Carbon\Carbon;

use App\Helper\AppLogger;

class FakerController extends Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        AuthMiddleware::check();
        AuthMiddleware::checkAdmin();
        AuthMiddleware::getCurrentUser();
    }

    public function generateFakeData()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $jumlahData = intval($_POST['jumlah_data'] ?? 0);
                if ($jumlahData <= 0) {
                    echo json_encode(['status' => 'error', 'message' => 'Jumlah data harus lebih dari nol']);
                    return;
                }

                $faker = Faker::create('id_ID');
                $dataWilayah = $this->model('WilayahModels')->allKecamatanWithKabKotaWithProvinsi();
                $dataPosisiton = $this->model('HrdModels')->allPosisition();

                if (empty($dataWilayah)) {
                    echo json_encode(['status' => 'error', 'message' => 'Data wilayah tidak tersedia']);
                    return;
                }

                if (empty($dataPosisiton)) {
                    echo json_encode(['status' => 'error', 'message' => 'Data wilayah tidak tersedia']);
                    return;
                }

                $prefixes = ['0812', '0878', '0821', '0852', '0856', '0821', '0819', '0838'];

                $dataFake = [];
                for ($i = 0; $i < $jumlahData; $i++) {
                    $jumlahKata = rand(2, 5);

                    $namaLengkapArray = [];
                    $namaLengkapArray[] = $faker->firstName;
                    for ($j = 1; $j < $jumlahKata - 1; $j++) {
                        $namaLengkapArray[] = $faker->firstName;
                    }

                    $namaLengkapArray[] = $faker->lastName;
                    $namaLengkap = implode(' ', $namaLengkapArray);
                    $namaBagian = explode(' ', $namaLengkap);
                    $namaPanggilan = $namaBagian[array_rand($namaBagian)];

                    $prefix = $prefixes[array_rand($prefixes)];
                    $randomNumber = $prefix . $faker->numerify('#######');

                    $randomWilayahlahir = $dataWilayah->random();
                    $randomWilayah = $dataWilayah->random();
                    $randomPosisiton = $dataPosisiton->random();

                    $tglLahir = $faker->dateTimeBetween('1990-01-01', '2010-12-31');

                    $tglMasuk = clone $tglLahir;
                    $tglMasuk->modify('+' . rand(18, 30) . ' years');

                    $tglMasuk->setDate(
                        $tglMasuk->format('Y'),
                        rand(1, 12),
                        rand(1, 28)
                    );
                    $tglMasukFormatted = $tglMasuk->format('Y-m-d');

                    $tglAkhirKontrak = clone $tglMasuk;
                    $tglAkhirKontrak->modify('+' . rand(1, 3) . ' years');
                    $tglAkhirKontrakFormatted = $tglAkhirKontrak->format('Y-m-d');

                    $gaji = $faker->numberBetween(4500000, 35000000);

                    $fakeStreet = $faker->streetName;
                    $fakeStreetNumber = $faker->buildingNumber;

                    $fakeVillage = $faker->randomElement([
                        $randomWilayah['nama_kecamatan'],
                        $randomWilayah['kota_kabupaten']['nama_kota_kabupaten'],
                        $faker->word,
                    ]);

                    $fakeHousingName = $faker->city . " Permai";
                    $fakeCity = $randomWilayah['kota_kabupaten']['nama_kota_kabupaten'];

                    $fakeAddress = $fakeStreet . " No. " . $fakeStreetNumber . ", " . $fakeVillage . ", " . $fakeHousingName . ", " . $fakeCity;

                    $dataFake[] = [
                        'nama' => $namaLengkap,
                        'panggilan' => $namaPanggilan,
                        'kelamin' => $faker->randomElement(['Pria', 'Wanita']),
                        'tgl_lahir' => $tglLahir->format('Y-m-d'),
                        'kd_provinsi' => $randomWilayah['kota_kabupaten']['kd_provinsi'],
                        'nama_provinsi' => $randomWilayah['kota_kabupaten']['provinsi']['nama_provinsi'],
                        'kd_kota_kabupaten' => $randomWilayah['kota_kabupaten']['kd_kota_kabupaten'],
                        'nama_kota_kabupaten' => $randomWilayah['kota_kabupaten']['nama_kota_kabupaten'],
                        'kd_kecamatan' => $randomWilayah['kd_kecamatan'],
                        'kecamatan' => $randomWilayah['nama_kecamatan'],
                        'kd_provinsi_lahir' => $randomWilayahlahir['kota_kabupaten']['kd_provinsi'],
                        'nama_provinsi_lahir' => $randomWilayahlahir['kota_kabupaten']['provinsi']['nama_provinsi'],
                        'kd_kota_kabupaten_lahir' => $randomWilayahlahir['kota_kabupaten']['kd_kota_kabupaten'],
                        'nama_kota_kabupaten_lahir' => $randomWilayahlahir['kota_kabupaten']['nama_kota_kabupaten'],
                        'kd_kecamatan_lahir' => $randomWilayahlahir['kd_kecamatan'],
                        'kecamatan_lahir' => $randomWilayahlahir['nama_kecamatan'],
                        'detail_alamat' => $fakeAddress,
                        'tgl_awal_kontrak' => $tglMasukFormatted,
                        'tgl_akhir_kontrak' => $tglAkhirKontrakFormatted,
                        'tgl_masuk' => $tglMasukFormatted,
                        'gaji' => $gaji,
                        'kd_divisi' => $randomPosisiton['departement']['divisi']['kd_divisi'],
                        'nama_divisi' => $randomPosisiton['departement']['divisi']['nama_divisi'],
                        'kd_departement' => $randomPosisiton['kd_departement'],
                        'nama_departement' => $randomPosisiton['departement']['nama_departement'],
                        'kd_position' => $randomPosisiton['kd_position'],
                        'nama_posisi' => $randomPosisiton['nama_position'],
                        'no_telp1' => $randomNumber,
                    ];
                }

                echo json_encode(['status' => 'success', 'message' => 'Berhasil buat data fake.', 'data' => $dataFake]);
            } else {
                throw new \Exception('Metode request tidak valid di hahahaha');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function simpanDataFakeKaryawan()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                if (empty($data['data']) || !is_array($data['data'])) {
                    echo json_encode(['status' => 'error', 'message' => 'Data yang dikirim tidak valid.']);
                    return;
                }

                $dataSave = [];

                foreach ($data['data'] as $datas) {
                    $dataSave[] = [
                        'nama_karyawan' => $datas['nama_karyawan'],
                        'nama_panggilan_karyawan' => $datas['nama_panggilan_karyawan'],
                        'gender' => $datas['gender'],
                        'tgl_lahir' => $datas['tgl_lahir'],
                        'bln_lahir' => $datas['bln_lahir'],
                        'thn_lahir' => $datas['thn_lahir'],
                        'tgl_awal_kontrak' => $datas['tgl_awal_kontrak'],
                        'tgl_bergabung' => $datas['tgl_bergabung'],
                        'bln_bergabung' => $datas['bln_bergabung'],
                        'thn_bergabung' => $datas['thn_bergabung'],
                        'tgl_akhir_kontrak' => $datas['tgl_akhir_kontrak'],
                        'gaji_angka' => $datas['gaji_angka'],
                        'provinsi_lahir' => $datas['provinsi_lahir'],
                        'kota_kab_lahir' => $datas['kota_kab_lahir'],
                        'kecamatan_lahir' => $datas['kecamatan_lahir'],
                        'provinsi_tinggal' => $datas['provinsi_tinggal'],
                        'kota_kab_tinggal' => $datas['kota_kab_tinggal'],
                        'kecamatan_tinggal' => $datas['kecamatan_tinggal'],
                        'alamat_tinggal' => $datas['alamat_tinggal'],
                        'kd_divisi' => $datas['kd_divisi'],
                        'kd_departement' => $datas['kd_departement'],
                        'kd_position' => $datas['kd_position'],
                        'kd_user' => $datas['kd_user'],
                        'no_telp1' => $datas['no_telp1'],
                    ];
                }

                $result = $this->model('HrdModels')->simpanBanyakKaryawan($dataSave);

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data Berhasil Ditambahkan.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di simpanDataFakeKaryawan');
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function generateFakePengunjungBukuTamu()
    {
        header('Content-Type: application/json');
        $log = AppLogger::getLogger('GENERATE-DATA-DUMMY-BUKU-TAMU');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $inputData = json_decode(file_get_contents('php://input'), true);

                if (!$inputData) {
                    throw new \Exception('Data input tidak valid (bukan JSON).');
                }

                $jumlahData = intval($inputData['jumlah_data'] ?? 0);
                if ($jumlahData <= 0) {
                    throw new \Exception('Jumlah data harus lebih dari nol.');
                }

                $tahunAwal = intval($inputData['tahun_awal'] ?? 0);
                $tahunAkhir = intval($inputData['tahun_akhir'] ?? 0);

                $carbonAwal = Carbon::createFromDate($tahunAwal, 1, 1);
                $carbonAkhir = Carbon::createFromDate($tahunAkhir, 12, 31);

                if ($carbonAwal->gt($carbonAkhir)) {
                    throw new \Exception('Tahun Awal tidak boleh lebih besar dari Tahun Akhir.');
                }

                $faker = Faker::create('id_ID');

                // Ambil data
                $dataWilayah = $this->model('WilayahModels')->allKecamatanWithKabKotaWithProvinsi();
                $dataSales = $this->model('SalesModels')->getAllSales();
                $dataAlasanKunjungan = $this->model('BukuTamuModels')->getAllAlasanKunjungan();
                $dataSumberInformasiDetail = $this->model('BukuTamuModels')->getAllSumberInformasiDetail();

                // Filter alasan kunjungan
                $filteredAlasanKunjungan = $dataAlasanKunjungan->filter(function ($item) {
                    return $item['kd_alasan_kunjungan_buku_tamu'] !== "AKBT-202506-0007";
                })->values();

                if ($filteredAlasanKunjungan->isEmpty()) {
                    throw new \Exception('Tidak ada alasan kunjungan yang valid.');
                }

                $dataFake = [];

                for ($i = 0; $i < $jumlahData; $i++) {
                    $jumlahKata = rand(2, 5);

                    $namaLengkapArray = [$faker->firstName];
                    for ($j = 1; $j < $jumlahKata - 1; $j++) {
                        $namaLengkapArray[] = $faker->firstName;
                    }
                    $namaLengkapArray[] = $faker->lastName;

                    $namaLengkap = implode(' ', $namaLengkapArray);

                    $alasanRandom = $filteredAlasanKunjungan->random();
                    $salesRandom = $dataSales->random();
                    $wilayahRandom = $dataWilayah->random();
                    $sumberInformasiDetailRandom = $dataSumberInformasiDetail->random();

                    $tglKunjungan = $faker->dateTimeBetween(
                        $carbonAwal->format('Y-m-d'),
                        $carbonAkhir->format('Y-m-d')
                    )->format('Y-m-d');

                    $jamRandom = $faker->numberBetween(9, 19);
                    $menitRandom = $faker->numberBetween(0, 59);
                    $waktuKunjungan = Carbon::createFromTime($jamRandom, $menitRandom)->format('H:i');

                    $dataFake[] = [
                        'nama_pengunjung' => $namaLengkap,
                        'kd_alasan_kunjungan_buku_tamu' => $alasanRandom['kd_alasan_kunjungan_buku_tamu'],
                        'nama_alasan_kunjungan' => $alasanRandom['nama_alasan_kunjungan'],
                        'tgl_kunjungan' => $tglKunjungan,
                        'waktu_kunjungan' => $waktuKunjungan,
                        'kd_master_sales' => $salesRandom['kd_master_sales'],
                        'nama_sales' => $salesRandom['karyawan']['nama_karyawan'] ?? 'N/A',
                        'kd_provinsi' => $wilayahRandom['kota_kabupaten']['kd_provinsi'],
                        'nama_provinsi' => $wilayahRandom['kota_kabupaten']['provinsi']['nama_provinsi'],
                        'kd_kota_kabupaten' => $wilayahRandom['kota_kabupaten']['kd_kota_kabupaten'],
                        'nama_kota_kabupaten' => $wilayahRandom['kota_kabupaten']['nama_kota_kabupaten'],
                        'kd_kecamatan' => $wilayahRandom['kd_kecamatan'],
                        'nama_kecamatan' => $wilayahRandom['nama_kecamatan'],
                        'kd_sumber_informasi_detail_buku_tamu' => $sumberInformasiDetailRandom['kd_sumber_informasi_detail_buku_tamu'],
                        'nm_sumber_informasi_detail' => $sumberInformasiDetailRandom['nm_sumber_informasi_detail'],
                        'kd_sumber_informasi_buku_tamu' => $sumberInformasiDetailRandom['kd_sumber_informasi_buku_tamu'],
                        'nm_sumber_informasi' => $sumberInformasiDetailRandom['sumber_informasi']['nm_sumber_informasi'],
                    ];
                }

                echo json_encode(['status' => 'success', 'message' => 'Berhasil buat data fake.', 'data' => $dataFake]);
            } else {
                throw new \Exception('Metode request tidak valid di generateFakePengunjungBukuTamu');
            }
        } catch (\Throwable $th) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
            exit;
        }
    }

    public function simpanDataFakeKunjunganBukuTamu()
    {
        header('Content-Type: application/json');
        $log = AppLogger::getLogger('SIMPAN DATA FAKE KUNJUNGAN BUKU TAMU');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $log->info("<================= MULAI PROSES UNTUK SIMPAN DATA FAKE KUNJUNGAN BUKU TAMU =================>");
                $log->info("<================= MULAI PROSES DI CONTROLLER simpanDataFakeKunjunganBukuTamu =================>");

                $inputData = json_decode(file_get_contents('php://input'), true);

                if (!$inputData) {
                    throw new \Exception('Data input tidak valid (bukan JSON).');
                }

                if (empty($inputData['data']) || !is_array($inputData['data'])) {
                    throw new \Exception('Data kosong tidak ada data yang dikirim.');
                    return;
                }

                $dataSave = [];

                foreach ($inputData['data'] as $d) {
                    $dataSave[] = [
                        'nama_pengunjung' => $d['nama_pengunjung'],
                        'kd_master_sales' => $d['kd_master_sales'],
                        'kd_provinsi' => $d['kd_provinsi'],
                        'kd_kota_kabupaten' => $d['kd_kota_kabupaten'],
                        'kd_kecamatan' => $d['kd_kecamatan'],
                        'kd_alasan_kunjungan_buku_tamu' => $d['kd_alasan_kunjungan_buku_tamu'],
                        'kd_sumber_informasi_buku_tamu' => $d['kd_sumber_informasi_buku_tamu'],
                        'kd_sumber_informasi_detail_buku_tamu' => $d['kd_sumber_informasi_detail_buku_tamu'],
                        'tgl_kunjungan' => $d['tgl_kunjungan'],
                        'bln_kunjungan' => $d['bln_kunjungan'],
                        'thn_kunjungan' => $d['thn_kunjungan'],
                        'waktu_kunjungan' => $d['waktu_kunjungan'],
                        'kd_user' => $d['kd_user'],
                    ];
                }

                $result = $this->model('BukuTamuModels')->simpanDummyPengunjungBukuTamu($dataSave);

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Data Berhasil Ditambahkan.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Tidak ada perubahan data user.']);
                }
            } else {
                throw new \Exception('Metode request tidak valid di simpanDataFakeKunjunganBukuTamu');
            }
        } catch (\Throwable $th) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
            exit;
        }
    }
}
