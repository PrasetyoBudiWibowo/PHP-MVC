<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpvSales extends Model
{
    protected $table = 'master_spv_sales';
    protected $primaryKey = 'kd_spv_sales';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_spv_sales',
        'kd_karyawan',
        'status_spv_sales',
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

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'kd_karyawan', 'kd_karyawan');
    }

}
