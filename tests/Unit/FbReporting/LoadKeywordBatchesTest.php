<?php

namespace Tests\Unit;
 
use Tests\TestCase; 

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
