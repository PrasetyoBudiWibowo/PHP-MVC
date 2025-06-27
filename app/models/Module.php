<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'tbl_module';
    protected $primaryKey = 'kd_module';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_module',
        'nama_module',
        'url_module',
        'status_module',
        'user_input',
        'tgl_input',
        'bln_input',
        'thn_input',
        'waktu_input',
    ];

    public $timestamps = false;
}
