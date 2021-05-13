<?php

namespace Tests\Unit\FbReporting\FbPagePost;

use App\Models\FbReporting\FbPagePost;
use App\Models\FbReporting\FbPagePostScheduler;
use App\Models\FbReporting\SubmittedKeyword;
use stdClass;
use Tests\TestCase; 

class DeleteScheduledTest extends TestCase
{
    /**
     * @test
     * @group fbPagePost
    */
    public function should_fail_if_request_method_not_delete()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('PUT', '/nova-vendor/fb-page-posts-card/delete-scheduled-draft');
            
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
            ->json('DELETE', '/nova-vendor/fb-page-posts-card/delete-scheduled-draft');
              
        $response->assertStatus(422);
    }

    /**
     * @test 
     * @group fbPagePost
    */
    public function should_pass_if_all_correct()
    {    
        $fbPagePost = FbPagePost::factory()->create();
        $scheduler = FbPagePostScheduler::factory()->create([
            'page_groups' => json_encode(['Group 1']),
            'start_date' => $this->faker->dateTime('tomorrow', 'UTC'),
            'fb_page_post_id' => $fbPagePost->id,
        ]);
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('DELETE', '/nova-vendor/fb-page-posts-card/delete-scheduled-draft', [
                'id' => $scheduler->id
            ]);
        $response->assertStatus(200); 
    }
}
