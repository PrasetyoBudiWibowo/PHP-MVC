<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SumberInformasiBukuTamu extends Model
{
    protected $table = 'master_sumber_informasi_buku_tamu';
    protected $primaryKey = 'kd_sumber_informasi_buku_tamu';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_sumber_informasi_buku_tamu',
        'nm_sumber_informasi',
        'tampil_buku_tamu',
        'user_input',
        'tgl_input',
        'bln_input',
        'thn_input',
        'waktu_input',
        'device',
        'alamat_device',
        'type_device',
    ];

    public $timestamps = false;
}
