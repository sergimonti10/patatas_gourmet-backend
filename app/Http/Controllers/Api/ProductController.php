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
            $products = Product::with('cut')->get();

            $response = response()->json($products);
            $response->headers->set('Access-Control-Allow-Origin', 'https://patatas-gourmet-frontend-3tb7.vercel.app');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');

            return $response;
        } catch (AuthorizationException $e) {
            $response = response()->json(['error' => 'No tienes permisos para ver los productos.'], 403);
            $response->headers->set('Access-Control-Allow-Origin', 'https://patatas-gourmet-frontend-3tb7.vercel.app');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');

            return $response;
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    // public function store(ProductRequest $request)
    // {
    //     try {
    //         $this->authorize('create', Product::class);

    //         $imageName = null;
    //         $imageName2 = null;

    //         if ($request->hasFile('image')) {
    //             $imageName = time() . '-' . $request->file('image')->getClientOriginalName();
    //             $request->file('image')->storeAs('public/img_products', $imageName);
    //         }

    //         if ($request->hasFile('image2')) {
    //             $imageName2 = time() . '-' . $request->file('image2')->getClientOriginalName();
    //             $request->file('image2')->storeAs('public/img_products', $imageName2);
    //         }

    //         $productData = $request->except(['image', 'image2']);

    //         $productData['image'] = $imageName;
    //         $productData['image2'] = $imageName2;

    //         $product = Product::create($productData);

    //         return response()->json($product, 201);
    //     } catch (AuthorizationException $e) {
    //         return response()->json(['error' => 'No tienes permisos para crear productos.'], 403);
    //     }
    // }

    public function store(ProductRequest $request)
    {
        try {
            $this->authorize('create', Product::class);

            $imageBase64 = null;
            $imageBase64_2 = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageMimeType = $image->getClientMimeType();
                $imageBase64 = 'data:' . $imageMimeType . ';base64,' . base64_encode(file_get_contents($image->getRealPath()));
            }

            if ($request->hasFile('image2')) {
                $image2 = $request->file('image2');
                $imageMimeType2 = $image2->getClientMimeType();
                $imageBase64_2 = 'data:' . $imageMimeType2 . ';base64,' . base64_encode(file_get_contents($image2->getRealPath()));
            }

            $productData = $request->except(['image', 'image2']);

            $productData['image'] = $imageBase64;
            $productData['image2'] = $imageBase64_2;

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
    // public function update(ProductRequest $request, string $id)
    // {
    //     try {
    //         $product = Product::find($id);
    //         $this->authorize('update', $product);

    //         $productData = $request->except(['image', 'image2']);

    //         if ($request->hasFile('image')) {
    //             if ($product->image) {
    //                 Storage::delete('public/img_products/' . $product->image);
    //             }
    //             $imageName = time() . '-' . $request->file('image')->getClientOriginalName();
    //             $request->file('image')->storeAs('public/img_products', $imageName);
    //             $productData['image'] = $imageName;
    //         }

    //         if ($request->hasFile('image2')) {
    //             if ($product->image2) {
    //                 Storage::delete('public/img_products/' . $product->image2);
    //             }
    //             $imageName2 = time() . '-' . $request->file('image2')->getClientOriginalName();
    //             $request->file('image2')->storeAs('public/img_products', $imageName2);
    //             $productData['image2'] = $imageName2;
    //         }

    //         $product->update($productData);

    //         return response()->json($product, 200);
    //     } catch (AuthorizationException $e) {
    //         return response()->json(['error' => 'No tienes permisos para actualizar este corte.'], 403);
    //     }
    // }

    public function update(ProductRequest $request, string $id)
    {
        try {
            $product = Product::find($id);
            $this->authorize('update', $product);

            $productData = $request->except(['image', 'image2']);

            if ($request->hasFile('image')) {
                if ($product->image) {
                    // No se necesita eliminar la imagen anterior si está almacenada como base64
                }
                $image = $request->file('image');
                $imageMimeType = $image->getClientMimeType();
                $imageBase64 = 'data:' . $imageMimeType . ';base64,' . base64_encode(file_get_contents($image->getRealPath()));
                $productData['image'] = $imageBase64;
            }

            if ($request->hasFile('image2')) {
                if ($product->image2) {
                    // No se necesita eliminar la imagen anterior si está almacenada como base64
                }
                $image2 = $request->file('image2');
                $imageMimeType2 = $image2->getClientMimeType();
                $imageBase64_2 = 'data:' . $imageMimeType2 . ';base64,' . base64_encode(file_get_contents($image2->getRealPath()));
                $productData['image2'] = $imageBase64_2;
            }

            $product->update($productData);

            return response()->json($product, 200);
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
