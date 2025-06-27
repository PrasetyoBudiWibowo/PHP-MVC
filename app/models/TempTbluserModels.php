<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Capsule\Manager as Capsule;

use App\Core\Database;

use App\Models\TempTblUser;

class TempTblUserModels
{
    private $db;

    use HasFactory;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllTempUser()
    {
        $allTempUser = TempTblUser::all();

        return $allTempUser;
    }
}
