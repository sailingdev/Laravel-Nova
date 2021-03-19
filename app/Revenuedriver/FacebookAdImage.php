<?php

namespace App\Revenuedriver;

use App\Revenuedriver\Base\Facebook;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\Fields\CampaignFields;

class FacebookAdImage extends Facebook
{
    /**
     * @var int
     */
    protected $createAttempts = 0;

    /**
     * @var int
     */
    protected $deleteAttempts = 0;
     
    /*
     * Create a new image
     *  
     * @param string $accountId, 
     * @param array $params
     * @param array $fields
     *
     * @return Response
     */
    public function create(string $accountId, array $params, array $fields=[])
    {
        $account = new AdAccount($accountId);
        try {
            $adImage = $account->createAdImage($fields, $params);
            return [true, $adImage];
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
        } catch (\Throwable $th) {
            return[false, $th];
        } 
    }
    

    /**
     * Delete an ad  image
     * 
     * @param string $ad
     * 
     * @return void
     */
    public function delete(string $adImageId)
    { 
       $set = new AdImage($adImageId);
     
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
                return $this->delete($adImageId);
            } 
            return [false, $e];
        } catch (\Throwable $th) {
            return [false, $th->getMessage()];
        }
    }

}