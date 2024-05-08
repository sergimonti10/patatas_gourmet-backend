<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CutRequest;
use App\Models\Cut;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CutController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('viewAny', Cut::class);
            $cuts = Cut::all();
            return response()->json($cuts);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para ver los tipos de corte.'], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CutRequest $request)
    {
        try {
            $this->authorize('create', Cut::class);
            $cuts = Cut::create($request->all());
            return response()->json($cuts, 201);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para crear cortes.'], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $this->authorize('view', Cut::find($id));
            $cuts = Cut::find($id);
            return response()->json($cuts);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para ver este corte.'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CutRequest $request, string $id)
    {
        try {
            $this->authorize('update', Cut::find($id));
            $cuts = Cut::find($id);
            $cuts->update($request->all());
            return response()->json($cuts, 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para actualizar este corte.'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->authorize('delete', Cut::find($id));
            $cuts = Cut::find($id);
            $cuts->delete();
            return response()->json(null, 204);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para eliminar este corte.'], 403);
        }
    }
}
