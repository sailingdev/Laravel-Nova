<?php

namespace Tests\Unit;

use App\Jobs\FbReporting\ProcessCampaignsFromSubmittedKeywordsJob;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

class SubmitedKeywordsTest extends TestCase
{
    /**
     * @test 
    */
    public function should_fail_if_request_method_not_post()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('PUT', '/nova-vendor/submit-keywords-card/submit-keywords');
            
        $response->assertStatus(405);
    }

    /**
     * @test 
    */
    public function should_fail_if_missing_required_params()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('POST', '/nova-vendor/submit-keywords-card/submit-keywords', [
                'keywords' => '',
                'market' => ''
            ]);
                
        $response->assertStatus(422);
    }

    /**
     * @test 
    */
    public function should_pass_if_all_correct()
    {  
       Queue::fake();
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('POST', '/nova-vendor/submit-keywords-card/submit-keywords', [
                'keywords' =>  'garage door opener', //$this->faker->domainWord. ' ' . $this->faker->domainWord,
                'market' => $this->faker->randomElement(['DE', 'US', 'UK'])
            ]); 
            
        $response->assertStatus(200);

        Queue::assertPushed(ProcessCampaignsFromSubmittedKeywordsJob::class);
    }
}
