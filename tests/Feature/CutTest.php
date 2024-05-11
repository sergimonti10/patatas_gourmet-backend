<?php

use App\Models\User;
use App\Models\Cut;
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

it('puede recibir una lista de cortes', function () { //No funciona por el protected hidden del modelo user
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $cut = Cut::factory()->count(3)->create();
    $response = $this->getJson('/api/cuts');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        '*' => [
            'name',
            'description',
            'weight',
        ],
    ]);
    $response->assertJsonCount(3);
});

it('puede crear un corte', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $cutsData = Cut::factory()->make()->toArray();
    $response = $this->postJson("/api/cuts", $cutsData);
    $response->assertStatus(201);
    $response->assertJsonFragment($cutsData);
});

it('puede borrar un corte', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $cuts = Cut::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/cuts/{$cuts->id}");
    $response->assertStatus(204);
});

it('no permite a un usuario sin rol hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $cuts = Cut::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/cuts/{$cuts->id}");
    $response->assertStatus(403);
});

it('no permite a un usuario sin autenticar hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $cuts = Cut::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/cuts/{$cuts->id}");
    $response->assertStatus(401);
});

it('no permite a un usuario con roles sin permisos hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    $user->assignRole('user');
    Sanctum::actingAs($user, ['*']);

    $cuts = Cut::factory()->create(['id' => 1]);
    $response = $this->deleteJson("/api/cuts/{$cuts->id}");
    $response->assertStatus(403);
});
