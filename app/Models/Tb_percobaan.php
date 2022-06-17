<?php

namespace App\Models;

use CodeIgniter\Model;

class Tb_percobaan extends Model
{

    protected $table = "tb_percobaan";
    protected $allowedFields = [
        'id_file',
        'percobaan'
    ];
    // protected $useTimestamps = true;


    // ==== GET DATA =====
    public function get_percobaan($id_file)
    {

        $data = $this
            ->select("percobaan")
            ->where("id_file", $id_file)
            ->orderBy("percobaan", "DESC")
            ->first();
        return $data['percobaan'];
    }

    public function get_id_percobaan($id_file, $percobaan)
    {
        $data = $this
            ->select("id_percobaan")
            ->where("id_file", $id_file)
            ->where("percobaan", $percobaan)
            ->first();

        return $data['id_percobaan'];
    }

    public function get_all_id_percobaan($id_file)
    {
        $data = $this->select('id_percobaan')->where("id_file", $id_file)->findAll();
        $semua_id = [];
        foreach ($data as $row) {
            array_push($semua_id, $row['id_percobaan']);
        }

        return $semua_id;
    }

    public function get_all_id_file()
    {
        $data = $this->select("id_file")->where("percobaan >=", 1)->findAll();
        $semua_id_file = [];
        foreach ($data as $row) {
            if (!in_array($row['id_file'], $semua_id_file)) {
                array_push($semua_id_file, $row['id_file']);
            }
        }

        return $semua_id_file;
    }


    // ===== PENGECEKAN =====
    public function Cek_file($id_file)
    {
        $data = $this
            ->select("percobaan")
            ->where("id_file", $id_file)
            ->orderBy("percobaan", "DESC")
            ->first();

        // dd($data);

        if ($data['percobaan'] >= 1 && $data['percobaan'] <= 3) {
            return true;
        } elseif ($data['percobaan'] == 1) {
            return false;
        }
    }
}
