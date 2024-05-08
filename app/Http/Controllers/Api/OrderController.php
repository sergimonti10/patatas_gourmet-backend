<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('viewAny', Order::class);
            $orders = Order::all();
            return response()->json($orders);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para ver los pedidos.'], 403);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        try {
            $this->authorize('create', Order::class);
            $orders = Order::create($request->all());
            return response()->json($orders, 201);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para crear pedidos.'], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $this->authorize('view', Order::find($id));
            $orders = Order::find($id);
            return response()->json($orders);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para ver este pedido.'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, string $id)
    {
        try {
            $this->authorize('update', Order::find($id));
            $orders = Order::find($id);
            $orders->update($request->all());
            return response()->json($orders, 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para actualizar este pedido.'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->authorize('delete', Order::find($id));
            $orders = Order::find($id);
            $orders->delete();
            return response()->json(null, 204);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para eliminar este pedido.'], 403);
        }
    }
}
