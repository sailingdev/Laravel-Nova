<?php

namespace Tests\Unit\FbReporting\FbPagePost;

use App\Models\FbReporting\FbPagePost;
use App\Models\FbReporting\FbPagePostScheduler;
use App\Models\FbReporting\SubmittedKeyword;
use stdClass;
use Tests\TestCase; 

class UpdatePagePostTest extends TestCase
{
    /** 
     * @test
     * @group fbPagePost
    */
    public function should_fail_if_request_method_not_post()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('PUT', '/nova-vendor/fb-page-posts-card/update-page-post');
            
        $response->assertStatus(405);
    }

    /**
     * @test 
     * @group fbPagePost
    */
    public function should_fail_if_missing_required_params()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('POST', '/nova-vendor/fb-page-posts-card/update-page-post', [
                'text' => '',
            ]);
                
        $response->assertStatus(422);
    }

    /**
     * @test 
     * @group fbPagePost
    */
    public function should_pass_if_all_correct()
    {    
        $post = FbPagePost::factory()->create();
        $scheduler = FbPagePostScheduler::factory()->create([
            'fb_page_post_id' => $post->id
        ]);
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('POST', '/nova-vendor/fb-page-posts-card/update-page-post', [
                'fb_page_post_id' => $post->id,
                'fb_page_post_scheduler_id' => $scheduler->id,
                'page_groups' => json_encode(['Group 2']),
                'reference' => $this->faker->name,
                'text' => $this->faker->text,
                'start_date' => $this->faker->dateTime('tomorrow')
            ]);
    
        $response->assertStatus(200); 
    }
}
