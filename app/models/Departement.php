<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    protected $table = 'master_departement';
    protected $primaryKey = 'kd_departement';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_departement',
        'kd_divisi',
        'nama_departement',
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

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'kd_divisi', 'kd_divisi');
    }
}
