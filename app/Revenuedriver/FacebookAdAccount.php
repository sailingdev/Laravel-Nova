<?php

namespace App\Revenuedriver;

use App\Revenuedriver\Base\AdAccountExtension;
use App\Revenuedriver\Base\Facebook;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use Illuminate\Support\Facades\Log;

class FacebookAdAccount extends Facebook
{ 
    /**
     * @var int
     */
    protected $loadAcccountAttempts = 0;

    /**
     * @var int
     */
    protected $getAdCreativesAttempts = 0;

    /**
     * @var int
     */
    protected $getAdsVolumeAttempts = 0;

    private $adCreatives = [];


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


    /**
     * @param string $accountId
     * 
     * @return array
     */
    public function getAdCreatives(string $accountId, $fields=[], $params=[]) 
    {
        $accountExtension = new AdAccountExtension($accountId);
        try {
            $cursor = $accountExtension->getAdCreatives($fields, $params);
            $cursor->setUseImplicitFetch(true);
            if ($cursor->count() > 0) {
                do {
                    $cursor->fetchAfter();
                    foreach ($cursor as $adCreative) {
                        $this->adCreatives[] = $adCreative;
                        Log::info('Adcreative', [$adCreative->id, $adCreative->status]);
                    }
                } while ($cursor->getNext());
            } 
            $useable = array_filter($cursor, function ($adCreative) {
                return $adCreative->status === 'ACTIVE';
            }); 
            return [true, $this->adCreatives];
        } catch( \FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
                | \FacebookAds\Http\Exception\ThrottleException | \FacebookAds\Http\Exception\PermissionException
                | \FacebookAds\Http\Exception\AuthorizationException $e) 
        { 
            if ($this->getAdCreativesAttempts < 10) {
                sleep(3);
                $this->getAdCreativesAttempts++;
                return $this->getAdCreatives($accountId, $fields, $params);
            } 
            return [false, $e];
        } catch(\Throwable $th) { 
            return [false, $th];
        }    
    }



    /**
     * @param string $accountId
     * @param mixed $fields=[]
     * @param mixed $params=[]
     * 
     * @return array
     */
    public function getAdsVolume(string $accountId, $fields=[], $params=[])
    {
        $account = new AdAccount($accountId);
        try {
            $cursor = $account->getAdsVolume($fields, $params);
            return [true, $cursor];
        } catch( \FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
                | \FacebookAds\Http\Exception\ThrottleException | \FacebookAds\Http\Exception\PermissionException
                | \FacebookAds\Http\Exception\AuthorizationException $e) 
        { 
            if ($this->getAdsVolumeAttempts < 10) {
                sleep(3);
                $this->getAdsVolumeAttempts++;
                return $this->getAdsVolume($accountId, $fields, $params);
            }
            return [false, $e];
        } catch(\Throwable $th) {
            return [false, $th];
        }  
    }
}