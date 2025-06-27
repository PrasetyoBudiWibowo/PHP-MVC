<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryLogin extends Model
{
    protected $table = 'history_login_user';
    protected $primaryKey = 'kd_history_login';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_history_login',
        'kd_user',
        'tgl_login',
        'waktu_login',
        'bln_login',
        'thn_login',
        'device_login',
        'nama_device',
    ];

    public $timestamps = false;
}
