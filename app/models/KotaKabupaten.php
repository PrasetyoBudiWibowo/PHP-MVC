<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KotaKabupaten extends Model
{
    protected $table = 'master_kota_kabupaten';
    protected $primaryKey = 'kd_kota_kabupaten';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_kota_kabupaten',
        'kd_provinsi',
        'id_kota_kabupaten',
        'nama_kota_kabupaten',
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

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'kd_provinsi', 'kd_provinsi');
    }
}
