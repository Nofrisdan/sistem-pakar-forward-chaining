<?php

namespace App\Models;

use CodeIgniter\Model;

class Tb_tes extends Model
{

    protected $table = "tb_tes";
    protected $allowedFields = ['percobaan'];
    // protected $useTimestamps = true;


    public function getData()
    {
        $data = $this
            ->select("percobaan")
            ->orderBy("percobaan", "DESC")
            ->first();
        dd($data);
    }
}
