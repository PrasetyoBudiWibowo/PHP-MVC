<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posisition extends Model
{
    protected $table = 'master_position';
    protected $primaryKey = 'kd_position';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_position',
        'kd_divisi',
        'kd_departement',
        'nama_position',
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

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'kd_departement', 'kd_departement');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'kd_divisi', 'kd_divisi');
    }
}
