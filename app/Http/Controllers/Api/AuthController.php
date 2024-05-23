<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $response = ['status' => 0, 'message' => 'Credenciales incorrectas'];
        $data = $request->all();
        $emailOrUsername = $data['email'] ?? null;
        if ($emailOrUsername === null) {
            $response['message'] = 'Introduzca su email';
            return response()->json($response, 400);
        }
        $user = User::where('email', $emailOrUsername)->first();
        if ($user) {
            if (Hash::check($data['password'], $user->password)) {
                $token = $user->createToken('auth_token');
                $response['status'] = 1;
                $response['message'] = '¡Bienvenido a Patatas Gourmet!';
                $response['user'] = $user;
                $response['auth_token'] = $token->plainTextToken;
                $roles = $user->roles()->pluck('name');
                $response['roles'] = $roles;
            } else {
                $response['message'] = 'Contraseña incorrecta';
                return response()->json($response, 401);
            }
        } else {
            $response['message'] = 'Usuario no existe';
            return response()->json($response, 404);
        }
        return response()->json($response);
    }

    public function register(Request $request)
    {
        $response = ['status' => 0, 'message' => 'Credenciales incorrectas'];

        $messages = [
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'email.max' => 'El correo electrónico no debe ser mayor a 255 caracteres.',
            'email.unique' => 'El correo electrónico ya está en uso, por favor elige otro.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg, gif, svg.',
            'image.max' => 'La imagen no debe ser mayor a 2048 kilobytes.',
        ];

        $request->validate([
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], $messages);

        // Manejar el archivo de imagen
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '-' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/img_users', $imageName);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'name' => $request->name,
            'surname' => $request->surname,
            'postal_code' => $request->postal_code,
            'locality' => $request->locality,
            'province' => $request->province,
            'street' => $request->street,
            'number' => $request->number,
            'floor' => $request->floor,
            'staircase' => $request->staircase,
            'phone' => $request->phone,
            'image' => $imageName,
        ]);

        $role = Role::findByName('super-admin', 'api');
        $user->assignRole($role);
        $roles = $user->roles()->pluck('name');
        $response['roles'] = $roles;

        $token = $user->createToken('auth_token');

        $response['status'] = 1;
        $response['message'] = '¡Bienvenido a Patatas Gourmet!';
        $response['user'] = $user;
        $response['auth_token'] = $token->plainTextToken;

        return response()->json($response, 201);
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
