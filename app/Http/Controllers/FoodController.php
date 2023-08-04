<?php

namespace App\Http\Controllers;

use App\Models\Food;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $bearerToken = $request->bearerToken();

        // if ($bearerToken === null) {
        //     return response()->json([
        //         'message' => 'Unauthenticated'
        //     ], 401, ['Content-Type' => 'application/json']);
        // }

        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401, ['Content-Type' => 'application/json']);
        }

        

        $foods = Food::all();
        $links = [];
        foreach ($foods as $food) {
            $links[] = [
                'self' => URL::to('/foods/' . $food->id),
            ];
        }

        foreach ($foods as $food) {
            $food->links = [
                'self' => URL::to('/foods/' . $food->id),
            ];
        }

        return response()->json([
            'total' => count($foods),
            'retrieved' => count($foods),
            'data' => $foods,
        ], 200, ['Content-Type' => 'application/json']);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401, ['Content-Type' => 'application/json']);
        }

        $validatedData = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|integer',
            'description' => 'required'
        ]);

        if ($validatedData->fails()){
            return response()->json([
                'message' => 'The given data was invalid',
                'errors' => $validatedData->errors()
            ], 422, ['Content-Type' => 'application/json']);
        }

        $food = Food::create($request->only('name', 'price', 'description'));

        return response()->json([
            'id' => $food->id,
            'name' => $food->name,
            'price' => $food->price,
            'description' => $food->description,
            'links' => ['self' => URL::to('/foods/' . $food->id)]
        ], 201, ['Content-Type' => 'application/json']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Food $food, Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401, ['Content-Type' => 'application/json']);
        }

        if ($food === null) {
            return response()->json([
                'message' => 'The given food resource is not found.'
            ], 404, ['Content-Type' => 'application/json']);
        }

        return response()->json([
            'id' => $food->id,
            'name' => $food->name,
            'price' => $food->price,
            'description' => $food->description,
            'links' => ['self' => URL::to('/foods/' . $food->id)]
        ], 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Food $food)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Food $food)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401, ['Content-Type' => 'application/json']);
        }

        if($food === null) {
            return response()->json([
                'message' => 'The given food resource is not found'
            ], 404, ['Content-Type' => 'application/json']);
        }

        $validatedData = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|integer',
            'description' => 'required'
        ]);

        $food->name = $request->name;
        $food->price = $request->price;
        $food->description = $request->description;


        return response()->json([
            'id' => $food->id,
            'name' => $food->name,
            'price' => $food->price,
            'description' => $food->description,
            'links' => ['self' => URL::to('/foods/' . $food->id)]
        ], 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food, Request $request)
    {

        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401, ['Content-Type' => 'application/json']);
        }

        if($food === null) {
            return response()->json([
                'message' => 'The given food resource is not found'
            ], 404, ['Content-Type' => 'application/json']);
        }

        $food->destroy($food->id);

        return response()->json([], 204);

    }
}
