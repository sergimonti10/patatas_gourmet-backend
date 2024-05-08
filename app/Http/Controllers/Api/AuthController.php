<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $response = ['status' => 0, 'message' => 'Invalid Request'];
        $data = $request->all();
        $emailOrUsername = $data['email'] ?? null;
        if ($emailOrUsername === null) {
            $response['message'] = 'Email or username is required';
            return response()->json($response);
        }
        $user = User::where(function ($query) use ($emailOrUsername) {
            $query->where('email', $emailOrUsername);
        })->first();
        if ($user) {
            if (Hash::check($data['password'], $user->password)) {
                $token = $user->createToken('auth_token');
                $response['status'] = 1;
                $response['message'] = $token->plainTextToken;
                $response['user'] = $user;
                $roles = $user->roles()->pluck('name');
                $response['roles'] = $roles;
            } else {
                $response['message'] = 'Invalid password';
            }
        } else {
            $response['message'] = 'User not found';
        }
        return response()->json($response);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
