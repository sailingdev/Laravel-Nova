<?php

namespace Tests\Unit\FbReporting\IgAccountLoader;

use App\Jobs\FbReporting\ProcessCampaignsFromSubmittedKeywordsJob;
use Tests\TestCase; 
use Illuminate\Support\Facades\Queue;

class LoadIgAccountsUsingFbPagesTest extends TestCase
{
    /**
     * @test 
     * @group igAccountLoader
    */
    public function should_fail_if_request_method_not_post()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('PUT', '/nova-vendor/ig-account-loader-card/load-ig-accounts');
            
        $response->assertStatus(405);
    }

    /**
     * @test 
     * @group igAccountLoader
    */
    public function should_fail_if_missing_required_params()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('POST', '/nova-vendor/ig-account-loader-card/load-ig-accounts', [
           
            ]);
                
        $response->assertStatus(422);
    }

    /**
     * @test 
     * @group igAccountLoader
    */
    public function should_pass_if_all_correct()
    {  
       
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('POST', '/nova-vendor/ig-account-loader-card/load-ig-accounts', [
                'fb_page_ids' =>  '3916735071749940', //$this->faker->domainWord. ' ' . $this->faker->domainWord,
            ]); 
            
        $response->assertStatus(200); 
    }
}
