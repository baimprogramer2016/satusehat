<?php

namespace App\Traits;

use App\Models\Parameter;
use App\Models\Organization;
use App\Repositories\Parameter\ParameterInterface;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;


use Illuminate\Support\Facades\Http;

trait GeneralTrait
{
    public $result;

    public function autoSeq($param)
    {

        if ($param == 'ORG') {
            $number = Organization::orderBy('id', 'DESC')->first();
            $this->result = ($number == null) ? 'OR-1' : 'OR-' . preg_replace("/[^0-9]/", "", $number->original_code) + 1;
        }

        return $this->result;
    }

    function highlight($text = '', $word = '')
    {
        if (strlen($text) > 0 && strlen($word) > 0) {
            return (str_ireplace($word, "<span class='text-success fw-bold'>{$word}</span>", $text));
        }
        return ($text);
    }

    function colorStatus($param)
    {
        switch ($param) {
            case 1:
                return 'success';
            case  0:
                return 'warning';
            case 2:
                return 'danger';
            case 3:
                return 'danger';
            default:
                return 'danger';
        }
    }
    function descPatient($param)
    {
        switch ($param) {
            case 1:
                return 'Updated';
            case  0:
                return 'Waiting';
            case 2:
                return 'No Completed';
            case 3:
                return 'ID IHS tidak ditemukan';
            case 4:
                return 'Review';
            default:
                return 'No Completed';
        }
    }

    function statusKirim()
    {
        $data = [
            ["kode" => "all", "desc" => "semua"],
            ["kode" => "200", "desc" => "Terkirim ke Satu sehat"],
            ["kode" => "500", "desc" => "Gagal Kirim"],
            ["kode" => "no", "desc" => "Review"]
        ];

        return $data;
    }

    function enc($param)
    {
        return Crypt::encrypt($param);
    }
    function dec($param)
    {
        return Crypt::decrypt($param);
    }

    # mendapatkan nilai numerator dan denominator
    public function split_nominator($param)
    {

        $change_param = $param;
        if (strpos($param, '/mg')) {
            $change_param = str_replace('/mg', 'mg', $param);
        }
        if (strpos($change_param, '/ml')) {
            $change_param = str_replace('/ml', 'ml', $change_param);
        }

        if (strpos($change_param, '/')) {
            $split_kza = explode('/', $change_param);
            $result = [
                'numerator' => explode(' ', $split_kza[0])[0],
                'numerator_satuan' => explode(' ', $split_kza[0])[1],
                'denominator' => explode(' ', $split_kza[1])[0],
                'denominator_satuan' => explode(' ', $split_kza[1])[1],
            ];
        } else {
            $result = [
                'numerator' => explode(' ', $change_param)[0],
                'numerator_satuan' => explode(' ', $change_param)[1],
                'denominator' => 0,
                'denominator_satuan' => '-',
            ];
        }

        # penyesuaian default 0
        $result['denominator_penyesuaian'] = 1;

        if ($result['denominator'] <> 0) {
            $result['denominator_penyesuaian'] = $result['denominator'];
        }

        return $result;
    }

    function createPercent($value1, $value2)
    {
        if ($value1 === null || $value2 === null || $value1 === 0 || $value2 === 0) {
            return 0;
        } else {
            return ($value1 / $value2) * 100;
        }
    }
}
