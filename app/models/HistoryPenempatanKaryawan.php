<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryPenempatanKaryawan extends Model
{
    protected $table = 'history_pemempatan_karyawan';
    protected $primaryKey = 'kd_penempatan_karyawan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_penempatan_karyawan',
        'kd_karyawan',
        'tgl_awal_penempatan',
        'tgl_akhir_penempatan',
        'doc_penempatan',
        'format_document',
        'note',
        'kd_divisi',
        'kd_departement',
        'kd_posisi',
        'user_input',
        'tgl_input',
        'bln_input',
        'thn_input',
        'waktu_input',
        'alamat_device',
        'type_device',
        'device',
    ];

    public $timestamps = false;

    public function Divisi()
    {
        return $this->belongsTo(Divisi::class, 'kd_divisi', 'kd_divisi');
    }

    public function Departement()
    {
        return $this->belongsTo(Departement::class, 'kd_departement', 'kd_departement');
    }

    public function Posisi()
    {
        return $this->belongsTo(Posisition::class, 'kd_posisi', 'kd_position');
    }

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'kd_karyawan', 'kd_karyawan');
    }
}
