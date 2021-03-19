<?php

namespace App\Revenuedriver;

use App\Revenuedriver\Base\Facebook;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;

class FacebookAd extends Facebook
{
    /**
     * @var int
     */
    protected $createAttempts = 0;

    /**
     * @param string $accountId
     * @param array $params
     * @param array $fields=[]
     * 
     * @return array
     */
    public function create(string $accountId, array $params=[], array $fields=[]): array
    { 
        $account = new AdAccount($accountId); 

        try {
            $ad = $account->createAd($fields, $params);
           return [true, $ad];
        } catch(\FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
            \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
            | \FacebookAds\Http\Exception\ThrottleException  | \FacebookAds\Http\Exception\PermissionException
            | \FacebookAds\Http\Exception\AuthorizationException $e) 
        {
            if ($this->createAttempts < 10) {
                sleep(3);
                $this->createAttempts++;
                return $this->create($accountId, $params, $fields);
            } 
            return [false, $e];
        }
        catch (\Throwable $th) {
            return [false, $th];
        }
    }

}