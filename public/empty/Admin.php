<?php

namespace App\Controllers;

use App\Models\Tb_file;
use App\Models\Tb_hasil;
use App\Models\Tb_percobaan;
use App\Models\Tb_tes;
use Exception;
use MathPHP\Statistics\ANOVA;

class Admin extends BaseController
{

    protected
        $tb_file,
        $tb_result,
        $tb_percobaan,
        $tb_tes,
        $validasi,
        $anova;

    public function __construct()
    {
        $this->tb_file = new Tb_file();
        $this->tb_result = new Tb_hasil();
        $this->tb_percobaan = new Tb_percobaan();
        $this->tb_tes = new Tb_tes();
        $this->validasi = service("validation");
        $this->anova = new ANOVA();
    }

    //home menu
    public function index()
    {

        $data = [
            "link" => "link_1",
            "script" => "script_1",
            "menu" => "home"
        ];
        return view('Admin/home/index', $data);
    }

    // statistik menu
    public function Statistik()
    {
        $data = [
            "link" => "link_1",
            "script" => "script_1",
            "menu" => "statistik",
            "file" => $this->tb_file->getFileStatistik(),
            "percobaan" => $this->tb_percobaan
        ];
        return view('Admin/statistik/index', $data);
    }


    // upload menu
    public function Upload()
    {
        // dd($this->tb_file->findAll());
        if (!empty($this->tb_file->findAll())) {
            $reset = true;
        } else {
            $reset = false;
        }

        // dd($reset);
        $data = [
            "link" => "link_1",
            "script" => "script_1",
            "menu" => "upload",
            "validasi" => $this->validasi,
            "semua_file" => $this->tb_file->getData(),
            "cek" => $this->tb_percobaan,
            "reset" => $reset
        ];
        return view('Admin/upload/index', $data);
    }

    public function Tambah_file()
    {

        // validasi
        if (!$this->validate([
            "file" => [
                "rules" => "uploaded[file]",
                "errors" => [
                    "uploaded" => "Silahkan Isi file terlebih dahulu"
                ]
            ]
        ])) {
            return redirect()->to(base_url("/Upload-file"))->withInput();
        }

        // $percobaan = $this->request->getVar("percobaan");
        $file = $this->request->getFile("file");
        $nama_file = $file->getName();
        $exp = explode(" ", $nama_file);
        $nama_baru = implode("_", $exp);
        $data = [
            "nama_file" => $nama_baru,
            "ukuran_file" => $file->getSize(),
            "status" => "no transfer",
            "aksi" => "off"
        ];

        // moving file
        $file->move("assets/file/", $nama_baru);

        // insert file
        $id_file = $this->tb_file->insert($data, true);

        $data_percobaan = [
            "percobaan" => 0,
            "id_file" => $id_file
        ];

        $insert = $this->tb_percobaan->insert($data_percobaan);

        if (is_numeric($insert)) {
            session()->setFlashdata("msg", "File Berhasil Di upload");
            return redirect()->to(base_url("/Upload-file"));
        }
        // insert ke tb_percobaan


    }


    public function hapus_file($id_file)
    {

        // === TESTING ====

        // ambil nama file
        $file = $this->tb_file->select("nama_file")->where("id_file", $id_file)->first();
        // d($file);
        // dd("tes");

        //hapus file
        $path = "assets/file/" . $file['nama_file'];
        unlink($path);

        // hapus tb_file
        $this->tb_file->where("id_file", $id_file)->delete();

        // ambil seluruh percobaan yang menggunakan id_file
        $semua_id_percobaan = [];
        $semua_percobaan = $this->tb_percobaan->select("id_percobaan")->where("id_file", $id_file)->findAll();
        foreach ($semua_percobaan as $row) {
            array_push($semua_id_percobaan, $row['id_percobaan']);
        }

        // hapus semua percobaan dengan id_file
        $this->tb_percobaan->where("id_file", $id_file)->delete();

        // hapus tb_hasil berdasarkan semua id_percobaan aktifkan jika api sudah berhasil di load
        $this->tb_result->whereIn("id_percobaan", $semua_id_percobaan)->delete();

        // kembalikan ke halaman upload file dengan mengirimkan msq alert
        session()->setFlashdata("msg", "File dengan nama " . $file['nama_file'] . " Berhasil dihapus");
        return redirect()->to(base_url("/Upload-file"));
    }

    public function reset()
    {
        // ambil semua file 
        $file =  $this->tb_file
            ->select("nama_file")
            ->findAll();

        // dd($file);

        // hapus semua file
        foreach ($file as $row) {
            $path = "assets/file/" . $row['nama_file'];
            unlink($path);
        }


        // truncate table file
        $this->tb_file->truncate();

        // truncate table percobaan
        $this->tb_percobaan->truncate();

        // truncate table hasil
        $this->tb_result->truncate();


        session()->setFlashdata("msg", "Seluruh File dan Data yang tersimpan berhasil dihapus");

        return redirect()->to(base_url("/Upload-file"));
    }


    public function transfer($id_file)
    {
        // cek apakah masih ada data yang sedang ditransfer dan active
        $cek_Data = $this->tb_file->getFileStatistik();
        if (empty($cek_Data)) {

            $data_update = [
                "status" => "transfer",
                "aksi" => "active"
            ];

            // update percobaan tb_percobaan jadi 1
            $this->tb_percobaan->where("id_file", $id_file)->set(["percobaan" => 1])->update();


            // update data tb_file
            $update =  $this
                ->tb_file
                ->where("id_file", $id_file)
                ->set($data_update)
                ->update();

            if ($update) {
                return redirect()->to(base_url("/Statistik"));
            }
        } else {
            session()->setFlashdata("Swal", "Running");
            return redirect()->to(base_url("/Upload-file"));
        }
    }

    // persyaratan bisa transfer ulang
    // harus sudah melakukan transfer 1

    public function transfer_ulang($id_file)
    {
        $percobaan = $this->tb_percobaan->get_percobaan($id_file);
        // dd($percobaan);


        // jika percobaan tidak sama dengan 3 maka lakukan penambahakan percobaan
        if ($percobaan < 3) {

            $update_percobaan = $percobaan + 1;
            // echo "tambahkan percobaan menjadi " . $update_percobaan . " ke dalam tabel percobart an";

            $data_percobaan = [
                "percobaan" => $update_percobaan,
                "id_file" => $id_file
            ];

            $insert = $this->tb_percobaan->insert($data_percobaan, true);

            if (is_numeric($insert)) {
                return redirect()->to(base_url("/Statistik"));
            }
        } else {
            $data_update_file = [
                "status" => "no transfer",
                "aksi" => "off"
            ];

            $update = $this
                ->tb_file
                ->where("id_file", $id_file)
                ->set($data_update_file)
                ->update();

            if ($update) {
                session()->setFlashdata("msg", "Mohon Maaf Percobaan yang anda lakukan sudah melebihi batas pengujian");
                return redirect()->to(base_url("/Upload-file"));
            }
        }
    }



    // analisis menu
    public function Analisis()
    {



        // ambil hasil dari tb_hasil berdasarkan id_file dan digantikan dengan id_percobaan, hasil dari uji 1-3;
        // $id_file = 1;
        // $semua_id_percobaan = $this->tb_percobaan->get_all_id_percobaan($id_file);


        // // // tes ambil waktu di file 1 percobaan ke 1 dengan ip address
        // $id_percobaan_1 = $semua_id_percobaan[1];
        // $ip_add = "127.0.0.2";
        // dd($this->tb_result->getTime($ip_add, $id_percobaan_1));




        // $ip_add_uji_1 = $this->tb_result->get_all_ip_add($semua_id_percobaan[1]);
        // $uji1 = $this->tb_result->get_all_pengujian($semua_id_percobaan[0]);
        // $uji2 = $this->tb_result->get_all_pengujian($semua_id_percobaan[1]);
        // $uji3 = $this->tb_result->get_all_pengujian($semua_id_percobaan[2]);

        // d($ip_add_uji_1);
        // d($uji1);
        // d($uji2);
        // d($uji3);
        // dd($semua_id_percobaan);



        $id_file = $this->tb_percobaan->get_all_id_file();

        try {
            $semua_file = $this->tb_file->select("id_file,nama_file,ukuran_file")->whereIn("id_file", $id_file)->findAll();
        } catch (Exception $err) {
            $semua_file = [];
        }


        $data = [
            "link" => "link_1",
            "script" => "script_1",
            "menu" => "analisis",
            "semua_file" => $semua_file,
            "tb_hasil" => $this->tb_result,
            "tb_percobaan" => $this->tb_percobaan,
        ];
        return view('Admin/analisis/index', $data);
    }

    public function Hasil_analisis($id_file)
    {
        $semua_id_percobaan = $this->tb_percobaan->get_all_id_percobaan($id_file);
        $ip_add_uji_1 = $this->tb_result->get_all_ip_add($semua_id_percobaan[0]);

        $uji1 = [];
        $uji2 = [];
        $uji3 = [];
        // diulang 10 IP
        for ($i = 0; $i < count($ip_add_uji_1); $i++) {
            array_push($uji1, $this->tb_result->getTimeToAnalisis($ip_add_uji_1[$i], $semua_id_percobaan[0]));
            array_push($uji2, $this->tb_result->getTimeToAnalisis($ip_add_uji_1[$i], $semua_id_percobaan[1]));
            array_push($uji3, $this->tb_result->getTimeToAnalisis($ip_add_uji_1[$i], $semua_id_percobaan[2]));
        }

        // file
        $file = $this->tb_file->getData($id_file);

        // deskriptif
        $deskriptif = [
            "normal" => [
                "mean" => getDeskriptif($uji1, "mean"),
                "median" => getDeskriptif($uji1, "median"),
                "modus" => getDeskriptif($uji1, "modus")
            ],
            "attack" => [
                "mean" => getDeskriptif($uji2, "mean"),
                "median" => getDeskriptif($uji2, "median"),
                "modus" => getDeskriptif($uji2, "modus")
            ],
            "repair" => [
                "mean" => getDeskriptif($uji3, "mean"),
                "median" => getDeskriptif($uji3, "median"),
                "modus" => getDeskriptif($uji3, "modus")
            ],

        ];


        // anova
        $anova = $this->anova->oneWay($uji1, $uji2, $uji3);
        $data = [
            "link" => "link_1",
            "script" => "script_1",
            "menu" => "analisis",
            "data_summary" => $anova['data_summary'],
            "total_summary" => $anova['total_summary'],
            "anova" => $anova['ANOVA'],
            "deskriptif" => $deskriptif,
            "file" => $file
        ];

        return view("Admin/analisis/hasil_analisis", $data);
    }
}
