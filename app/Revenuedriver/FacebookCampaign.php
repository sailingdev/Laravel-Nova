<?php

namespace App\Revenuedriver;

use App\Revenuedriver\Base\Facebook;
use App\Revenuedriver\Base\AdAccountExtension;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;

class FacebookCampaign extends Facebook
{


    /**
     * @var int
     */
    protected $loadCampaignAttempts = 0;

    /**
     * @var int
     */
    protected $createCampaignAttempts = 0;

    /**
     * @var int
     */
    protected $getAdSetsAttempt = 0;

    /**
     * Load campaign
     *
     * @param mixed $accountId
     * @param mixed $fields=[]
     * 
     * @return array
     */
    public function loadCampaign($accountId, $fields=[]): array
    { 
        $accountExtension = new AdAccountExtension($accountId);
        try {
            $cursor = $accountExtension->getCampaigns($fields);
            if ($cursor->count() < 1) {
                return [false, 'No record for this campaign'];
            }
            return [true, $cursor];
        } catch(\FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException $e) 
        {
            if ($this->loadCampaignAttempts < 5) {
                $this->loadCampaignAttempts++;
                return $this->loadCampaign($accountId, $fields);
            } 
            return [false, $e];
        } catch(\Throwable $th) { 
            return [false, $th];
        }
    }

   
    /**
     * @param string $accountId
     * @param array $params
     * @param array $fields=[]
     * 
     * @return array
     */
    public function createCampaign(string $accountId, array $params, array $fields=[]): array
    { 
        try {
           $campaign = (new AdAccount($accountId))->createCampaign($fields, $params)->exportAllData();
           return [true, $campaign];
        } catch(\FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
            \FacebookAds\Http\Exception\ServerException $e) 
        {
            if ($this->createCampaignAttempts < 5) {
                $this->createCampaignAttempts++;
                return $this->createCampaign($accountId, $params, $fields);
            } 
            return [false, $e];
        } catch (\Throwable $th) {
          return [false, $th];
        }
    }

    public function getAdsets(string $campaignId)
    {
        try {
            $adsets = (new Campaign($campaignId))->getAdSets([
             'campaign_id', 'name', 'targeting', 'bid_amount', 'billing_event', 'promoted_object', 'start_time', 'end_time'
            ]);
            return [true, $adsets];
        } catch(\FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
            \FacebookAds\Http\Exception\ServerException $e) 
        {
            if ($this->getAdSetsAttempt < 5) {
                $this->getAdSetsAttempt++;
                return $this->getAdsets($campaignId);
            } 
            return [false, $e];
        } catch (\Throwable $th) {
            return [false, $th->getMessage()];
        }
    }
}