<?php

namespace App\Traits;

use App\Models\Parameter;
use Illuminate\Support\Carbon;



use Illuminate\Support\Facades\Http;

trait ApiTrait
{
    public $result;


    public function env()
    {
        return Parameter::first();
    }
    protected function expiryToken($expires_in)
    {
        $datetime = Carbon::now()->addSeconds($expires_in)->format('Y-m-d H:i:s'); // Current datetime + 3 Jam
        return $datetime;
    }
    # Waktu Saat ini
    protected function currentNow()
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

        if ($this->currentNow() < $data->expiry_token) {
            return $data->access_token;
        } else {

            $url = $data->auth_url . '/accesstoken?grant_type=client_credentials';
            $response = Http::asForm()->post($url, [
                'client_id' => $this->replaceId($data->client_id),
                'client_secret' => $this->replaceId($data->client_secret),
            ]);

            $token = $response->json()['access_token']; //parsing token
            $expires_in = $response->json()['expires_in']; //parsing token
            $data->access_token = $token;
            $data->expiry_token = $this->expiryToken($expires_in);
            $data->update();
            return $token;
        }
    }

    public function auth_login_kfa()
    {
        $data =  $this->env();
        if ($this->currentNow() < $data->expiry_token_kfa) {

            return $data->access_token_kfa;
        } else {

            $url = $data->auth_url . '/accesstoken?grant_type=client_credentials';
            $response = Http::asForm()->post($url, [
                'client_id' => $this->replaceId($data->client_id),
                'client_secret' => $this->replaceId($data->client_secret),
            ]);

            $token = $response->json()['access_token']; //parsing token
            $expires_in = $response->json()['expires_in']; //parsing token
            $data->access_token_kfa = $token;
            $data->expiry_token_kfa = $this->expiryToken($expires_in);
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

    public function post_general_ss($endpoint, $payload)
    {
        # generate token
        $token = $this->auth_satu_sehat();
        $url    = $this->env()->base_url . "/" . $endpoint;

        $response = Http::withToken($token)->post($url, $payload);
        return $response;
    }
}
