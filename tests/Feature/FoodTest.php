<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\Food;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodTest extends TestCase
{
    /**
     * A basic feature test example.
     */
        use RefreshDatabase;
    
    public function test_must_authenticated_and_retrieve_data()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken; 

        // $this->assertFalse($user);

        // $response = $this->json('GET', '/api/foods', [], ['Authorization' => 'Bearer ' . $token]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->GET('/api/foods');
        
        
        $response->assertStatus(200)->assertJsonStructure([
            'total', 'retrieved', 'data'
        ]);
    }


    public function test_if_data_invalid() {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken; 

        $foodData = [
            "name" => "",
            "price" => 10000,
            "description" => "Lorem ipsum dolor amet"
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->POST('/api/foods', $foodData);

        $response->assertStatus(422)->assertJsonStructure([
            "message", 'errors'
        ]);
    }

    public function test_if_food_is_actually_exist()
    {
        $user = User::factory()->create();
        $food = Food::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $foodData = [
            "name" => "Lorem",
            "price" => 10000,
            "description" => "Lorem ipsum dolor amet"
        ];

        // Make a PUT request to update a non-existent food item
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put('/api/foods/' . $food->id, $foodData);

        $response->assertStatus(200)->assertJsonStructure([
            'id', 'name', 'description', 'links'
        ]);
    }

    // public function test_if_food_not_found() {
    //     $user = User::factory()->create();
    //     $token = $user->createToken('test-token')->plainTextToken; 
        
    //     $nonExistentId = 2500;
        
    //     $foodData = [
    //         "name" => "Lorem",
    //         "price" => 10000,
    //         "description" => "Lorem ipsum dolor amet"
    //     ];
        
    //     $this->assertNull(Food::find($nonExistentId));
        

    //     $response = $this->withHeaders([
    //         'Authorization' => 'Bearer ' . $token,
    //     ])->PUT('/api/foods/'. $nonExistentId, $foodData);

    //     $response->assertStatus(404)->assertJsonStructure([
    //         "message"
    //     ]);
    // }
    
}
