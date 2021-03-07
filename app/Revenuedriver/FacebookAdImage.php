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
        } catch(\FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException $e) 
        {
            if ($this->createAttempts < 5) {
                $this->createAttempts++;
                return $this->create($accountId, $params, $fields);
            } 
            return [false, $e];
        } catch (\Throwable $th) {
            return[false, $th];
        } 
     }
 

}