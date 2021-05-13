<?php

namespace Tests\Unit\FbReporting\FbPagePost;

use App\Models\FbReporting\FbPagePost;
use App\Models\FbReporting\FbPagePostScheduler;
use stdClass;
use Tests\TestCase; 

class LoadPostLibraryTest extends TestCase
{

    /**
     * @test
     * @group fbPagePost
    */
    public function should_fail_if_request_method_not_get()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('PUT', '/nova-vendor/fb-page-posts-card/load-post-library');
            
        $response->assertStatus(405);
    }

    /**
     * @test 
     * @group fbPagePost
    */
    public function should_pass_if_all_correct()
    {     
        FbPagePost::factory()->count(3)->create();
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('GET', '/nova-vendor/fb-page-posts-card/load-post-library');
        $response->assertStatus(200); 
    }
}
