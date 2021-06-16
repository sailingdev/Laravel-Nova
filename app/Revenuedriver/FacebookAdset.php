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
    protected $deleteAttempt = 0;

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
        } catch(\FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
            \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
            | \FacebookAds\Http\Exception\ThrottleException  | \FacebookAds\Http\Exception\PermissionException
            | \FacebookAds\Http\Exception\AuthorizationException $e) 
        {
            if ($this->createAttempt < 10) {
                sleep(3);
                $this->createAttempt++;
                return $this->create($accountId, $params, $fields);
            } 
            return [false, $e];
        }
        catch (\Throwable $th) { 
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
        } catch(\FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
        \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
        | \FacebookAds\Http\Exception\ThrottleException  | \FacebookAds\Http\Exception\PermissionException
        | \FacebookAds\Http\Exception\AuthorizationException $e) 
        {
            if ($this->getAdsAttempt < 10) {
                sleep(3);
                $this->getAdsAttempt++;
                return $this->getAds($adsetId);
            } 
            return [false, $e];
        } catch (\Throwable $th) {
            return [false, $th->getMessage()];
        }
    }

     /**
     * Delete an adset 
     * 
     * @param string $adsetId
     * 
     * @return void
     */
    public function delete(string $adsetId)
    { 
       $set = new AdSet($adsetId);
     
        try {
            $set->deleteSelf();
            return [true];
        } catch(\FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
                | \FacebookAds\Http\Exception\ThrottleException | \FacebookAds\Http\Exception\PermissionException
                | \FacebookAds\Http\Exception\AuthorizationException $e) 
        {
            if ($this->deleteAttempt < 10) {
                sleep(5);
                $this->deleteAttempt++;
                return $this->delete($adsetId);
            } 
            return [false, $e];
        } catch (\Throwable $th) {
            return [false, $th->getMessage()];
        }
    }
}