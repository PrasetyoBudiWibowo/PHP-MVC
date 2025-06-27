<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AksesModule extends Model
{
    protected $table = 'akses_module';
    protected $primaryKey = 'kd_akses';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_akses',
        'kd_module',
        'kd_user',
        'status_akses',
        'user_input',
        'tgl_input',
        'bln_input',
        'thn_input',
        'waktu_input',
    ];

    public $timestamps = false;
}
