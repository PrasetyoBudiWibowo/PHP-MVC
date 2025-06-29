<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuTamu extends Model
{
    protected $table = 'buku_tamu';
    protected $primaryKey = 'kd_buku_tamu';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_buku_tamu',
        'kd_buku_tamu_awal',
        'nama_pengunjung',
        'status_kunjungan',
        'nama_pengunjung',
        'kd_alasan_kunjungan_buku_tamu',
        'alasan_kunjungan_detail',
        'kd_sales',
        'kd_provinsi',
        'kd_kota_kabupaten',
        'kd_kecamatan',
        'alamat_detail',
        'kd_sumber_informasi_buku_tamu',
        'detail_sumber_informasi',
        'kd_sumber_informasi_detail_buku_tamu',
        'tgl_kunjungan',
        'bln_kunjungan',
        'waktu_kunjungan',
        'note',
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
}
