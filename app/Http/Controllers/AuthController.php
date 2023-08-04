<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) {

        // return response()->json(['user' => auth('sanctum')->user()]);

        $allRequest = $request->all();

        $credentials = Validator::make($allRequest, [
            'email' => 'required|email',
            'password' => 'required|min:6|regex:/[0-9]+/|regex:/[a-zA-Z]+/'
        ]);
        


        if ($credentials->fails()) {
            $errors = $credentials->errors();

            // * Return error on body
            return response()->json([
                'message' => 'The Given data is invalid',
                'errors' => $credentials->errors()
            ], 422, ['Content-Type' => 'application/json']);
        }


        $user =  User::where('email', $request->email)->first();
        if ($user == null){
            return response()->json([
                'message' => 'Account is not found'
            ], 404, ['Content-Type' => 'application/json']);
        }
        
        if(auth()->user()) {
            return response()->json([
                'message' => 'Already Authenthicated'
            ], 403, ['Content-Type' => 'application/json']);
        }

        if(Auth::attempt($request->only(['email', 'password']))){
            $user = User::where('email', $request->email)->first();
                // $user = User::where('email', $request->email)->first();

            $user->tokens()->delete();

            return response()->json([
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200, ['Content-Type' => 'application/json']);
        }
        
        return response()->json([
            'message' => 'Invalid Credentials'
        ], 401, ['Content-Type' => 'application/json']);

    }


    public function logout(Request $request) {
        if ($request->user()->currentAccessToken()->delete()) {

            return response()->json([
                'message' => 'User Logged out'
            ], 200, ['Content-Type' => 'application/json']);
        }

    }
}
