<?php 

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait LoadIgAccountId
{
    public function load($facebookPageId)
    {
        // fields
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ])->post('https://graph.facebook.com/v10.0/'.$facebookPageId.'/page_backed_instagram_accounts', [
                    'access_token' => 'EAAPK1aBYEkUBAODfx5XieVRHhqm8Wtu5DZAcPJB3S74l0jPrkZCHmtHyqO8pu0vrTUKplz0G1CvKGZAFZC4OM9aWghd18y7ZA3Wnayy7dSsw58I1LLnQZAepfUnSXNWzDFtMQ2ccXUXZB8K25ws6ZBVl7vZAsEZCkTFUydzbpFF10RkYI9C9sMV1ZAZAQkbJVJ0ozZBYkNZAQx7FVN3OnYwCzaOSNQIZAX6CnPqjXRNrZCNoaLWioAZDZD',
                    'fields' => 'id',
                    'appsecret_proof' => '72dba153c7900925673bc429c0333e7a'
                ]);

            $decoded = json_decode($response->body());
            dd($decoded);
            return true;
        } catch (\Throwable $th) {
            dd($th);
            return false;
        }
    }
}