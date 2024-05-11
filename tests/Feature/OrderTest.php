<?php

use App\Models\Order;
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

it('puede recibir una lista de pedidos', function () { //No funciona por el protected hidden del modelo user
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $order = Order::factory()->count(3)->create();
    $response = $this->getJson('/api/orders');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        '*' => [
            'date_order',
            'date_deliver',
            'status',
            'total_price',
            'total_products',
            'id_user',
        ],
    ]);
    $response->assertJsonCount(3);
});

it('puede crear un pedido', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $orderData = Order::factory()->make()->toArray();
    $response = $this->postJson("/api/orders", $orderData);
    $response->assertStatus(201);
    $response->assertJsonFragment($orderData);
});

it('puede borrar un de pedido', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $orders = Order::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/orders/{$orders->id}");
    $response->assertStatus(204);
});

it('no permite a un usuario sin rol hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $orders = Order::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/orders/{$orders->id}");
    $response->assertStatus(403);
});

it('no permite a un usuario sin autenticar hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $orders = Order::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/orders/{$orders->id}");
    $response->assertStatus(401);
});

it('no permite a un usuario con roles sin permisos hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    $user->assignRole('user');
    Sanctum::actingAs($user, ['*']);

    $orders = Order::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/orders/{$orders->id}");
    $response->assertStatus(403);
});
