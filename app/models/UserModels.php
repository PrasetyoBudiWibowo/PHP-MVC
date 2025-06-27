<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Carbon\Carbon;

use App\Models\User;
use App\Models\HistoryLogin;
use App\Models\TempTblUser;
use App\Helper\DeviceHelper;
use App\Helper\GeoDetector;

use Exception;

use App\Core\Database;

class UserModels
{
    private $db;

    use HasFactory;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function AllUser()
    {
        $allUser = User::all();

        return $allUser;
    }

    public function userWithLevel($kodeUser)
    {
        $user = User::where('kd_asli_user', $kodeUser)
            ->where('status_user', 'ACTIVE')
            ->where('blokir', 'TIDAK')
            ->with('level')
            ->first();

        if (!$user) {
            throw new \Exception("USER YANG SEDANG ANDA GUNAKAN TERBLOKIR ATAU TIDAK AKTIVE.");
        }

        $result = [
            'kd_asli_user' => $user->kd_asli_user,
            'nama_user' => $user->nama_user,
            'id_usr_level' => $user->id_usr_level,
            'status_user' => $user->status_user,
            'blokir' => $user->blokir,
            'tgl_input' => $user->tgl_input,
            'level_user' => [
                'id' => $user->level->id,
                'level_user' => $user->level->level_user,
            ]
        ];

        return $result;
    }

    private function generateUserId()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'USR-' . $currentMonth . '-';

        $lastUser = User::where('kd_asli_user', 'LIKE', $prefix . '%')
            ->orderBy('kd_asli_user', 'DESC')
            ->first();

        if (!$lastUser) {
            return $prefix . '000';
        }

        $lastId = $lastUser->kd_asli_user;
        $lastNumber = substr($lastId, -3);

        $newNumber = str_pad(intval($lastNumber) + 1, 3, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    private function buatKodeHistoryLogin()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'HSL-' . $currentMonth . '-';

        $lastUser = HistoryLogin::where('kd_history_login', 'LIKE', $prefix . '%')
            ->orderBy('kd_history_login', 'DESC')
            ->first();

        if (!$lastUser) {
            return $prefix . '0000';
        }

        $lastId = $lastUser->kd_history_login;
        $lastNumber = substr($lastId, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    private function buatDataHistoryLogin($data)
    {
        Capsule::beginTransaction();

        try {
            $kd_history_login = $this->buatKodeHistoryLogin();
            $tgl_login = Carbon::now()->toDateString();
            $waktu_login = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i');
            $bln_login = Carbon::now()->format('m');
            $thn_login = Carbon::now()->year;

            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $deviceInfo = DeviceHelper::detectDevice($userAgent);
            $deviceType = $deviceInfo['deviceType'];
            $device = $deviceInfo['browser'];

            $ipDetector = GeoDetector::getDeviceLocation();
            $ipDevice = isset($ipDetector['ip']) ? $ipDetector['ip'] : 'Unknown IP';

            $historyLogin = new HistoryLogin();
            $historyLogin->kd_history_login = $kd_history_login;
            $historyLogin->kd_user = $data['kd_user'];
            $historyLogin->tgl_login = $tgl_login;
            $historyLogin->waktu_login = $waktu_login;
            $historyLogin->bln_login = $bln_login;
            // $historyLogin->thn_login = $thn_login;
            $historyLogin->alamat_device = $ipDevice;
            $historyLogin->type_device = $deviceType;
            $historyLogin->device = $device;

            $historyLogin->save();

            Capsule::commit();

            return $historyLogin;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal buat data history: " . $e->getMessage());
        }
    }

    private function generateKdUserTemp()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'TMPSR-' . $currentMonth . '-';

        $lastUser = TempTblUser::where('kd_temp_user', 'LIKE', $prefix . '%')
            ->orderBy('kd_temp_user', 'DESC')
            ->first();

        if (!$lastUser) {
            return $prefix . '000';
        }

        $lastId = $lastUser->kd_temp_user;
        $lastNumber = substr($lastId, -3);

        $newNumber = str_pad(intval($lastNumber) + 1, 3, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    public function generateImage($data)
    {
        $formatDate = Carbon::now()->format('Ym');
        $prefix = 'IMGUSR-' . $formatDate . '-';

        if ($data['kd_asli_user'] !== null) {
            $oldImg = User::where('kd_asli_user', $data['kd_asli_user'])->value('img_user');

            if (!empty($oldImg)) {
                $oldFilePath = $_SERVER['DOCUMENT_ROOT'] . '/mvc-project/public/img/user/' . $oldImg;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
        }

        $lastImage = User::where('img_user', 'LIKE', $prefix . '%')->max('img_user');

        if ($lastImage) {
            $lastNumber = (int) substr($lastImage, 11, 4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newImage = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT) . '-' . $data['kd_asli_user'];

        return $newImage;
    }

    public function cekKodeAsliUser($kdUser)
    {
        $cekUserCode = User::where('kd_asli_user', $kdUser)->exists();

        return $cekUserCode;
    }

    public function cekNamaUser($data)
    {
        $cekNamaUser = User::where('nama_user', $data)->exists();

        return $cekNamaUser;
    }

    public function register($data)
    {
        Capsule::beginTransaction();

        try {
            $kd_asli_user = $this->generateUserId();
            $tgl_input = Carbon::now()->toDateString();
            $waktu_input = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i');
            $bln_input = Carbon::now()->format('m');
            $thn_input = Carbon::now()->year;

            $factory = new PasswordHasherFactory([
                'common' => new NativePasswordHasher(),
            ]);
            $passwordHasher = $factory->getPasswordHasher('common');
            $password = $passwordHasher->hash($data['password']);

            $config = new HtmlSanitizerConfig();
            $sanitizer = new HtmlSanitizer($config);
            $nama_user = $sanitizer->sanitize($data['nama_user']);

            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $deviceInfo = DeviceHelper::detectDevice($userAgent);
            $deviceType = $deviceInfo['deviceType'];
            $device = $deviceInfo['browser'];

            $ipDetector = GeoDetector::getDeviceLocation();
            $ipDevice = isset($ipDetector['ip']) ? $ipDetector['ip'] : 'Unknown IP';

            $user = new User();
            $user->kd_asli_user = $kd_asli_user;
            $user->nama_user = $nama_user;
            $user->id_usr_level = $data['id_usr_level'];
            $user->password = $password;
            $user->password_tampil = $data['password'];
            $user->status_user = "ACTIVE";
            $user->blokir = "TIDAK";
            $user->img_user = $data['img_user'];
            $user->format_img_user = $data['format_img_user'];
            $user->tgl_input = $tgl_input;
            $user->waktu_input = $waktu_input;
            $user->bln_input = $bln_input;
            $user->thn_input = $thn_input;
            $user->device = $device;
            $user->type_device = $deviceType;
            $user->nama_device = $ipDevice;
            $user->user_input = $data['user_input'] ?? null;

            $user->save();

            Capsule::commit();

            return $user;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal registrasi: " . $e->getMessage());
        }
    }

    public function login($data)
    {
        $factory = new PasswordHasherFactory([
            'common' => new NativePasswordHasher(),
        ]);
        $passwordHasher = $factory->getPasswordHasher('common');

        $user = User::where('nama_user', '=', $data['nama_user'])
            ->where('blokir', 'TIDAK')
            ->where('status_user', 'ACTIVE')
            ->with('level')
            ->first();

        if ($user && $passwordHasher->verify($user->password, $data['password'])) {
            $this->buatDataHistoryLogin(['kd_user' => $user->kd_asli_user]);

            $result = [
                'kd_asli_user' => $user->kd_asli_user,
                'nama_user' => $user->nama_user,
                'id_level_user' => $user->id_usr_level,
                'password_tampil' => $user->password_tampil,
                'status_user' => $user->status_user,
                'blokir' => $user->blokir,
                'img_user' => $user->img_user,
                'format_img_user' => $user->format_img_user,
                'tgl_input' => $user->tgl_input,
                'level_user' => [
                    [
                        'id' => $user->level->id,
                        'level_user' => $user->level->level_user,
                    ]
                ]
            ];

            return $result;
        }

        return null;
    }

    public function dataTempEditUser($data)
    {
        Capsule::beginTransaction();

        try {
            $tempUserDelete = TempTblUser::where('user_input', $data['user_input'])->delete();

            $kd_temp_user = $this->generateKdUserTemp();
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $deviceInfo = DeviceHelper::detectDevice($userAgent);

            $deviceType = $deviceInfo['deviceType'];
            $deviceName = $deviceInfo['deviceName'];

            $userTemp = new TempTblUser();
            $userTemp->kd_temp_user = $kd_temp_user;
            $userTemp->kd_user = $data['kd_user'];
            $userTemp->nama_user = $data['nama_user'];
            $userTemp->id_usr_level = $data['id_usr_level'];
            $userTemp->password = $data['password'];
            $userTemp->status_user = $data['status_user'];
            $userTemp->blokir = $data['blokir'];
            $userTemp->img_user = $data['img_user'];
            $userTemp->format_img_user = $data['format_img_user'];
            $userTemp->user_input = $data['user_input'];
            $userTemp->device = $deviceType;
            $userTemp->nama_device = $deviceName;

            $userTemp->save();

            Capsule::commit();

            return $userTemp;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses buat data di temp usr: " . $e->getMessage());
        }
    }

    public function ubahDataUser($data)
    {
        Capsule::beginTransaction();

        try {
            $factory = new PasswordHasherFactory([
                'common' => new NativePasswordHasher(),
            ]);
            $passwordHasher = $factory->getPasswordHasher('common');
            $hashedPassword = $passwordHasher->hash($data['password']);

            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $deviceInfo = DeviceHelper::detectDevice($userAgent);

            $deviceType = $deviceInfo['deviceType'];
            $deviceName = $deviceInfo['deviceName'];

            $user = User::find($data['kd_asli_user']);

            if ($user) {
                $user->update([
                    'nama_user' => $data['nama_user'],
                    'id_usr_level' => $data['id_usr_level'],
                    'img_user' => $data['img_user'] ?? null,
                    'format_img_user' => $data['format_img_user'] ?? null,
                    'password_tampil' => $data['password'],
                    'password' => $hashedPassword,
                    'device' => $deviceType,
                    'nama_device' => $deviceName,
                ]);

                TempTblUser::where('user_input', $data['user_input'])->delete();
            }

            Capsule::commit();

            return $user;
        } catch (\Exception $e) {
            Capsule::rollBack();
            throw new \Exception("Gagal memperbarui data user: " . $e->getMessage());
        }
    }
}
