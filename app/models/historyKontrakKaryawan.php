<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryKontrakKaryawan extends Model
{
    protected $table = 'history_kontrak_karyawan';
    protected $primaryKey = 'kd_hsr_kontrak_karyawan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_hsr_kontrak_karyawan',
        'kd_karyawan',
        'tgl_awal',
        'tgl_akhir',
        'status_kontrak',
        'note',
        'user_input',
        'tgl_input',
        'bln_input',
        'thn_input',
        'waktu_input',
        'alamat_device',
        'type_device',
        'device',
    ];

    public $timestamps = false;

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'kd_karyawan', 'kd_karyawan');
    }
}
