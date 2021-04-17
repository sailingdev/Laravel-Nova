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
    protected $deleteCampaignAttempts = 0;

    /**
     * @var int
     */
    protected $getAdSetsAttempt = 0;

    /**
     * @var int
     */
    protected $showAttempts = 0;

    /**
     * @var int
     */
    protected $updateAttempts = 0;

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
                if ($this->loadCampaignAttempts < 10) {
                    sleep(3);
                    $this->loadCampaignAttempts++;
                    return $this->loadCampaign($accountId, $fields);
                } 
                return [false, 'No record for this campaign'];
            }
            return [true, $cursor];
        } catch( \FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
                | \FacebookAds\Http\Exception\ThrottleException | \FacebookAds\Http\Exception\PermissionException
                | \FacebookAds\Http\Exception\AuthorizationException $e) 
        { 
            if ($this->loadCampaignAttempts < 10) {
                sleep(3);
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
        } catch(\FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
        \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
        | \FacebookAds\Http\Exception\ThrottleException | \FacebookAds\Http\Exception\PermissionException
        | \FacebookAds\Http\Exception\AuthorizationException $e) 
        {
            if ($this->createCampaignAttempts < 10) {
                sleep(5);
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
        } catch(\FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
                | \FacebookAds\Http\Exception\ThrottleException | \FacebookAds\Http\Exception\PermissionException
                | \FacebookAds\Http\Exception\AuthorizationException $e) 
        {
            if ($this->getAdSetsAttempt < 10) {
                sleep(5);
                $this->getAdSetsAttempt++;
                return $this->getAdsets($campaignId);
            } 
            return [false, $e];
        } catch (\Throwable $th) {
            return [false, $th->getMessage()];
        }
    }

    /**
     * @param string $accountId
     * 
     * @return array
     */
    public function delete(string $campaignId)
    {  
        $campaign = new Campaign($campaignId);
        
        try {
            $campaign->deleteSelf();
            return [true];
        } catch(\FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
                | \FacebookAds\Http\Exception\ThrottleException | \FacebookAds\Http\Exception\PermissionException
                | \FacebookAds\Http\Exception\AuthorizationException $e) 
        {
            if ($this->deleteCampaignAttempts < 10) {
                sleep(5);
                $this->deleteCampaignAttempts++;
                return $this->delete($campaignId);
            } 
            return [false, $e];
        } catch (\Throwable $th) {
            return [false, $th->getMessage()];
        }
    }


    /**
     * Show campaign
     *
     * @param mixed $campaignId
     * @param mixed $fields=[]
     * 
     * @return array
     */
    public function show($campaignId, $fields=[]): array
    {   
        
        try {
            $inst = (new Campaign($campaignId))->getSelf($fields);
            return [true, $inst];
        } catch( \FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
                | \FacebookAds\Http\Exception\ThrottleException | \FacebookAds\Http\Exception\PermissionException
                | \FacebookAds\Http\Exception\AuthorizationException $e) 
        { 
            if ($this->loadCampaignAttempts < 10) {
                sleep(3);
                $this->showAttempts++;
                return $this->show($campaignId, $fields);
            } 
            return [false, $e];
        } catch(\Throwable $th) { 
            return [false, $th];
        }
    }

    
     /**
     * Show campaign
     *
     * @param mixed $campaignId
     * @param mixed $fields=[]
     * 
     * @return array
     */
    public function update($campaignId, $fields=[], $params=[]): array
    {   
        
        try {
            $set = new Campaign($campaignId);
            $response = $set->updateSelf($fields, $params);
            return [true, $response];
        } catch( \FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
                | \FacebookAds\Http\Exception\ThrottleException | \FacebookAds\Http\Exception\PermissionException
                | \FacebookAds\Http\Exception\AuthorizationException $e) 
        { 
            if ($this->loadCampaignAttempts < 10) {
                sleep(3);
                $this->updateAttempts++;
                return $this->update($campaignId, $fields, $params);
            } 
            return [false, $e];
        } catch(\Throwable $th) { 
            return [false, $th];
        }
    }
}