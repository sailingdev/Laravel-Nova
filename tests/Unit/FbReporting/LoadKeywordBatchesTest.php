<?php

namespace Tests\Unit;

use App\Jobs\FbReporting\ProcessCampaignsFromSubmittedKeywordsJob;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

class LoadKeywordBatchesTest extends TestCase
{
    /**
     * @test 
    */
    public function should_fail_if_request_method_not_get()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('PUT', '/nova-vendor/submit-keywords-card/load-keyword-batches');
            
        $response->assertStatus(405);
    }

   

    /**
     * @test 
    */
    public function should_pass_if_all_correct()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('GET', '/nova-vendor/submit-keywords-card/load-keyword-batches');
        
        $response->assertStatus(200);
    }
}
