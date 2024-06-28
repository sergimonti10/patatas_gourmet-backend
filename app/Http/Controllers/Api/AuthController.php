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
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'surname.required' => 'El campo apellido es obligatorio.',
            'surname.string' => 'El campo apellido debe ser una cadena de texto.',
            'email.sometimes' => 'El campo correo electrónico es obligatorio en algunas situaciones.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'email.unique' => 'El correo electrónico ya está en uso, por favor elige otro.',
            'password.sometimes' => 'El campo contraseña es obligatorio en algunas situaciones.',
            'password.string' => 'El campo contraseña debe ser una cadena de texto.',
            'password.min' => 'El campo contraseña debe contener al menos 8 caracteres.',
            'postal_code.required' => 'El campo código postal es obligatorio.',
            'postal_code.integer' => 'El campo código postal debe ser un número entero.',
            'locality.required' => 'El campo localidad es obligatorio.',
            'locality.string' => 'El campo localidad debe ser una cadena de texto.',
            'province.required' => 'El campo provincia es obligatorio.',
            'province.string' => 'El campo provincia debe ser una cadena de texto.',
            'street.required' => 'El campo calle es obligatorio.',
            'street.string' => 'El campo calle debe ser una cadena de texto.',
            'number.required' => 'El campo número es obligatorio.',
            'number.string' => 'El campo número debe ser una cadena de texto.',
            'floor.nullable' => 'El campo piso es opcional.',
            'floor.string' => 'El campo piso debe ser una cadena de texto.',
            'staircase.nullable' => 'El campo escalera es opcional.',
            'staircase.string' => 'El campo escalera debe ser una cadena de texto.',
            'image.nullable' => 'El campo imagen es opcional.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser un archivo de tipo: jpeg, png, jpg.',
            'phone.required' => 'El campo teléfono es obligatorio.',
            'phone.string' => 'El campo teléfono debe ser una cadena de texto.',
            'role.sometimes' => 'El campo rol es obligatorio en algunas situaciones.',
            'role.string' => 'El campo rol debe ser una cadena de texto.',
            'role.exists' => 'El rol proporcionado no existe.',
        ];

        $validatedData = $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'sometimes|email|unique:users,email',
            'password' => 'sometimes|string|min:8',
            'postal_code' => 'required|integer',
            'locality' => 'required|string',
            'province' => 'required|string',
            'street' => 'required|string',
            'number' => 'required|string',
            'floor' => 'nullable|string',
            'staircase' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'phone' => 'required|string',
            'role' => 'sometimes|string|exists:roles,name',
        ], $messages);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '-' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/img_users', $imageName);
        }

        $user = User::create([
            'name' => $validatedData['name'],
            'surname' => $validatedData['surname'],
            'email' => $validatedData['email'],
            'password' => isset($validatedData['password']) ? bcrypt($validatedData['password']) : null,
            'postal_code' => $validatedData['postal_code'],
            'locality' => $validatedData['locality'],
            'province' => $validatedData['province'],
            'street' => $validatedData['street'],
            'number' => $validatedData['number'],
            'floor' => $validatedData['floor'] ?? '',
            'staircase' => $validatedData['staircase'] ?? '',
            'phone' => $validatedData['phone'],
            'image' => $imageName,
        ]);

        if (isset($validatedData['role'])) {
            $role = Role::findByName($validatedData['role'], 'api');
            $user->assignRole($role);
            $roles = $user->roles()->pluck('name');
            $response['roles'] = $roles;
        } else {
            $role = Role::findByName('user', 'api');
            $user->assignRole($role);
            $response['roles'] = ['user'];
        }

        $token = $user->createToken('auth_token');

        $response['status'] = 1;
        $response['message'] = '¡Bienvenido a Patatas Gourmet!';
        $response['user'] = $user;
        $response['auth_token'] = $token->plainTextToken;

        return response()->json($response, 201);
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'La contraseña actual no es correcta.',
            ], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'La contraseña ha sido modificada correctamente.',
        ]);
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
