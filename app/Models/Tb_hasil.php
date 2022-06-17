<?php

namespace App\Models;

use CodeIgniter\Model;

class Tb_hasil extends Model
{
    protected $table = "tb_hasil";
    protected $allowedFields = [
        'id_percobaan',
        'ip_add',
        'waktu',
    ];


    // get Data 
    public function getAllData($id_percobaan)
    {
        $semua_data_percobaan = $this->where("id_percobaan", $id_percobaan)->findAll();


        return $semua_data_percobaan;
    }

    public function get_all_pengujian($id_percobaan)
    {
        $data = $this->select('waktu')->where("id_percobaan", $id_percobaan)->findAll();
        $waktu = [];
        foreach ($data as $row) {
            array_push($waktu, $row['waktu']);
        }
        return $waktu;
    }
    public function get_all_ip_add($id_percobaan)
    {
        $data = $this->select("ip_add")->where("id_percobaan", $id_percobaan)->findAll();
        $ip = [];
        foreach ($data as $row) {
            array_push($ip, $row['ip_add']);
        }
        return $ip;
    }

    public function getTime($ip_add, $id_percobaan)
    {

        $data = $this->select("waktu")->where("ip_add", $ip_add)->where("id_percobaan", $id_percobaan)->first();

        if (empty($data)) {
            return "N/A";
        } else {
            return $data['waktu'];
        }
    }
    public function getTimeToAnalisis($ip_add, $id_percobaan)
    {

        $data = $this->select("waktu")->where("ip_add", $ip_add)->where("id_percobaan", $id_percobaan)->first();

        if (empty($data)) {

            $na = 3 * 3600000;
            return (string) $na;
        } else {
            return $data['waktu'];
        }
    }
}
