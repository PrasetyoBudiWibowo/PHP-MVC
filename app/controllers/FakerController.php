<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use Faker\Factory as Faker;

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
}
