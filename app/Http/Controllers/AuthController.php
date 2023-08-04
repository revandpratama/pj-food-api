<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) {

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

        $user =  User::where('email', $credentials['email'])->first();
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
        
        if(!Auth::attempt($credentials)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401, ['Content-Type' => 'application/json']);
        }


        // * Request success
        return response()->json([
            'token' => 'token',
        ], 200, ['Content-Type' => 'application/json']);
    }
}
