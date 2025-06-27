<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Carbon\Carbon;

use App\Models\Module;
use App\Models\AksesModule;

use Exception;

use App\Core\Database;

class ModuleModels
{
    private $db;

    use HasFactory;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function allModule()
    {
        $modules = Module::all();
        return $modules;
    }

    private function generateKodeModule()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'MDL-' . $currentMonth . '-';

        $lastModule = Module::where('kd_module', 'LIKE', $prefix . '%')
            ->orderBy('kd_module', 'DESC')
            ->first();

        if (!$lastModule) {
            return $prefix . '0000';
        }

        $lastId = $lastModule->kd_module;
        $lastNumber = substr($lastId, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    private function generateKodeAksesModule()
    {
        $currentMonth = Carbon::now()->format('Ym');
        $prefix = 'AKM-' . $currentMonth . '-';

        $aksesTerakhir = AksesModule::where('kd_akses', 'LIKE', $prefix . '%')
            ->orderBy('kd_akses', 'DESC')
            ->first();

        if (!$aksesTerakhir) {
            return $prefix . '0000';
        }

        $lastId = $aksesTerakhir->kd_akses;
        $lastNumber = substr($lastId, -4);

        $newNumber = str_pad(intval($lastNumber) + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $newNumber;
    }

    public function cekModule($kdModule)
    {
        $module = Module::where('kd_module', $kdModule)->first();
        return $module;
    }

    public function cekAksesUser($kdModule, $kdUser)
    {
        $userAkses = AksesModule::where('kd_module', $kdModule)
            ->where('kd_user', $kdUser)
            ->first();
        return $userAkses;
    }

    public function simpanModule($data)
    {
        Capsule::beginTransaction();

        try {
            $kdModule = $this->generateKodeModule();

            $tgl_input = Carbon::now()->toDateString();
            $bln_input = Carbon::now()->format('m');
            $thn_input = Carbon::now()->year;
            $waktu_input = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i');

            $module = new Module();
            $module->kd_module = $kdModule;
            $module->nama_module = $data['nama_module'];
            $module->url_module = $data['url_module'];
            $module->status_module = 'ACTIVE';
            $module->user_input = $data['user_input'];
            $module->tgl_input = $tgl_input;
            $module->bln_input = $bln_input;
            $module->thn_input = $thn_input;
            $module->waktu_input = $waktu_input;

            $module->save();

            Capsule::commit();
            return $module;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses di simpanModule ModuleModels: " . $e->getMessage());
        }
    }

    public function simpanAksesModule($data)
    {
        Capsule::beginTransaction();

        try {
            $tgl_input = Carbon::now()->toDateString();
            $bln_input = Carbon::now()->format('m');
            $thn_input = Carbon::now()->year;
            $waktu_input = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i');

            $saveAksesModule = [];

            foreach ($data as $d) {
                $kdAksesModule = $this->generateKodeAksesModule();

                $aksesModule = new AksesModule();
                $aksesModule->kd_akses = $kdAksesModule;
                $aksesModule->kd_module = $d['kd_module'];
                $aksesModule->kd_user = $d['kd_user'];
                $aksesModule->status_akses = 'YA';
                $aksesModule->user_input = $d['user_input'];
                $aksesModule->tgl_input = $tgl_input;
                $aksesModule->bln_input = $bln_input;
                $aksesModule->thn_input = $thn_input;
                $aksesModule->waktu_input = $waktu_input;

                $aksesModule->save();

                $saveAksesModule[] = $aksesModule;
            }

            Capsule::commit();

            return $saveAksesModule;
        } catch (Exception $e) {
            Capsule::rollBack();
            throw new Exception("Gagal proses simpak akses menu module simpanAksesModule: " . $e->getMessage());
        }
    }
}
