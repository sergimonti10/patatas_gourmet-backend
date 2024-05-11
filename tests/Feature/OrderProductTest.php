<?php

use App\Models\Order;
use App\Models\User;
use App\Models\OrderProduct;
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

it('puede recibir una lista de productos de pedidos', function () { //No funciona por el protected hidden del modelo user
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $orderProduct = OrderProduct::factory()->count(3)->create();
    $response = $this->getJson('/api/orderProducts');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        '*' => [
            'quantity',
            'unit_price',
            'id_product',
            'id_order',
        ],
    ]);
    $response->assertJsonCount(3);
});

it('puede crear un producto de pedido', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $order = Order::factory()->create();
    $product = Product::factory()->create();
    $orderProductsData = OrderProduct::factory()->make()->toArray();
    $response = $this->postJson("/api/orderProducts", $orderProductsData);
    $response->assertStatus(201);
    $response->assertJsonFragment($orderProductsData);
});

it('puede borrar un producto de pedido', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $orderProducts = OrderProduct::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/orderProducts/{$orderProducts->id}");
    $response->assertStatus(204);
});

it('no permite a un usuario sin rol hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $orderProducts = OrderProduct::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/orderProducts/{$orderProducts->id}");
    $response->assertStatus(403);
});

it('no permite a un usuario sin autenticar hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $orderProducts = OrderProduct::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/orderProducts/{$orderProducts->id}");
    $response->assertStatus(401);
});

it('no permite a un usuario con roles sin permisos hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    $user->assignRole('user');
    Sanctum::actingAs($user, ['*']);

    $orderProducts = OrderProduct::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/orderProducts/{$orderProducts->id}");
    $response->assertStatus(403);
});
