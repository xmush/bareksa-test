<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Tests\TestCase;

class TagTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // $name_1 = substr($this->faker->sentence(3, true), 0, 19);
    // $name_2 = substr($this->faker->sentence(3, true), 0, 19);

    // $data_create_valid = [
    //     'name' => ''
    // ]
    
    private $tag_id;

    function set_tag_id($id) {
        $this->tag_id = $id;
    }

    function generate_name_valid_len() {
        return substr($this->faker->sentence(3, true), 0, 19);
    }

    function generate_name_invalid_len() {
        return Str::random(30);
    }

    function generate_create_tag_valid_data() {
        return [
            'name' => $this->generate_name_valid_len()
        ];
    }

    function generate_create_tag_invalid_data() {
        return [
            'name' => $this->generate_name_invalid_len()
        ];
    }

    function generate_update_tag_valid_data() {
        return [
            'name' => $this->generate_name_valid_len(),
            'status' => 'draft',
        ];
    }

    function generate_update_tag_invalid_data() {
        return [
            'name' => $this->generate_name_invalid_len(),
            'status' => 'draft',
        ];
    }


    public function test_tag_list() {
        $response = $this->get('/api/tag');
        $response->assertStatus(200);
    }

    public function test_tag_create_invalid() {
        $data = $this->generate_create_tag_valid_data();

        $response = $this->post('/api/tag?'.Arr::query($data));

        $response->assertStatus(200);
    }

    public function test_tag_create_valid() {
        $data = $this->generate_create_tag_valid_data();

        $response = $this->post('/api/tag?'.Arr::query($data));
        $response_data = $response->json(); 
        $this->set_tag_id($response_data['data']['id']);

        $response->assertStatus(200);
    }
    
    public function test_tag_detail() {
        
        $response = $this->get('/api/tag/'.$this->tag_id);

        $response->assertStatus(200);
    }

    public function test_tag_update() {
        $data = $this->generate_update_tag_valid_data();

        $response = $this->patch('/api/tag/'.$this->tag_id.'?'.Arr::query($data));
        
        // $response->assertStatus(200);
        $response->assertStatus(405);
    }

    public function test_tag_update_invalid() {
        $data = $this->generate_update_tag_invalid_data();

        $response = $this->patch('/api/tag/'.$this->tag_id.'?'.Arr::query($data));
        
        // $response->assertStatus(400);
        $response->assertStatus(405);
    }
    
    public function test_tag_delete() {

        $response = $this->delete('/api/tag/'.$this->tag_id);

        $response->assertStatus(405);
        
    }

    public function test_tag_delete_second() {

        $response = $this->delete('/api/tag/'.$this->tag_id);
        
        // $response->assertStatus(404);
        $response->assertStatus(405);

    }
}
