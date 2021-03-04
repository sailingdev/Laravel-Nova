<?php

namespace App\Revenuedriver;

use App\Revenuedriver\Base\Facebook;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;

class FacebookAdset extends Facebook
{
    /**
     * @var int
    */
    protected $createAttempt = 0;

    /**
     * @var int
    */
    protected $getAdsAttempt = 0;

     
    /**
     * @param string $accountId
     * @param array $fields
     * @param array $params=[]
     * 
     * @return array
     */
    public function create(string $accountId, array $params=[], array $fields=[]): array
    { 
        $account = new AdAccount($accountId);
        try {
            $adset = $account->createAdSet($fields, $params);
            return [true, $adset];
        } catch(\FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
            \FacebookAds\Http\Exception\ServerException $e) 
        {
            if ($this->createAttempt < 5) {
                $this->createAttempt++;
                return $this->create($accountId, $params, $fields);
            } 
            return [false, $e];
        }
        catch (\Throwable $th) {
            dd($th->getMessage());
            return [false, $th];
        }
    }

    /**
     * @param mixed $adsetId
     * 
     * @return mixed
     */
    public function getAds(string $adsetId)
    {
        try {
           $ads = (new Adset($adsetId))->getAds([
               'name', 'adset_id', 'creative', 'status'
           ]);
           return [true, $ads];
        } catch(\FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
            \FacebookAds\Http\Exception\ServerException $e) 
        {
            if ($this->getAdsAttempt < 5) {
                $this->getAdsAttempt++;
                return $this->getAds($adsetId);
            } 
            return [false, $e];
        } catch (\Throwable $th) {
            return [false, $th->getMessage()];
        }
    }
}