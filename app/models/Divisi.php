<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $table = 'master_divisi';
    protected $primaryKey = 'kd_divisi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_divisi',
        'nama_divisi',
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
