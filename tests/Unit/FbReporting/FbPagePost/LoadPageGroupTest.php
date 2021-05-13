<?php

namespace Tests\Unit\FbReporting\FbPagePost;

use App\Models\FbReporting\FbPagePost;
use App\Models\FbReporting\FbPagePostScheduler;
use stdClass;
use Tests\TestCase; 

class LoadPageGroupTest extends TestCase
{

    /**
     * @test
     * @group fbPagePost
    */
    public function should_fail_if_request_method_not_get()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('PUT', '/nova-vendor/fb-page-posts-card/load-page-groups');
            
        $response->assertStatus(405);
    }

    /**
     * @test 
     * @group fbPagePost
    */
    public function should_pass_if_all_correct()
    {      
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('GET', '/nova-vendor/fb-page-posts-card/load-page-groups');
        $response->assertStatus(200); 
    }
}
