<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderProductRequest;
use App\Models\OrderProduct;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderProductController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('viewAny', OrderProduct::class);
            $orderProducts = OrderProduct::all();
            return response()->json($orderProducts);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para ver los productos de los pedidos.'], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderProductRequest $request)
    {
        try {
            $this->authorize('create', OrderProduct::class);
            $orderProducts = OrderProduct::create($request->all());
            return response()->json($orderProducts, 201);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para crear productos en pedidos.'], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $this->authorize('view', OrderProduct::find($id));
            $orderProducts = OrderProduct::find($id);
            return response()->json($orderProducts);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para ver este producto de pedido.'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderProductRequest $request, string $id)
    {
        try {
            $this->authorize('update', OrderProduct::find($id));
            $orderProducts = OrderProduct::find($id);
            $orderProducts->update($request->all());
            return response()->json($orderProducts, 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para actualizar este producto de pedido.'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->authorize('delete', OrderProduct::find($id));
            $orderProducts = OrderProduct::find($id);
            $orderProducts->delete();
            return response()->json(null, 204);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para eliminar este producto de pedido.'], 403);
        }
    }
}
