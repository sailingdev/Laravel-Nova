<?php

namespace App\Revenuedriver;

use App\Revenuedriver\Base\Facebook;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\AdCreative;
use FacebookAds\Object\Fields\CampaignFields;

class FacebookAdCreative extends Facebook
{
     
    /**
     * @var int
     */
    protected $showAttempts = 0;

    /**
     * @var int
     */
    protected $createAttempts = 0;

     /**
     * @var int
     */
    protected $deleteAttempts = 0;

    /**
     * @param string $adCreativeId
     * @param array $params=[]
     * @param array $fields=[]
     * 
     * @return array
     */
    public function show(string $adCreativeId, array $params=[], array $fields=[]): array
    {  
       
        try {
           $adCreative = (new AdCreative($adCreativeId))->getSelf($params, $fields);
           return [true, $adCreative];
        } catch(\FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
            \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
            | \FacebookAds\Http\Exception\ThrottleException  | \FacebookAds\Http\Exception\PermissionException
            | \FacebookAds\Http\Exception\AuthorizationException  $e) 
        {
            if ($this->showAttempts < 10) {
                sleep(3);
                $this->showAttempts++;
                return $this->show($adCreativeId, $params, $fields);
            } 
            return [false, $e];
        } catch (\Throwable $th) {
            return [false, $th];
        }
    }

    /*
     * Create a new campaign
     * 
     * @param string $accountId
     * @param array $params
     * @param array $fields
     * 
     * @return Response
     */
    public function create(string $accountId, array $params, array $fields=[])
    { 
        $account = new AdAccount($accountId);
        
        try {
            $adCreative = $account->createAdCreative($fields, $params);
            return [true, $adCreative];
        } catch(\FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
                | \FacebookAds\Http\Exception\ThrottleException  | \FacebookAds\Http\Exception\PermissionException
                | \FacebookAds\Http\Exception\AuthorizationException  $e) 
        {
            if ($this->createAttempts < 10) {
                sleep(3);
                $this->createAttempts++;
                return $this->create($accountId, $params, $fields);
            } 
            return [false, $e];
        } 
        catch (\Throwable $th) {
            return[false, $th];
        } 
    }



    /**
     * Delete an adcceatvie
     * 
     * @param string $ad
     * 
     * @return void
     */
    public function delete(string $adCreativeId)
    { 
       $set = new AdCreative($adCreativeId);
     
        try {
            $set->deleteSelf();
            return [true];
        } catch(\FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
                | \FacebookAds\Http\Exception\ThrottleException | \FacebookAds\Http\Exception\PermissionException
                | \FacebookAds\Http\Exception\AuthorizationException $e) 
        {
            if ($this->deleteAttempts < 10) {
                sleep(5);
                $this->deleteAttempts++;
                return $this->delete($adCreativeId);
            } 
            return [false, $e];
        } catch (\Throwable $th) {
            return [false, $th->getMessage()];
        }
    }
 

}