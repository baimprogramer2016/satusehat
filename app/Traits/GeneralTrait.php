<?php

namespace App\Traits;

use App\Models\Parameter;
use App\Models\Organization;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;


use Illuminate\Support\Facades\Http;

trait GeneralTrait
{
    public $result;


    public function env()
    {
        return Parameter::first();
    }
    protected function expiryToken()
    {
        $datetime = Carbon::now()->addSeconds(env("EXPIRY_TOKEN"))->format('Y-m-d H:i:s'); // Current datetime + 3 Jam
        return $datetime;
    }
    # Waktu Saat ini
    protected function currentTime()
    {
        $datetime = Carbon::now()->format('Y-m-d H:i:s'); // Current datetime
        return $datetime;
    }

    protected function replaceId($id)
    {
        return str_replace(env('REPLACE_ID'), '', $id);
    }

    public function auth_satu_sehat()
    {
        $data =  $this->env();

        if ($this->currentTime() < $data->expiry_token) {
            return $data->access_token;
        } else {

            $url = $data->auth_url . '/accesstoken?grant_type=client_credentials';
            $response = Http::asForm()->post($url, [
                'client_id' => $this->replaceId($data->client_id),
                'client_secret' => $this->replaceId($data->client_secret),
            ]);

            $token = $response->json()['access_token']; //parsing token
            $data->access_token = $token;
            $data->expiry_token = $this->expiryToken();
            $data->update();
            return $token;
        }
    }

    public function api_response_ss($endpoint, $id)
    {
        # generate token
        $token = $this->auth_satu_sehat();
        # hit ss
        $url    = $this->env()->base_url . "/" . $endpoint . "/" . $this->dec($id);
        //get detail
        $response_satusehat  = Http::withToken($token)->get($url)->body();
        return $response_satusehat;
    }

    public function autoSeq($param)
    {

        if ($param == 'ORG') {
            $number = Organization::orderBy('original_code', 'DESC')->first();
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
}
