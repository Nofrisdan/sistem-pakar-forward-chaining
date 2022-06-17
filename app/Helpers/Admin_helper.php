<?php

// get IP addres
function getIPAddress()
{
    //whether ip is from the share internet
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    //whether ip is from the proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    //whether ip is from the remote address
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


// data deskriptif
function getDeskriptif($array, $output)
{

    $hasil = "";
    if (!is_array($array)) {
        return false;
    } else {
        if ($output == "mean") {
            $hasil = array_sum($array) / count($array);
        } elseif ($output == "median") {
            sort($array);
            if (count($array) % 2 == 0) {
                // $hasil = "genap";
                // genap

                $m = count($array) / 2;

                $bil1 = $array[$m - 1];
                $bil2 = $array[$m];

                $hasil = ($bil1 + $bil2) / 2;
            } else {
                // ganjil
                $m = round(count($array) / 2);
                // $hasil = $m;
                $hasil = $array[$m - 1];
                // $hasil = $array;
            }
        } elseif ($output == "modus") {

            $arr_baru = array_count_values($array);
            arsort($arr_baru);
            // for
            foreach ($arr_baru as $k => $arr_baru) {
                $hasil = $k;
                break;
            }
        } else {
            return false;
        }
    }

    return $hasil;
}
