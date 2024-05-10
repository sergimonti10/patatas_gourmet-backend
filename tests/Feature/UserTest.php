<?php

use App\Models\User;
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

it('puede recibir una lista de usuarios', function () { //No funciona por el protected hidden del modelo user
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $users = User::factory()->count(3)->create();
    $response = $this->getJson('/api/users');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        '*' => [
            'name',
            'surname',
            'email',
            'password',
            'postal_code',
            'locality',
            'province',
            'street',
            'number',
            'floor',
            'staircase',
            'image',
            'phone',
        ],
    ]);
    $response->assertJsonCount(4);
});

it('puede crear un usuario', function () { //No funciona por el protected hidden del modelo user
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $usersData = User::factory()->make()->toArray();
    $response = $this->postJson("/api/users", $usersData);
    $response->assertStatus(201);
    $response->assertJsonFragment($usersData);
});

it('puede borrar un usuario', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');
    Sanctum::actingAs($user, ['*']);

    $users = User::factory()->create(['id' => 2]);
    $response = $this->deleteJson("/api/users/{$users->id}");
    $response->assertStatus(204);
});

it('no permite a un usuario sin rol hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $users = User::factory()->create(['id' => 2]);
    $response = $this->deleteJson("/api/users/{$users->id}");
    $response->assertStatus(403);
});

it('no permite a un usuario sin autenticar hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $users = User::factory()->create(['id' => 2]);
    $response = $this->deleteJson("/api/users/{$users->id}");
    $response->assertStatus(401);
});

it('no permite a un usuario con roles sin permisos hacer una llamada HTTP', function () {
    $user = User::factory()->create();
    $user->assignRole('user');
    Sanctum::actingAs($user, ['*']);

    $users = User::factory()->create(['id' => 2]);
    $response = $this->deleteJson("/api/users/{$users->id}");
    $response->assertStatus(403);
});
