<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Capsule\Manager as Capsule;

use App\Core\Database;

use App\Models\LevelUser;

class LevelUserModels
{
    private $db;

    use HasFactory;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getLevels()
    {
        $allLevel = LevelUser::all();

        // $this->db->query("SELECT * FROM tbl_level_user");
        // $result = $this->db->resultSet();
        return $allLevel;
    }
}
