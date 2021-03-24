<?php

namespace App\Revenuedriver;

use App\Revenuedriver\Base\Facebook;
use Illuminate\Support\Facades\Http;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;

class FacebookAdLocale extends Facebook
{ 

    /**
     * @var int
    */
    protected $getAllAttempt = 0;

   
    /**
     * @param mixed $adsetId
     * 
     * @return mixed
     */
    public function getAll()
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ])->get('https://graph.facebook.com/v10.0/search?access_token='.$this->clientToken.'&type=adlocale');
            $decoded = json_decode($response->body());
            // dd('mona', $decoded);
           return [true, $decoded];
        } catch(\FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
        \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
        | \FacebookAds\Http\Exception\ThrottleException  | \FacebookAds\Http\Exception\PermissionException
        | \FacebookAds\Http\Exception\AuthorizationException $e) 
        {
            if ($this->getAllAttempt < 10) {
                sleep(3);
                $this->getAllAttempt++;
                return $this->getAll();
            } 
            return [false, $e];
        } catch (\Throwable $th) {
            return [false, $th->getMessage()];
        }
    }

    
}