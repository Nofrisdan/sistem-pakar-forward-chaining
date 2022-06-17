<?php

namespace App\Models;

use CodeIgniter\Model;

class Tb_file extends Model
{

    protected $table = "tb_file";
    protected $allowedFields = [
        'nama_file',
        'ukuran_file',
        'status',
        'aksi'
    ];


    // get data 

    public function getData($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        } else {
            return $this->where("id_file", $id)->first();
        }
    }

    public function getFileStatistik()
    {
        $data = $this
            ->where("status", "transfer")
            ->where("aksi", "active")
            ->first();

        return $data;
    }
}
