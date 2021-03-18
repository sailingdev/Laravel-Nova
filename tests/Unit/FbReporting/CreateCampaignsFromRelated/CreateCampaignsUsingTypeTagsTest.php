<?php

namespace Tests\Unit\FbReporting\CreateCampaignsFromRelated;

use App\Models\FbReporting\SubmittedKeyword;
use stdClass;
use Tests\TestCase; 

class CreateCampaignsUsingTypeTagsTest extends TestCase
{
    /**
     * @test 
    */
    public function should_fail_if_request_method_not_post()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('PUT', '/nova-vendor/create-campaigns-from-related-card/create-campaign');
            
        $response->assertStatus(405);
    }

    /**
     * @test 
    */
    public function should_fail_if_missing_required_params()
    {  
        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('POST', '/nova-vendor/create-campaigns-from-related-card/create-campaign', [
                'data' => '',
            ]);
                
        $response->assertStatus(422);
    }

    /**
     * @test 
    */
    public function should_pass_if_all_correct()
    {   
        $submissions = SubmittedKeyword::factory()->count(3)->make([
            'action_taken' => 'new'
        ]);
        
        $data = [];
        foreach ($submissions as $submission) {
            $obj = new stdClass;
            $obj->batch_id = $submission->batch_id;
            $obj->type_tag = $this->faker->city;
            $obj->keyword = $submission->keyword;
            $obj->id = $submission->id;
            array_push($data, $obj);
        }

        $response = $this->actingAs($this->getDefaultUser())
            ->withHeaders(['Accept' => 'application/json'])
            ->json('POST', '/nova-vendor/create-campaigns-from-related-card/create-campaign', [
                'data' =>  json_encode($data), 
            ]); 
            
        $response->assertStatus(200); 
    }
}
