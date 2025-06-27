<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryInputMasterKaryawan extends Model
{
    protected $table = 'history_edit_master_karyawan';
    protected $primaryKey = 'kd_hsr_edit_karyawan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_hsr_edit_karyawan',
        'jenis_edit',
        'kd_karyawan',
        'nama_karyawan',
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
}
