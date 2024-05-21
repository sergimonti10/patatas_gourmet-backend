<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('viewAny', Product::class);
            $products = Product::all();
            return response()->json($products);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para ver los productos.'], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $this->authorize('create', Product::class);
            $products = Product::create($request->all());
            return response()->json($products, 201);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para crear productos.'], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $this->authorize('view', Product::find($id));
            $products = Product::find($id);
            return response()->json($products);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para ver este producto.'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        try {
            $this->authorize('update', Product::find($id));
            $products = Product::find($id);
            $products->update($request->all());
            return response()->json($products, 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para actualizar este producto.'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->authorize('delete', Product::find($id));
            $products = Product::find($id);
            $products->delete();
            return response()->json(null, 204);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para eliminar este producto.'], 403);
        }
    }
}
