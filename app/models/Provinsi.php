<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'master_provinsi';
    protected $primaryKey = 'kd_provinsi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_provinsi',
        'id_provinsi',
        'nama_provinsi',
        'status_tampil',
        'tgl_input',
        'bln_input',
        'thn_input',
        'waktu_input',
        'user_input',
        'alamat_device',
        'type_device',
        'device',
    ];

    public $timestamps = false;
}
