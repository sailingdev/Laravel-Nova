<?php

namespace App\Revenuedriver;

use App\Revenuedriver\Base\Facebook;
use App\Services\AdAccountService;
use App\Services\FbPageService;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Page;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\Business;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

        $pageAccessToken = $this->getPageAccessToken($pageId, $longLivedUserAccessToken, hash_hmac('sha256', $longLivedUserAccessToken, $this->appSecret));

        $proof= hash_hmac('sha256', $pageAccessToken, $this->appSecret); 
     
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
        $shortUserAccessToken = 'EAAFmyiUTy1IBAAhLDt3bpBmKgViyIDADzkwt38iUuZCEnhXdPHXfG8isLxBZCxVwe4UAgZA4PY3g1XkQ0tkSW6vKz5E2LdN7BWwSvz0SKZBPmTMq4OXdRLUyAOJD0hbHV8GqwLbZAefzxErKwsSFBPeSW1CsxNCsWShSbDkkV4dB7irUgofEEwPVRi6ZC53pMUwJeZAG3bIM40G9Kxym609Nn8ewEeUDeOMHR11J8ElDI3r0mIu1IoD';
         
        // Last fetched: 31 march, 2021 10:10PM UTC
        $longUserAccessToken='EAAFmyiUTy1IBAJrfCnVulpA5SRZCeWXcHdjJ19N5vYURf1sZAKrqQpIpoZBVky7CqnSfAKKxE6YZAN0tZCVr9GH9u0mPpTWZCxoa30qdljQL12hXHZBGVauWQoGtoL5vprt18VSem2ZCPAmxZBO6Yxw1kdcLbjOFWFC7QnZBrAt3EJPAZDZD';
       
        // try {
        //     $response = Http::withHeaders([
        //         'Accept' => 'application/json',
        //         'Content-type' => 'application/json',
        //     ])
        //     ->get('https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=' . $this->appId . 
        //     '&client_secret='.$this->clientSecret.'&fb_exchange_token=' . $shortUserAccessToken);

        //     $decoded = json_decode($response->body());
        //     dd('Long lived user access token', $decoded);
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
            return null;
        }
    }


    /**
     * @return [type]
     */
    public function loadBusinessAccountPages()
    {
        
        $businessManagers = [
            [
                'id' => '137338727436558',
                'name' => 'rd'
            ], 
            [
                'id' => '276611900308096',
                'name' => 'tt'
            ]
        ];

        foreach ($businessManagers as $businessManager) {
            try {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json',
                ])->get('https://graph.facebook.com/'.$businessManager['id'].'/owned_pages?access_token=' . $this->getLongLivedUserAccessToken() . 
                '&appsecret_proof='.hash_hmac('sha256', $this->getLongLivedUserAccessToken(), $this->clientSecret) . '&limit=60000');
    
                $decoded = json_decode($response->body());
                Log::info('I got in here', [count($decoded->data)]);
                // dd($decoded);
                if (isset($decoded->data)) {
                    if (count($decoded->data) > 0) {
                        $fbPageService = new FbPageService;
                       
                        $fbPageService->updateOrCreateMultipleRows($decoded->data, $businessManager['name']);
                    }
                } 
            } catch (\Throwable $th) {
                Log::error('An error occured while processing the loadBusinessAccountPages call', [$th]);
            }
        }
    }


    /**
     * @return void
     */
    public function curateRunningAds(int $rowId, string $pageId)
    {
        $fbPageService = new FbPageService;
        $inst = new FacebookAdAccount(); 
        $adAccountId = $this->getAccount21Id(); 
        // I don't understand why they required an ad account as part of the parameter. 
        // It seems to work fine no matter the ad account provided
        $pull = $inst->getAdsVolume($adAccountId, [
            'ads_running_or_in_review_count'
        ], [
            'page_id' => $pageId
        ]); 
        if ($pull[0] == true) { 
            if (count($pull[1]) > 0) {
                $newCount = isset($pull[1][0]->ads_running_or_in_review_count) ? 
                    $pull[1][0]->ads_running_or_in_review_count : null;

                $fbPageService->updateRunningAdsCount($rowId, $newCount);
                return true;
            } 
        } 
        return false;
    }










 
            
                    // $data = $decoded->data); 
                    
//                         $totalRunningAds = 0;
//                         try {
//                             // 108032627632083
//                             $proof = hash_hmac('sha256', $this->getLongLivedUserAccessToken(), 'e7110f3d0020c61c25d979a55475fedc');
//                             $pageRunningAds = Http::withHeaders([
//                                 'Accept' => 'application/json',
//                                 'Content-type' => 'application/json',
//                             ])->get('https://graph.facebook.com/v10.0/108296017769619/ads_posts?access_token=' . $this->getLongLivedUserAccessToken() . 
//                             '&appsecret_proof='.$proof. '&fields=status_type,is_expired,is_hidden,promotion_status&include_inline_create=true&limit=100');
// // 
//                             $pageData = json_decode($pageRunningAds->body());
//                                 dd($pageData);
//                             if (isset($pageData->data) && count($pageData->data) > 0) {
//                                 $goNext = false;
                             
//                                 $totalRunningAds += count($pageData->data);
                                
                                
//                                 if (isset($pageData->paging) && isset($pageData->paging->next)) {
//                                     $goNext = true;
                                    
//                                     while($goNext === true) {
//                                         $nextPage = $pageData->paging->next;
//                                         $nextPageCon = $nextPage . '&appsecret_proof=' . $proof;
//                                         // dd($nextPageCon);
//                                         Log::info('See this', [$nextPageCon]);
//                                         $pageRunningAds2 = Http::withHeaders([
//                                             'Accept' => 'application/json',
//                                             'Content-type' => 'application/json',
//                                         ])->get($nextPageCon);

//                                         $pageData2 = json_decode($pageRunningAds2->body());
                                        
//                                         if (!isset($pageData2->paging->next) || 
//                                         ( isset($pageData2->paging->next) && $pageData2->paging->next == '' ) ) {
//                                             $goNext = false;
//                                            dd('Ilele', $pageData2);
//                                         }
//                                         else {
//                                             $totalRunningAds += count($pageData2->data);
//                                         }
//                                     }
//                                 } 
//                             }
//                             dd($totalRunningAds, 'Mona Moxie');
                           
//                         } catch (\Throwable $th) {
//                             dd($th);
//                         } 

}