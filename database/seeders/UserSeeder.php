<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();
        $numberOfUsers = 10;
        $faker = Faker::create();

        for ($i = 0; $i < $numberOfUsers; $i++) {
            $userRoles = $roles->random(rand(1, 3));
            $cp = $faker->numberBetween(10000, 99999);
            $user = User::create([
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('1234'),
                'postal_code' => $cp,
                'locality' => $faker->city,
                'province' => $faker->state,
                'street' => $faker->streetName,
                'number' => $faker->buildingNumber,
                'floor' => $faker->randomElement([$faker->numberBetween(1, 10), null]), // Algunos usuarios pueden no tener piso
                'staircase' => $faker->randomElement(['A', 'B', 'C', null]), // Algunos usuarios pueden no tener escalera
                'image' => $faker->imageUrl(),
                'phone' => $faker->phoneNumber,
            ]);
            $user->assignRole($userRoles);
        }
    }
}
