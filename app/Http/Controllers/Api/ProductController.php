<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            header('Access-Control-Allow-Origin: https://patatas-gourmet-frontend-ehv2-pau80ngzr-sergimonti10s-projects.vercel.app');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
            $products = Product::with('cut')->get();
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

            $imageName = null;
            $imageName2 = null;

            if ($request->hasFile('image')) {
                $imageName = time() . '-' . $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('public/img_products', $imageName);
            }

            if ($request->hasFile('image2')) {
                $imageName2 = time() . '-' . $request->file('image2')->getClientOriginalName();
                $request->file('image2')->storeAs('public/img_products', $imageName2);
            }

            $productData = $request->except(['image', 'image2']);

            $productData['image'] = $imageName;
            $productData['image2'] = $imageName2;

            $product = Product::create($productData);

            return response()->json($product, 201);
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
            $product = Product::with('cut')->find($id);
            return response()->json($product);
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
            $product = Product::find($id);
            $this->authorize('update', $product);

            $productData = $request->except(['image', 'image2']);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::delete('public/img_products/' . $product->image);
                }
                $imageName = time() . '-' . $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('public/img_products', $imageName);
                $productData['image'] = $imageName;
            }

            if ($request->hasFile('image2')) {
                if ($product->image2) {
                    Storage::delete('public/img_products/' . $product->image2);
                }
                $imageName2 = time() . '-' . $request->file('image2')->getClientOriginalName();
                $request->file('image2')->storeAs('public/img_products', $imageName2);
                $productData['image2'] = $imageName2;
            }

            $product->update($productData);

            return response()->json($product, 200);
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
            $this->authorize('delete', Product::find($id));
            $products = Product::find($id);
            $products->delete();
            return response()->json(null, 204);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'No tienes permisos para eliminar este producto.'], 403);
        }
    }
}
