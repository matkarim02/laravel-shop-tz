<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
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
        User::factory()->count(5)->create();

        $this->call(ProductSeeder::class);

        $users = User::all();
        $products = Product::all();

        for ($i = 0; $i < 5; $i++) {
            $user = $users->random();
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'new',
                'total_price' => 0,
            ]);

            $attachProducts = $products->random(rand(2, 4));
            $total = 0;
            foreach ($attachProducts as $product) {
                $quantity = rand(1, 3);
                $order->products()->attach($product->id, ['quantity' => $quantity]);
                $total += $product->price * $quantity;
            }
            $order->update(['total_price' => $total]);
        }
    }
}
