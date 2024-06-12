<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $orders = Order::with('products', 'user')->get();
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
        DB::beginTransaction();

        try {
            $this->authorize('create', Order::class);

            $order = Order::create($request->only([
                'date_order',
                'date_deliver',
                'status',
                'total_price',
                'total_products',
                'id_user'
            ]));

            foreach ($request->products as $product) {
                $order->products()->attach($product['id'], [
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['unit_price']
                ]);
            }

            DB::commit();

            return response()->json($order->load(['user', 'products']), 201);
        } catch (AuthorizationException $e) {
            DB::rollBack();
            return response()->json(['error' => 'No tienes permisos para crear pedidos.'], 403);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el pedido.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $this->authorize('view', Order::find($id));
            $order = Order::with(['user', 'products'])->findOrFail($id);
            return response()->json($order);
        } catch (AuthorizationException) {
            return response()->json(['error' => 'No tienes permisos para ver este pedido.'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $this->authorize('update', Order::find($id));
            $order = Order::find($id);
            $request->validate([
                'status' => 'required|string|in:pendiente,procesando,reparto,entregado,cancelado'
            ]);

            $order->status = $request->status;

            if ($request->status === 'entregado') {
                $order->date_deliver = now()->format('Y-m-d');
            }

            $order->save();
            return response()->json($order, 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para actualizar este pedido.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el pedido.'], 500);
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
