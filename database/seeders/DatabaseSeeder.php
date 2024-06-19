<?php

namespace Database\Seeders;

use App\Models\Cut;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesSeeder::class);
        //$this->call(UserSeeder::class);
        Cut::factory(10)->create();
        Product::factory(10)->create();
        //Order::factory(10)->create();
        //OrderProduct::factory(10)->create();
    }
}
