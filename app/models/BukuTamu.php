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
        'kd_alasan_kunjungan_buku_tamu',
        'alasan_kunjungan_detail',
        'kd_master_sales',
        'kd_provinsi',
        'kd_kota_kabupaten',
        'kd_kecamatan',
        'alamat_detail',
        'kd_sumber_informasi_buku_tamu',
        'detail_sumber_informasi',
        'kd_sumber_informasi_detail_buku_tamu',
        'tgl_kunjungan',
        'bln_kunjungan',
        'thn_kunjungan',
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

    public function alasan_kunjungan()
    {
        return $this->belongsTo(AlasanKunjunganBukuTamu::class, 'kd_alasan_kunjungan_buku_tamu', 'kd_alasan_kunjungan_buku_tamu');
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'kd_master_sales', 'kd_master_sales');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'kd_provinsi', 'kd_provinsi');
    }

    public function kota_kabupaten()
    {
        return $this->belongsTo(KotaKabupaten::class, 'kd_provinsi', 'kd_kota_kabupaten');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kd_kecamatan', 'kd_kecamatan');
    }

    public function sumber_informasi_buku_tamu()
    {
        return $this->belongsTo(SumberInformasiBukuTamu::class, 'kd_sumber_informasi_buku_tamu', 'kd_sumber_informasi_buku_tamu');
    }

    public function sumber_informasi_detail_buku_tamu()
    {
        return $this->belongsTo(SumberInformasiDetailBukuTamu::class, 'kd_sumber_informasi_detail_buku_tamu', 'kd_sumber_informasi_detail_buku_tamu');
    }

    public function user_input()
    {
        return $this->belongsTo(User::class, 'kd_asli_user', 'user_input');
    }
}
