<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelUser extends Model
{
    protected $table = 'tbl_level_user';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'level_user',
    ];

    public $timestamps = false;
}
