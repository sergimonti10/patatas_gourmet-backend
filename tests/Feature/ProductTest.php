<?php

use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

/**
 * Configura el entorno antes de cada prueba.
 */
beforeEach(function () {
    Role::create(['name' => 'super-admin']);
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'user']);
});

it('puede recibir una lista de productos', function () { //No funciona por el protected hidden del modelo user
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $product = Product::factory()->count(3)->create();
    $response = $this->getJson('/api/products');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        '*' => [
            'name',
            'description',
            'price',
            'weight',
            'image',
            'image2',
            'id_cut',
        ],
    ]);
    $response->assertJsonCount(3);
});

it('puede crear un producto', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $productData = Product::factory()->make()->toArray();
    $response = $this->postJson("/api/products", $productData);
    $response->assertStatus(201);
    $response->assertJsonFragment($productData);
});

it('puede borrar un de producto', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $products = Product::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/products/{$products->id}");
    $response->assertStatus(204);
});

it('no permite a un usuario sin rol hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $products = Product::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/products/{$products->id}");
    $response->assertStatus(403);
});

it('no permite a un usuario sin autenticar hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $products = Product::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/products/{$products->id}");
    $response->assertStatus(401);
});

it('no permite a un usuario con roles sin permisos hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    $user->assignRole('user');
    Sanctum::actingAs($user, ['*']);

    $products = Product::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/products/{$products->id}");
    $response->assertStatus(403);
});
