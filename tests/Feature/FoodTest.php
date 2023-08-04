<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    
    
    public function test_must_authenticated()
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
}
