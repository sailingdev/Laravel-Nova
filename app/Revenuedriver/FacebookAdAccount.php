<?php

namespace App\Revenuedriver;

use App\Revenuedriver\Base\Facebook;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;

class FacebookAdAccount extends Facebook
{ 
    /**
     * @var int
     */
    protected $loadAcccountAttempts = 0;


    /**
     * Load campaign
     *
     * @param mixed $accountId
     * @param mixed $fields=[]
     * 
     * @return array
     */
    public function loadAccount($accountId, $fields=[]): array
    {  
        try {
            $account = (new AdAccount($accountId))->getSelf($fields);
            return [true, $account];
        } catch( \FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
                | \FacebookAds\Http\Exception\ThrottleException | \FacebookAds\Http\Exception\PermissionException
                | \FacebookAds\Http\Exception\AuthorizationException $e) 
        { 
            if ($this->loadAccount < 10) {
                sleep(3);
                $this->loadAcccountAttempts++;
                return $this->loadAccount($accountId, $fields);
            } 
            return [false, $e];
        } catch(\Throwable $th) { 
            return [false, $th];
        }
    }
}