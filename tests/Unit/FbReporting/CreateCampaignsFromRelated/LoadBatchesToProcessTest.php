<?php

namespace Tests\Unit;

use App\Jobs\FbReporting\ProcessCampaignsFromSubmittedKeywordsJob;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

class LoadBatchesToProcessTest extends TestCase
{
    /**
     * @test 
    */
    public function should_fail_if_request_method_not_get()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('PUT', '/nova-vendor/create-campaigns-from-related-card/load-batches-to-process');
            
        $response->assertStatus(405);
    }

   

    /**
     * @test 
    */
    public function should_pass_if_all_correct()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('GET', '/nova-vendor/create-campaigns-from-related-cardvendor/bin/phpunit --group=a1/load-batches-to-process');
        
        $response->assertStatus(200);
    }
}
