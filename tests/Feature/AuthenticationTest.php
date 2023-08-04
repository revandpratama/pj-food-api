<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_invalid_login_credentials()
    {
        $invalidCredentials = [
            'email' => 'invalidemail',
            'password' => 'short',
        ];

        $validator = Validator::make($invalidCredentials, [
            'email' => 'required|email',
            'password' => 'required|min:6|regex:/[0-9]+/|regex:/[a-zA-Z]+/'
        ]);

        $this->assertTrue($validator->fails());

        $response = $this->json('POST', '/api/users/authenticate', $invalidCredentials);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'email',
                    'password',
                ]
            ]);
    }

    public function test_if_user_exists() {

        $nonExistUser = [
            'email' => 'nonExistEmail@example.test',
            'password' => 'legitpassword123',
        ];


        $this->assertEquals(User::where('email', $nonExistUser['email'])->first(),  null);

        $response = $this->json('POST', '/api/users/authenticate', $nonExistUser);
        
        $response->assertStatus(404)
        ->assertJsonStructure([
            'message'
        ]);

        
    }

    public function test_if_user_already_authenticated()
    {
        
        $user = User::factory()->create(); 
        $token = $user->createToken('test-token')->plainTextToken; 

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->POST('/api/users/authenticate', ['email' => $user->email, 'password' => 'password123']);

        // $this->assertNotEquals(auth('sanctum')->user() ,null);

      
        $response->assertStatus(403);
        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function test_everything_going_normal() {
        $user = User::factory()->create();

        $legitUser = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->json('POST', '/api/users/authenticate', $legitUser);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token'
        ]);
    }
}
