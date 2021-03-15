<?php

namespace Tests\Unit\FbReporting\CreateCampaignsFromRelated;
 
use Tests\TestCase;

class ProcessedBatchHistoryTest extends TestCase
{
    /**
     * @test 
    */
    public function should_fail_if_request_method_not_get()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('PUT', '/nova-vendor/create-campaigns-from-related-card/processed-batch-history');
            
        $response->assertStatus(405);
    }

   

    /**
     * @test 
    */
    public function should_pass_if_all_correct()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('GET', '/nova-vendor/create-campaigns-from-related-card/processed-batch-history');
        
        $response->assertStatus(200);
    }
}
