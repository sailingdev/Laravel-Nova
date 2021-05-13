<?php

namespace Tests\Unit\FbReporting\FbPagePost;

use App\Models\FbReporting\SubmittedKeyword;
use stdClass;
use Tests\TestCase; 

class SubmitPagePostTest extends TestCase
{
    /**
     * @test
     * @group fbPagePost
    */
    public function should_fail_if_request_method_not_post()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('PUT', '/nova-vendor/fb-page-posts-card/submit-page-post');
            
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
            ->json('POST', '/nova-vendor/fb-page-posts-card/submit-page-post', [
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
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('POST', '/nova-vendor/fb-page-posts-card/submit-page-post', [
                'text' =>  $this->faker->text,
                'url' => $this->faker->url,
                'start_date' => $this->faker->dateTime('tomorrow'),
                'page_groups' => json_encode(['Group 1']),
                'reference' => $this->faker->name,
                'media' => ''
            ]);
            
        $response->assertStatus(200); 
    }
}
