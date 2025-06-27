<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempTblUser extends Model
{
    protected $table = 'tmp_tbl_user';
    protected $primaryKey = 'kd_temp_user';
    public $incrementing = false;

    protected $fillable = [
        'kd_temp_user',
        'kd_user',
        'nama_user',
        'id_usr_level',
        'password',
        'status_user',
        'blokir',
        'img_user',
        'format_img_user',
        'user_input',
        'device',
        'nama_device',
    ];

    public $timestamps = false;
}
