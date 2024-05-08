<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; //Necesario para que funcione el authorize


class UserController extends Controller
{
    use AuthorizesRequests; //Necesario para que funcione el authorize
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('viewAny', User::class);
            $users = User::all();
            return response()->json($users);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para ver los usuarios registrados.'], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $this->authorize('create', User::class);
            $users = User::create($request->all());
            return response()->json($users, 201);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para crear usuarios.'], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $this->authorize('view', User::find($id));
            $users = User::find($id);
            return response()->json($users);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para ver este usuario.'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        try {
            $this->authorize('update', User::find($id));
            $users = User::find($id);
            $users->update($request->all());
            return response()->json($users, 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para actualizar este usuario.'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->authorize('delete', User::find($id));
            $users = User::find($id);
            $users->delete();
            return response()->json(null, 204);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para eliminar este usuario.'], 403);
        }
    }
}
