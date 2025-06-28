<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlasanKunjunganBukuTamu extends Model
{
    protected $table = 'master_alasan_kunjungan_buku_tamu';
    protected $primaryKey = 'kd_alasan_kunjungan_buku_tamu';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_alasan_kunjungan_buku_tamu',
        'nama_alasan_kunjungan',
        'tampil_buku_tamu',
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
