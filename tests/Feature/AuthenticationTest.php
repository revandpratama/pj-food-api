<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
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
}
