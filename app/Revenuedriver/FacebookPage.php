<?php

namespace App\Revenuedriver;

use App\Revenuedriver\Base\Facebook;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Page;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use Illuminate\Support\Facades\Http;

class FacebookPage extends Facebook
{
    /**
     * @var int
     */
    protected $createAttempts = 0;

    /**
     * @var int
     */
    protected $deleteAttempts = 0;

    /**
     * @var int
     */
    protected $loadInstragramAccountAttempts = 0;
    public $appId;

    public $clientSecret;

    public $clientToken;
 
    /**
     * @var string
     */
    public $api;


    public function __construct()
    {
        $this->appId =  config('facebook.marketing.app_id');
        $this->clientSecret = config('facebook.marketing.client_secret');
        // $this->clientToken = config('facebook.marketing.client_token');
        $this->clientToken = 'EAAFmyiUTy1IBAMU2BklKpEJtRU8jZB2yXy6aXD1dZAZAK2MbZAdqlyi4DZCCvrhyb8MgpKQv5fatam7zJi34IvXgkz7ypBjjcmoJWFEeg4ISgfZBq4QiKXOtD0oRYnNF0ZCdJP7GYMwaqxZCcR7Vs1IkrpGJFTxLdBKi7fnJJiOPe3lpMaCxVnWHRH20uwp6t9ZCAC8SEMP2Q6Mk1hl3iOFHd6QYXHl1KhZA6ZCeOVwxLe9tQZDZD';
        Api::init($this->appId, $this->clientSecret, $this->clientToken, false);

        // The Api object is now available through singleton
        $this->api = Api::instance(); 
        $this->api->setLogger(new CurlLogger());
    }

    /**
     * @param string $accountId
     * @param array $params
     * @param array $fields=[]
     * 
     * @return array
     */
    public function loadInstagramAccounts(string $pageId, array $params=[], array $fields=[]): array
    { 
       

        $longLivedUserAccessToken = $this->getLongLivedUserAccessToken();


        $pageAccessToken = $this->getPageAccessToken($pageId, $longLivedUserAccessToken, hash_hmac('sha256', $longLivedUserAccessToken, 'e7110f3d0020c61c25d979a55475fedc'));

        $proof= hash_hmac('sha256', $pageAccessToken, 'e7110f3d0020c61c25d979a55475fedc'); 
        
        if ($pageAccessToken != null) {
           
            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json',
                ])
                ->post('https://graph.facebook.com/v10.0/'.$pageId.'/page_backed_instagram_accounts', [
                    'access_token' => $pageAccessToken,
                    'fields' => ['id'],
                    'appsecret_proof' => $proof
                ]);

                $decoded = json_decode($response->body());
                if (isset($decoded->id)) {
                    return [true, $decoded->id];
                }
               if (isset($decoded->error)) {
                  return [false, $decoded->error->message];
               }
               return [false, 'Unknown error'];
            } catch(\FacebookAds\Exception\Exception | \FacebookAds\Http\Exception\ClientException | \FacebookAds\Http\Exception\EmptyResponseException |
                \FacebookAds\Http\Exception\ServerException | \FacebookAds\Http\Exception\RequestException
                | \FacebookAds\Http\Exception\ThrottleException  | \FacebookAds\Http\Exception\PermissionException
                | \FacebookAds\Http\Exception\AuthorizationException $e) 
            {
                if ($this->loadInstragramAccountAttempts < 10) {
                    sleep(3);
                    $this->loadInstragramAccountAttempts++;
                    return $this->loadInstagramAccounts($pageId, $params, $fields);
                } 
                return [false, $e];
            }
            catch (\Throwable $th) {
                return [false, $th];
            }
        }
        return [false, 'No record returned'];
      

    }


    protected function getLongLivedUserAccessToken()
    {
        $shortUserAccessToken = 'EAAPK1aBYEkUBAFuP79MAa27hamYUZBtR0voOzXcSbCSkKjZCmWgDPgTm7raPMeoTtQk2RzSlW4fGW3ZCNrdrwAbFZC5ZBdhyGeZCc2CXpqYqutOrVbjqigSZAWG7rq09a3KXYXQyIHR5oFvnZAnMyZBRpFfBUEniCUnZACkFLiSOGPXbPGkgVHbDqdbJk0Gf4hjqKoaO2OZBtQs41IxZBZCI9gOC2W0kCAWtCynYmG3GU0igUfwZDZD';
        
        // Last fetched: 26 march, 2021 7:30PM UTC
        $longUserAccessToken='EAAPK1aBYEkUBAKCTICChxZCcjh3oKriLmqTFYHbP6ESUwvtmgKaCNkkRJRwIpuKWqvCHl9ZCPnWgYy6zLwZAL7gsjthy4zXwH4i6FTlIQq3LCTvXo93nudmdN5po5Gi542pxCv2yVZAMXN8ho2BGDQe7yIgdELzD1HlstpiLqgZDZD';
       
        // try {
        //     $response = Http::withHeaders([
        //         'Accept' => 'application/json',
        //         'Content-type' => 'application/json',
        //     ])
        //     ->get('https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=' . $this->appId . 
        //     '&client_secret=e7110f3d0020c61c25d979a55475fedc&fb_exchange_token=' . $shortUserAccessToken);

        //     $decoded = json_decode($response->body());
        //     dd($decoded);
        //     return $decoded->access_token;
        // } catch (\Throwable $th) {
        //     dd($th);
        //     return false;
        // }
        return $longUserAccessToken;
    }

    protected function getPageAccessToken($pageId, $longLivedUserAccessToken, $proof)
    {
         // get a page access token
         try {

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ])->get('https://graph.facebook.com/'.$pageId.'?access_token='.$longLivedUserAccessToken.'&fields=access_token&appsecret_proof='.$proof);

            $decoded = json_decode($response->body());
            if (isset($decoded->access_token)) {
                return $decoded->access_token;
            }
            // dd($decoded);
            return null;
        } catch (\Throwable $th) {
            dd($th);
            return null;
        }
    }
 

}