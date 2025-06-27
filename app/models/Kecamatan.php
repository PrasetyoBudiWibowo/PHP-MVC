<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'master_kecamatan';
    protected $primaryKey = 'kd_kecamatan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_kecamatan',
        'kd_kota_kabupaten',
        'id_kecamatan',
        'nama_kecamatan',
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

    public function kotaKabupaten()
    {
        return $this->belongsTo(KotaKabupaten::class, 'kd_kota_kabupaten', 'kd_kota_kabupaten');
    }
}
