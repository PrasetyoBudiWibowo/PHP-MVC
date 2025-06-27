<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'master_sales';
    protected $primaryKey = 'kd_master_sales';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_master_sales',
        'kd_spv_sales',
        'kd_karyawan',
        'status_sales',
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

    public function SpvSales()
    {
        return $this->belongsTo(SpvSales::class, 'kd_spv_sales', 'kd_spv_sales');
    }

}
